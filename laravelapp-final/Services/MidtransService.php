<?php

namespace App\Services;

use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class MidtransService
{
    public function __construct(
        private readonly PaymentService $paymentService
    ) {}

    public function createTransaction(Payment $payment)
    {
        try {
            $snapToken = $this->paymentService->createSnapToken(
                $payment->booking->loadMissing('user')
            );

            $payment->update([
                'payment_details' => array_merge($payment->payment_details ?? [], [
                    'snap_token' => $snapToken,
                    'midtrans_transaction_id' => $payment->payment_code,
                ]),
            ]);

            return $snapToken;

        } catch (\Exception $e) {
            Log::error('Midtrans transaction creation failed: '.$e->getMessage());
            throw $e;
        }
    }

    public function handleNotification($notification)
    {
        try {
            $payload = is_string($notification)
                ? json_decode($notification, true)
                : $notification;

            if (! is_array($payload)) {
                throw new InvalidArgumentException('Invalid Midtrans notification payload.');
            }

            $result = $this->paymentService->handleMidtransNotification($payload);

            if (! $result['success']) {
                Log::warning('Midtrans notification was rejected.', [
                    'message' => $result['message'],
                    'order_id' => $payload['order_id'] ?? null,
                ]);
            }

            return (bool) ($result['success'] ?? false);

        } catch (\Exception $e) {
            Log::error('Midtrans notification handling failed: '.$e->getMessage());
            throw $e;
        }
    }
}
