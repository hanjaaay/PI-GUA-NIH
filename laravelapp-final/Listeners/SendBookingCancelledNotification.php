<?php

namespace App\Listeners;

use App\Events\BookingCancelled;
use App\Mail\BookingCancelledMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendBookingCancelledNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(BookingCancelled $event)
    {
        $booking = $event->booking;

        Mail::to($booking->user->email)->send(new BookingCancelledMail($booking));
    }
}
