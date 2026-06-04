<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use App\Support\BookingStatuses;
use App\Support\PaymentStatuses;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentService
{
    public function __construct(
        private readonly BookingService $bookingService
    ) {
        $this->configureMidtrans();
    }

    public function createSnapToken(Booking $booking): string
    {
        if ($booking->status === BookingStatuses::PAID) {
            throw new \Exception('Booking already paid.');
        }

        $payment = Payment::where('booking_id', $booking->id)->first();

        if (
            $payment &&
            $payment->payment_status === PaymentStatuses::PENDING &&
            isset($payment->payment_details['snap_token'])
        ) {
            return $payment->payment_details['snap_token'];
        }

        $snapToken = $this->generateSnapTokenFromMidtrans($booking);

        if (! $payment) {

            Payment::create([
                'booking_id' => $booking->id,
                'payment_code' => $booking->order_id,
                'amount' => $booking->total_price,
                'currency' => 'IDR',
                'payment_method' => 'midtrans',
                'payment_status' => PaymentStatuses::PENDING,
                'payment_details' => [
                    'snap_token' => $snapToken,
                ],
            ]);

        } else {

            $payment->update([
                'payment_status' => PaymentStatuses::PENDING,
                'payment_details' => array_merge(
                    $payment->payment_details ?? [],
                    [
                        'snap_token' => $snapToken,
                    ]
                ),
            ]);
        }

        return $snapToken;
    }

    private function generateSnapTokenFromMidtrans(Booking $booking): string
    {
        $params = [
            'transaction_details' => [
                'order_id' => $booking->order_id,
                'gross_amount' => (int) $booking->total_price,
            ],
            'expiry' => [
                'start_time' => now()->format('Y-m-d H:i:s O'),
                'unit' => 'minute',
                'duration' => $booking->remainingMinutes(),
            ],
            'customer_details' => [
                'first_name' => $booking->user?->name,
                'email' => $booking->user?->email,
            ],
        ];

        return Snap::getSnapToken($params);
    }

    public function handleMidtransNotification(array $payload): array
    {
        Log::info('MIDTRANS CALLBACK PAYLOAD', $payload);

        $orderId = (string) ($payload['order_id'] ?? '');
        $statusCode = (string) ($payload['status_code'] ?? '');
        $grossAmount = (string) ($payload['gross_amount'] ?? '');

        if (
            ! $orderId ||
            ! $statusCode ||
            ! $grossAmount ||
            empty($payload['signature_key'])
        ) {
            return $this->result(
                false,
                'Incomplete Midtrans payload.',
                400
            );
        }

        $booking = Booking::where('order_id', $orderId)->first();

        if (! $booking) {

            Log::error('BOOKING NOT FOUND', [
                'order_id' => $orderId
            ]);

            return $this->result(
                false,
                'Booking not found.',
                404
            );
        }

        if (
            ! $this->signatureIsValid(
                $orderId,
                $statusCode,
                $grossAmount,
                (string) $payload['signature_key']
            )
        ) {

            Log::warning('INVALID MIDTRANS SIGNATURE', [
                'order_id' => $orderId
            ]);

            return $this->result(
                false,
                'Invalid signature.',
                403
            );
        }

        if (! $this->amountMatches($booking, $grossAmount)) {

            Log::warning('INVALID MIDTRANS AMOUNT', [
                'expected' => $booking->total_price,
                'actual' => $grossAmount,
            ]);

            return $this->result(
                false,
                'Invalid amount.',
                422
            );
        }

        $payment = $this->recordPaymentNotification(
            $booking,
            $payload
        );

        $transactionStatus =
            (string) ($payload['transaction_status'] ?? '');

        $fraudStatus =
            (string) ($payload['fraud_status'] ?? '');

        if ($this->isPaidStatus(
            $transactionStatus,
            $fraudStatus
        )) {

            $confirmedBooking =
                $this->bookingService->confirmPaidForce(
                    $booking,
                    (string) ($payload['transaction_id'] ?? null)
                );

            $payment->markAsPaid();

            return $this->result(
                true,
                'Payment confirmed.'
            );
        }

        if ($transactionStatus === 'pending') {

            $payment->update([
                'payment_status' => PaymentStatuses::PENDING,
            ]);

            return $this->result(
                true,
                'Payment pending.'
            );
        }

        if ($transactionStatus === 'expire') {

            $this->bookingService->expire(
                $booking,
                true
            );

            $payment->markAsExpired();

            return $this->result(
                true,
                'Payment expired.'
            );
        }

        if (
            in_array(
                $transactionStatus,
                ['cancel', 'deny', 'failure'],
                true
            )
        ) {

            $this->bookingService->markFailed($booking);

            $payment->markAsFailed();

            return $this->result(
                true,
                'Payment failed.'
            );
        }

        return $this->result(
            true,
            'Callback processed.'
        );
    }

    public function snapScriptUrl(): string
    {
        return config('midtrans.is_production')
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    }

    public function signatureIsValid(
        string $orderId,
        string $statusCode,
        string $grossAmount,
        string $signature
    ): bool {

        $expected = hash(
            'sha512',
            $orderId .
            $statusCode .
            $grossAmount .
            config('midtrans.server_key')
        );

        return hash_equals($expected, $signature);
    }

    public function amountMatches(
        Booking $booking,
        string $grossAmount
    ): bool {

        return
            (int) round((float) $booking->total_price)
            ===
            (int) round((float) $grossAmount);
    }

    private function recordPaymentNotification(
        Booking $booking,
        array $payload
    ): Payment {

        $payment = Payment::firstOrCreate(
            [
                'payment_code' => $booking->order_id
            ],
            [
                'booking_id' => $booking->id,
                'amount' => $booking->total_price,
                'currency' => 'IDR',
                'payment_method' => 'midtrans',
                'payment_status' => PaymentStatuses::PENDING,
                'expired_at' => $booking->expired_at,
            ]
        );

        $payment->update([
            'booking_id' => $booking->id,
            'amount' => $booking->total_price,
            'payment_method' => 'midtrans',
            'payment_channel' => $payload['payment_type'] ?? null,
            'transaction_id' => $payload['transaction_id'] ?? null,
            'payment_details' => $payload,
        ]);

        return $payment;
    }

    private function isPaidStatus(
        string $transactionStatus,
        string $fraudStatus
    ): bool {

        if ($transactionStatus === 'settlement') {
            return true;
        }

        return
            $transactionStatus === 'capture'
            &&
            (
                $fraudStatus === 'accept'
                ||
                $fraudStatus === ''
            );
    }

    private function configureMidtrans(): void
    {
        Config::$serverKey =
            config('midtrans.server_key');

        Config::$clientKey =
            config('midtrans.client_key');

        Config::$isProduction =
            (bool) config('midtrans.is_production');

        Config::$isSanitized = true;

        Config::$is3ds = true;
    }

    private function result(
        bool $success,
        string $message,
        int $httpStatus = 200
    ): array {

        return [
            'success' => $success,
            'message' => $message,
            'http_status' => $httpStatus,
        ];
    }
}