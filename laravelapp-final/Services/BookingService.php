<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingTicket;
use App\Models\Payment;
use App\Models\Ticket;
use App\Models\TouristAttraction;
use App\Models\User;
use App\Support\BookingStatuses;
use App\Support\PaymentStatuses;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BookingService
{
    public function createBooking(
        User $user,
        TouristAttraction $event,
        int $ticketId,
        int $quantity
    ): Booking {
        return DB::transaction(function () use ($user, $event, $ticketId, $quantity) {
            $ticket = Ticket::whereKey($ticketId)
                ->lockForUpdate()
                ->firstOrFail();

            if (! $ticket->is_active) {
                throw new RuntimeException('Ticket is not active.');
            }

            if ((int) $ticket->tourist_attraction_id !== (int) $event->id) {
                throw new RuntimeException('Invalid ticket selection.');
            }

            if ((int) $ticket->available_quantity < $quantity) {
                throw new RuntimeException('Ticket stock not available.');
            }

            $ticket->decrement('available_quantity', $quantity);

            $booking = Booking::create([
                'user_id' => $user->id,
                'tourist_attraction_id' => $event->id,
                'ticket_id' => $ticket->id,
                'visit_date' => $this->resolveVisitDate($event, $ticket),
                'quantity' => $quantity,
                'total_price' => $ticket->price * $quantity,
                'status' => BookingStatuses::PENDING,
                'payment_status' => PaymentStatuses::PENDING,
                'expired_at' => now()->addMinutes(config('booking.expiry_minutes', 15)),
                'order_id' => $this->generateOrderId(),
            ]);

            Payment::create([
                'booking_id' => $booking->id,
                'payment_code' => $booking->order_id,
                'amount' => $booking->total_price,
                'currency' => 'IDR',
                'payment_method' => 'midtrans',
                'payment_status' => 'pending',
                'expired_at' => $booking->expired_at,
            ]);

            return $booking;
        });
    }

    public function expireIfDue(Booking $booking): Booking
    {
        if (! $booking->shouldExpire()) {
            return $booking;
        }

        return $this->expire($booking);
    }

    public function expire(Booking $booking, bool $force = false): Booking
    {
        return DB::transaction(function () use ($booking, $force) {
            $lockedBooking = Booking::whereKey($booking->id)
                ->lockForUpdate()
                ->first();

            if (! $lockedBooking || $lockedBooking->status !== BookingStatuses::PENDING) {
                return $lockedBooking ?? $booking;
            }

            if (! $force && ! $lockedBooking->shouldExpire()) {
                return $lockedBooking;
            }

            $this->restoreReservedStock($lockedBooking);

            $lockedBooking->update([
                'status' => BookingStatuses::EXPIRED,
                'payment_status' => PaymentStatuses::EXPIRED,
            ]);

            return $lockedBooking->fresh();
        });
    }

    public function cancel(Booking $booking): Booking
    {
        return DB::transaction(function () use ($booking) {
            $lockedBooking = Booking::whereKey($booking->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedBooking->status !== BookingStatuses::PENDING) {
                return $lockedBooking;
            }

            $this->restoreReservedStock($lockedBooking);

            $lockedBooking->update([
                'status' => BookingStatuses::CANCELLED,
                'payment_status' => PaymentStatuses::FAILED,
            ]);

            return $lockedBooking->fresh();
        });
    }

    public function deletePending(Booking $booking): void
    {
        DB::transaction(function () use ($booking) {
            $lockedBooking = Booking::whereKey($booking->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedBooking->status === BookingStatuses::PENDING) {
                $this->restoreReservedStock($lockedBooking);
            }

            $lockedBooking->delete();
        });
    }

    public function markFailed(Booking $booking): Booking
    {
        return DB::transaction(function () use ($booking) {
            $lockedBooking = Booking::whereKey($booking->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedBooking->status !== BookingStatuses::PENDING) {
                return $lockedBooking;
            }

            $this->restoreReservedStock($lockedBooking);

            $lockedBooking->update([
                'status' => BookingStatuses::FAILED,
                'payment_status' => PaymentStatuses::FAILED,
            ]);

            return $lockedBooking->fresh();
        });
    }

    public function confirmPaid(Booking $booking, ?string $midtransOrderId = null): Booking
    {
        return DB::transaction(function () use ($booking, $midtransOrderId) {
            $lockedBooking = Booking::whereKey($booking->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedBooking->status === BookingStatuses::PAID) {
                $this->generateQrTickets($lockedBooking);

                return $lockedBooking->fresh();
            }

            if ($lockedBooking->status !== BookingStatuses::PENDING) {
                return $lockedBooking;
            }

            if ($lockedBooking->shouldExpire()) {
                $this->restoreReservedStock($lockedBooking);

                $lockedBooking->update([
                    'status' => BookingStatuses::EXPIRED,
                    'payment_status' => PaymentStatuses::EXPIRED,
                ]);

                return $lockedBooking->fresh();
            }

            $lockedBooking->update([
                'status' => BookingStatuses::PAID,
                'payment_status' => PaymentStatuses::PAID,
                'midtrans_order_id' => $midtransOrderId,
            ]);

            $this->generateQrTickets($lockedBooking);

            return $lockedBooking->fresh();
        });
    }

    public function confirmPaidForce(Booking $booking, ?string $midtransOrderId = null): Booking
    {
        return DB::transaction(function () use ($booking, $midtransOrderId) {
            $lockedBooking = Booking::whereKey($booking->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedBooking->status === BookingStatuses::PAID) {
                $this->generateQrTickets($lockedBooking);
                return $lockedBooking->fresh();
            }

            if (in_array($lockedBooking->status, [BookingStatuses::CANCELLED, BookingStatuses::FAILED], true)) {
                return $lockedBooking;
            }

            $lockedBooking->update([
    'status' => BookingStatuses::PAID,
    'payment_status' => PaymentStatuses::PAID,
    'midtrans_order_id' => $midtransOrderId,
]);

$this->generateQrTickets($lockedBooking);

event(new \App\Events\BookingPaid($lockedBooking));

return $lockedBooking->fresh();
        });
    }

    public function markPaymentChallenge(Booking $booking): Booking
    {
        return DB::transaction(function () use ($booking) {
            $lockedBooking = Booking::whereKey($booking->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedBooking->status !== BookingStatuses::PENDING) {
                return $lockedBooking;
            }

            $lockedBooking->update([
                'payment_status' => PaymentStatuses::CHALLENGE,
            ]);

            return $lockedBooking->fresh();
        });
    }

    private function generateQrTickets(Booking $booking): void
    {
        if ($booking->bookingTickets()->exists()) {
            return;
        }

        for ($i = 1; $i <= $booking->quantity; $i++) {
            $ticketCode = $this->generateTicketCode();
            $qrContent = $booking->order_id.'|'.$ticketCode;

            BookingTicket::create([
                'booking_id' => $booking->id,
                'ticket_code' => $ticketCode,
                'qr_code' => base64_encode(
                    QrCode::format('svg')
                        ->size(300)
                        ->generate($qrContent)
                ),
            ]);
        }
    }

    private function restoreReservedStock(Booking $booking): void
    {
        $ticket = Ticket::whereKey($booking->ticket_id)
            ->lockForUpdate()
            ->first();

        if (! $ticket) {
            return;
        }

        $ticket->increment('available_quantity', $booking->quantity);
    }

    private function resolveVisitDate(TouristAttraction $event, Ticket $ticket): mixed
    {
        return $event->start_date
            ?? $ticket->valid_date
            ?? $ticket->valid_from
            ?? now();
    }

    private function generateOrderId(): string
    {
        do {
            $orderId = 'BOOK-'.strtoupper(Str::random(16));
        } while (Booking::where('order_id', $orderId)->exists());

        return $orderId;
    }

    private function generateTicketCode(): string
    {
        do {
            $ticketCode = 'TKT-'.strtoupper(Str::random(20));
        } while (BookingTicket::where('ticket_code', $ticketCode)->exists());

        return $ticketCode;
    }
}
