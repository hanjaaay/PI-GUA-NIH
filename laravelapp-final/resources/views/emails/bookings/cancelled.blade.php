@component('mail::message')
# Booking Cancelled

Dear {{ $booking->user->name }},

Your booking has been cancelled.

**Booking Details:**
- Booking Code: {{ $booking->booking_code }}
- Attraction: {{ $booking->ticket->attraction->name }}
- Visit Date: {{ $booking->visit_date->format('d M Y') }}
- Number of Tickets: {{ $booking->quantity }}
- Total Amount: {{ number_format($booking->total_price) }} IDR

@if($booking->status === 'cancelled' && $booking->visit_date->isAfter(now()->addHours(24)))
**Refund Information:**
Your payment will be refunded to your original payment method within 3-5 business days.
@endif

If you have any questions, please don't hesitate to contact us.

Thanks,<br>
{{ config('app.name') }}
@endcomponent 