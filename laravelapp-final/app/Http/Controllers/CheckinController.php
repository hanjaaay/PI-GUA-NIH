<?php

namespace App\Http\Controllers;

use App\Models\BookingTicket;
use App\Support\BookingStatuses;
use App\Support\PaymentStatuses;
use Illuminate\Support\Facades\DB;
use App\Models\TicketCheckin;
class CheckinController extends Controller
{
    public function show($ticketCode)
    {
        $ticket = BookingTicket::where(
            'ticket_code',
            $ticketCode
        )->first();

        return $this->resultFor($ticket, false);
    }




    public function checkin($ticketCode)
    {
        try {

            return DB::transaction(function () use ($ticketCode) {

                $ticket = BookingTicket::lockForUpdate()
                    ->where('ticket_code', $ticketCode)
                    ->first();

                return $this->resultFor($ticket, true);
            });

        } catch (\Exception $e) {

            return view(
                'checkin.result',
                [

                    'status' => 'error',

                    'message' => 'Unexpected scanner error.',
                ]
            );
        }
    }

    private function resultFor(
        ?BookingTicket $ticket,
        bool $consume
    ) {

       



        if (! $ticket) {
            return view('checkin.result', [
                'status' => 'invalid',
                'message' => 'Ticket not found.',
            ]);
        }

        $ticket->loadMissing([
            'booking.touristAttraction',
            'booking.user',
        ]);

        $booking = $ticket->booking;
        $event = $booking?->touristAttraction;
        $user = $booking?->user;

        $ticketDate = $booking?->ticket?->valid_date;

if ($ticketDate) {

    $today =
        now()->format('Y-m-d');

    $validDate =
        \Carbon\Carbon::parse($ticketDate)
            ->format('Y-m-d');

    if ($today !== $validDate) {

        return $this->ticketResult(
            'invalid_date',

            'Ticket is only valid on ' .
            \Carbon\Carbon::parse($ticketDate)
                ->format('d F Y'),

            $ticket,
            $booking,
            $event,
            $user
        );
    }
}
     

        if (! $booking || $booking->payment_status !== PaymentStatuses::PAID) {
            return $this->ticketResult(
                'unpaid',
                'Booking has not been paid.',
                $ticket,
                $booking,
                $event,
                $user
            );
        }

        if ($booking->status === BookingStatuses::EXPIRED) {
            return $this->ticketResult(
                'expired',
                'Booking has expired.',
                $ticket,
                $booking,
                $event,
                $user
            );
        }

        $ticketType =
    strtolower($booking?->ticket?->ticket_type ?? '');

$isVip =
    str_contains($ticketType, 'vip');

if ($isVip) {

    $alreadyCheckedToday =
        $ticket->checkins()
            ->whereDate(
                'checkin_date',
                today()
            )
            ->exists();

    if ($alreadyCheckedToday) {

        return $this->ticketResult(
            'used',

            'VIP ticket already used today.',

            $ticket,
            $booking,
            $event,
            $user
        );
    }

} else {

    $ticketType =
    strtolower($booking?->ticket?->ticket_type ?? '');

$isVip =
    str_contains($ticketType, 'vip');

if ($isVip) {

    $alreadyCheckedToday =
        $ticket->checkins()
            ->whereDate(
                'checkin_date',
                today()
            )
            ->exists();

    if ($alreadyCheckedToday) {

        return $this->ticketResult(
            'used',

            'VIP ticket already used today.',

            $ticket,
            $booking,
            $event,
            $user
        );
    }

} else {

    $ticketType =
    strtolower($booking?->ticket?->ticket_type ?? '');

$isVip =
    str_contains($ticketType, 'vip');

if ($isVip) {

    $alreadyCheckedToday =
        $ticket->checkins()
            ->whereDate(
                'checkin_date',
                today()
            )
            ->exists();

    if ($alreadyCheckedToday) {

        return $this->ticketResult(
            'used',

            'VIP ticket already used today.',

            $ticket,
            $booking,
            $event,
            $user
        );
    }

} else {

    if ($ticket->is_used) {

        return $this->ticketResult(
            'used',

            'Ticket already checked in.',

            $ticket,
            $booking,
            $event,
            $user
        );
    }
}
}

        if (! $consume) {
            return $this->ticketResult(
                'valid',
                'Ticket is valid and ready for check-in.',
                $ticket,
                $booking,
                $event,
                $user
            );
        }

        $ticketType =
    strtolower($booking?->ticket?->ticket_type ?? '');

$isVip =
    str_contains($ticketType, 'vip');

if ($isVip) {

    TicketCheckin::create([
        'booking_ticket_id' => $ticket->id,
        'checkin_date' => today(),
        'checked_in_at' => now(),
    ]);

} else {

    $ticket->update([
        'is_used' => true,
        'used_at' => now(),
    ]);
}

        return $this->ticketResult(
            'success',
            'Access granted.',
            $ticket,
            $booking,
            $event,
            $user
        );
    }
}
    private function ticketResult(
        string $status,
        string $message,
        BookingTicket $ticket,
        mixed $booking,
        mixed $event,
        mixed $user
    ) {
        return view('checkin.result', [
            'status' => $status,
            'message' => $message,
            'ticket' => $ticket,
            'booking' => $booking,
            'event' => $event,
            'user' => $user,
        ]);
    }
}
