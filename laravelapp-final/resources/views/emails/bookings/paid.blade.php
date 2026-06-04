@component('mail::message')
# Booking Confirmed

Dear {{ $booking->user?->name }},

Your booking has been confirmed and paid successfully.

**Booking Details:**
- Booking Code: {{ $booking->booking_code }}
- Attraction: {{ $booking->ticket?->attraction?->name }}
{{ optional($booking->visit_date)->format('d M Y') }}
- Number of Tickets: {{ $booking->quantity }}
- Total Amount: {{ number_format($booking->total_price) }} IDR

@component('mail::button', ['url' => route('bookings.ticket', $booking)])
Download E-Ticket
@endcomponent

**Important Information:**
- Please show your e-ticket at the entrance
- Valid only for the selected date
- Non-transferable

If you have any questions, please don't hesitate to contact us.

Thanks,<br>
{{ config('app.name') }}
@endcomponent 