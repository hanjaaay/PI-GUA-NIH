<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Services\BookingService;
use App\Support\BookingStatuses;
use App\Support\PaymentStatuses;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class ExpireBookings extends Command
{
    /*
    |--------------------------------------------------------------------------
    | SIGNATURE
    |--------------------------------------------------------------------------
    */

    protected $signature =
        'bookings:expire';

    /*
    |--------------------------------------------------------------------------
    | DESCRIPTION
    |--------------------------------------------------------------------------
    */

    protected $description =
        'Expire unpaid bookings and restore stock';

    /*
    |--------------------------------------------------------------------------
    | HANDLE
    |--------------------------------------------------------------------------
    */

    public function handle(BookingService $bookingService)
    {
        /** @var \Illuminate\Database\Eloquent\Collection<int, Booking> $expiredBookings */
        $expiredBookings = Booking::where('status', BookingStatuses::PENDING)
            ->where('payment_status', PaymentStatuses::PENDING)
            ->where('expired_at', '<', now())
            ->get();

        if ($expiredBookings->isEmpty()) {
            $this->info('No expired bookings found.');
            return SymfonyCommand::SUCCESS;
        }

        foreach ($expiredBookings as $booking) {
            /** @var Booking $booking */
            try {
                $expiredBooking = $bookingService->expire($booking);

                if ($expiredBooking->status === BookingStatuses::EXPIRED) {
                    $this->info("Expired booking: {$expiredBooking->order_id} | Stock restored: {$expiredBooking->quantity}");
                    \Illuminate\Support\Facades\Log::info('Booking expired by scheduler', [
                        'order_id' => $expiredBooking->order_id,
                        'quantity_restored' => $expiredBooking->quantity,
                        'ticket_id' => $expiredBooking->ticket_id,
                    ]);
                } else {
                    $this->warn("Booking {$booking->order_id} skipped (status: {$expiredBooking->status})");
                }
            } catch (\Throwable $e) {
                $this->error("Failed to expire booking {$booking->order_id}: {$e->getMessage()}");
                \Illuminate\Support\Facades\Log::error('ExpireBookings command failed', [
                    'order_id' => $booking->order_id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return SymfonyCommand::SUCCESS;
    }
}
