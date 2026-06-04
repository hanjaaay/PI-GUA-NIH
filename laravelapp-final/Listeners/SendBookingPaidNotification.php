<?php

namespace App\Listeners;

use App\Events\BookingPaid;
use App\Mail\BookingPaidMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendBookingPaidNotification implements ShouldQueue
{
    use InteractsWithQueue;

   public function handle(BookingPaid $event)
{
    $booking = $event->booking;

    try {
        Mail::to($booking->user->email)
            ->send(new BookingPaidMail($booking));
    } catch (\Throwable $e) {
        Log::error('Failed to send booking paid email', [
            'booking_id' => $booking->id,
            'error' => $e->getMessage(),
        ]);
    }
}
}