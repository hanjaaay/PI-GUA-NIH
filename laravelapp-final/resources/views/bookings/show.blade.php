@extends('layouts.public')

@section('content')

<section
    style="
        padding-top:120px;
        padding-bottom:100px;
    ">

    <div class="container">

        <div class="row g-5">

            {{-- LEFT --}}
            <div class="col-lg-7">

                {{-- HEADER --}}
                <div class="mb-5">

                    <span
                        class="px-4 py-2 rounded-pill"
                        style="
                            background:rgba(255,255,255,0.06);
                            border:1px solid rgba(255,255,255,0.08);
                            color:#b3b3b3;
                            font-size:14px;
                            display:inline-block;
                            margin-bottom:20px;
                        ">

                        🎫 Booking Confirmation

                    </span>

                    <h1
                        class="fw-black mb-3"
                        style="
                            font-size: clamp(2.8rem,6vw,5rem);
                            line-height:1;
                            font-weight:900;
                            letter-spacing:-3px;
                        ">

                        Complete
                        Your Payment.

                    </h1>

                    <p
                        style="
                            color:#9ca3af;
                            font-size:1.1rem;
                            line-height:1.9;
                            max-width:700px;
                        ">

                        Your tickets are temporarily reserved.
                        Complete payment before the timer ends.

                    </p>

                </div>

                {{-- PAYMENT STATUS --}}
                <div
                    class="glass-card p-4 p-lg-5 mb-4">

                    <div
                        class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

                        <div>

                            <small
                                style="
                                    color:#9ca3af;
                                ">

                                PAYMENT STATUS

                            </small>

                            <h2
                                class="fw-bold mb-0 mt-2">

                                @if($booking->status == 'paid')

                                    Payment Successful

                                @elseif($booking->status == 'pending')

                                    Awaiting Payment

                                @elseif($booking->status == 'expired')

                                    Booking Expired

                                @else

                                    Booking Status

                                @endif

                            </h2>

                        </div>

                        {{-- STATUS BADGE --}}
                        <div>

                            @if($booking->status == 'paid')

                                <span
                                    class="status-badge bg-success-subtle text-success">

                                    PAID

                                </span>

                            @elseif($booking->status == 'pending')

                                <span
                                    class="status-badge bg-warning-subtle text-warning">

                                    PENDING

                                </span>

                            @elseif($booking->status == 'expired')

                                <span
                                    class="status-badge bg-danger-subtle text-danger">

                                    EXPIRED

                                </span>

                            @else

                                <span
                                    class="status-badge bg-secondary-subtle text-secondary">

                                    {{ strtoupper($booking->status) }}

                                </span>

                            @endif

                        </div>

                    </div>

                    {{-- COUNTDOWN --}}
                    @if($booking->isPending())

                        <div
                            class="countdown-box mb-4">

                            <div
                                class="d-flex align-items-center gap-3">

                                <div
                                    class="countdown-icon">

                                    <i class="bi bi-clock-fill"></i>

                                </div>

                                <div>

                                    <small
                                        style="
                                            color:#9ca3af;
                                        ">

                                        PAYMENT DEADLINE

                                    </small>

                                    <h3
                                        class="fw-bold mb-0 mt-1"
                                        id="countdown">

                                        Loading...

                                    </h3>

                                </div>

                            </div>

                        </div>

                    @endif

                    {{-- BOOKING INFO --}}
                    <div
                        class="booking-info">

                        {{-- ORDER --}}
                        <div
                            class="booking-row">

                            <span>
                                Order ID
                            </span>

                            <strong>
                                {{ $booking->order_id }}
                            </strong>

                        </div>

                        {{-- EVENT --}}
                        <div
                            class="booking-row">

                            <span>
                                Event
                            </span>

                            <strong>
                                {{ $booking->touristAttraction->name }}
                            </strong>

                        </div>

                        {{-- DATE --}}
                        <div
                            class="booking-row">

                            <span>
                                Visit Date
                            </span>

                            <strong>

                                {{ \Carbon\Carbon::parse($booking->visit_date)->format('d F Y') }}

                            </strong>

                        </div>

                        {{-- QTY --}}
                        <div
                            class="booking-row">

                            <span>
                                Quantity
                            </span>

                            <strong>
                                {{ $booking->quantity }} Ticket
                            </strong>

                        </div>

                        {{-- TOTAL --}}
                        <div
                            class="booking-row border-0 pt-4">

                            <span
                                style="
                                    font-size:1.1rem;
                                ">

                                Total Payment

                            </span>

                            <strong
                                style="
                                    font-size:1.8rem;
                                ">

                                Rp {{ number_format($booking->total_price,0,',','.') }}

                            </strong>

                        </div>

                    </div>

                </div>

                {{-- ALERT --}}
                @if($booking->isExpired())

                    <div
                        class="glass-card p-4"
                        style="
                            border:1px solid rgba(239,68,68,0.2);
                            background:rgba(239,68,68,0.08);
                        ">

                        <div
                            class="d-flex align-items-start gap-3">

                            <div
                                class="alert-icon bg-danger">

                                <i
                                    class="bi bi-exclamation-triangle-fill">
                                </i>

                            </div>

                            <div>

                                <h5
                                    class="fw-bold mb-2">

                                    Booking Expired

                                </h5>

                                <p
                                    class="mb-0"
                                    style="
                                        color:#d1d5db;
                                        line-height:1.8;
                                    ">

                                    Your reserved tickets have been released
                                    back to stock because payment was not completed
                                    before the deadline.

                                </p>

                            </div>

                        </div>

                    </div>

                @endif



                {{-- PAID --}}
                @if($booking->status === 'paid')

                    <div
                        class="glass-card p-4"
                        style="
                            border:1px solid rgba(34,197,94,0.2);
                            background:rgba(34,197,94,0.08);
                        ">

                        <div
                            class="d-flex align-items-start gap-3">

                            <div
                                class="alert-icon bg-success">

                                <i
                                    class="bi bi-check-circle-fill">
                                </i>

                            </div>

                            <div>

                                <h5
                                    class="fw-bold mb-2">

                                    Payment Successful

                                </h5>

                                <p
                                    class="mb-4"
                                    style="
                                        color:#d1d5db;
                                        line-height:1.8;
                                    ">

                                    Your booking has been confirmed successfully.
                                    Download your ticket below.

                                </p>

                                <a
                                    href="{{ route('bookings.ticket', $booking) }}"
                                    class="btn-festigo">

                                    <i
                                        class="bi bi-download me-2">
                                    </i>

                                    Download Ticket

                                </a>

                            </div>

                        </div>

                    </div>

                @endif

            </div>

            {{-- RIGHT --}}
            <div class="col-lg-5">

                <div
                    class="glass-card p-4 p-lg-5 sticky-top"
                    style="
                        top:120px;
                    ">

                    {{-- ICON --}}
                    <div
                        class="payment-icon mb-4">

                        <i
                            class="bi bi-shield-lock-fill">
                        </i>

                    </div>

                    <h2
                        class="fw-bold mb-3">

                        Secure Payment

                    </h2>

                    <p
                        style="
                            color:#9ca3af;
                            line-height:1.9;
                        "
                        class="mb-5">

                        All transactions are securely processed through
                        Midtrans payment gateway.

                    </p>

                    {{-- FEATURES --}}
                    <div class="mb-5">

                        <div
                            class="feature-row">

                            <i
                                class="bi bi-check-circle-fill text-success">
                            </i>

                            <span>
                                Instant Booking Confirmation
                            </span>

                        </div>

                        <div
                            class="feature-row">

                            <i
                                class="bi bi-check-circle-fill text-success">
                            </i>

                            <span>
                                Secure Payment Gateway
                            </span>

                        </div>

                        <div
                            class="feature-row">

                            <i
                                class="bi bi-check-circle-fill text-success">
                            </i>

                            <span>
                                QR Ticket Access
                            </span>

                        </div>

                    </div>

                    {{-- BUTTON --}}
                    @if($booking->isPending())

                        @if(request('payment_processing'))
                            <div class="alert mb-3"
                                 style="background:rgba(109,93,252,0.12);
                                        border:1px solid rgba(109,93,252,0.3);
                                        border-radius:12px;
                                        color:#d1d5db;
                                        font-size:14px;
                                        padding:12px 16px;">
                                <i class="bi bi-arrow-repeat me-2"></i>
                                Payment received. Confirming your booking — please wait a moment and
                                <a href="{{ route('bookings.show', $booking) }}"
                                   style="color:#a78bfa;">refresh this page</a>.
                            </div>

                            @if($booking->isPending())
                            <script>
                                // Auto-refresh every 5 seconds while status is still pending
                                setTimeout(function() {
                                    window.location.reload();
                                }, 5000);
                            </script>
                            @endif
                        @endif

                        @if($snapToken)

                            <button
                                id="pay-button"
                                class="btn-festigo w-100 py-3"
                                style="
                                    font-size:18px;
                                    border-radius:20px;
                                ">

                                <i
                                    class="bi bi-credit-card-fill me-2">
                                </i>

                                Pay Now

                            </button>

                        @else

                            <div
                                class="alert alert-danger">

                                Failed to generate payment token.

                            </div>

                        @endif

                    @endif

                </div>

            </div>

        </div>

    </div>

</section>

<style>

    .status-badge {

        padding:
            12px 20px;

        border-radius:
            999px;

        font-size:
            14px;

        font-weight:
            700;
    }

    .countdown-box {

        padding:
            24px;

        border-radius:
            24px;

        background:
            rgba(109,93,252,0.08);

        border:
            1px solid rgba(109,93,252,0.2);
    }

    .countdown-icon {

        width:
            60px;

        height:
            60px;

        border-radius:
            18px;

        display:
            flex;

        align-items:
            center;

        justify-content:
            center;

        background:
            linear-gradient(
                135deg,
                #6d5dfc,
                #4f46e5
            );

        font-size:
            22px;
    }

    .booking-row {

        display:
            flex;

        justify-content:
            space-between;

        align-items:
            center;

        padding:
            20px 0;

        border-bottom:
            1px solid rgba(255,255,255,0.06);

        gap:
            20px;
    }

    .booking-row span {

        color:
            #9ca3af;
    }

    .alert-icon {

        width:
            58px;

        height:
            58px;

        border-radius:
            18px;

        display:
            flex;

        align-items:
            center;

        justify-content:
            center;

        color:
            white;

        font-size:
            22px;

        flex-shrink:
            0;
    }

    .payment-icon {

        width:
            90px;

        height:
            90px;

        border-radius:
            28px;

        display:
            flex;

        align-items:
            center;

        justify-content:
            center;

        background:
            linear-gradient(
                135deg,
                #6d5dfc,
                #4f46e5
            );

        font-size:
            36px;

        color:
            white;
    }

    .feature-row {

        display:
            flex;

        align-items:
            center;

        gap:
            14px;

        margin-bottom:
            18px;

        color:
            #d1d5db;
    }

</style>

@if($booking->isPending())

<script>

    

    const expiredAt =

    new Date(

        "{{ \Carbon\Carbon::parse($booking->expired_at)->toIso8601String() }}"

    ).getTime();

    const countdown =
        document.getElementById('countdown');

    const timer =
    setInterval(function () {

        const now =
            new Date().getTime();

        const distance =
            expiredAt - now;

        if (distance < 0) {

            clearInterval(timer);

            countdown.innerHTML = "EXPIRED";

            // Attempt to close Midtrans popup if it is currently open.
            // snap.hide() is the official Midtrans Snap API method.
            if (typeof snap !== 'undefined') {
                try { snap.hide(); } catch (e) {}
            }

            setTimeout(() => {
                window.location.reload();
            }, 2000);

            return;
        }

        const minutes =
            Math.floor(
                (distance % (1000 * 60 * 60))
                / (1000 * 60)
            );

        const seconds =
            Math.floor(
                (distance % (1000 * 60))
                / 1000
            );

        countdown.innerHTML =
            minutes + "m " + seconds + "s";

    }, 1000);

</script>

@endif

@if($snapToken)

<script
    src="{{ $midtransSnapUrl }}"
    data-client-key="{{ config('midtrans.client_key') }}">
</script>

<script>

document.getElementById('pay-button').onclick = function () {

    snap.pay('{{ $snapToken }}', {

        onSuccess: function(result) {

            // Midtrans confirmed payment on frontend.
            // Webhook may not have arrived yet — poll backend status
            // instead of reloading immediately.

            const statusUrl = '{{ route('bookings.status', $booking) }}';
            const bookingUrl = '{{ route('bookings.show', $booking) }}';

            let attempts = 0;
            const maxAttempts = 20; // 20 * 2s = up to 40 seconds

            const poll = setInterval(function () {

                attempts++;

                fetch(statusUrl, {
                    headers: { 'Accept': 'application/json' }
                })
                .then(res => res.json())
                .then(data => {

                    if (data.is_paid) {
                        clearInterval(poll);
                        window.location.href = bookingUrl;
                        return;
                    }

                    if (data.is_expired) {
                        clearInterval(poll);
                        // Booking expired before webhook arrived — redirect anyway
                        window.location.href = bookingUrl;
                        return;
                    }

                    if (attempts >= maxAttempts) {
                        clearInterval(poll);
                        // Webhook took too long — redirect to booking page.
                        // The page will show pending with a note to refresh.
                        window.location.href = bookingUrl + '?payment_processing=1';
                    }
                })
                .catch(() => {
                    // Network error — just redirect
                    clearInterval(poll);
                    window.location.href = bookingUrl;
                });

            }, 2000); // poll every 2 seconds
        },

        onPending: function(result) {
            // Bank transfer or other async method — redirect to booking page
            window.location.href = '{{ route('bookings.show', $booking) }}';
        },

        onError: function(result) {
            console.error('Midtrans payment error:', result);
        },

        onClose: function() {
            // User closed popup without paying — do nothing
        }
    });
};

</script>

@endif

@endsection
