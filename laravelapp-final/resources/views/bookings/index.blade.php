@extends('layouts.public')

@section('content')

<section
    style="
        padding-top:120px;
        padding-bottom:100px;
    ">

    <div class="container">

        {{-- HEADER --}}
        <div
            class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4 mb-5">

            <div>

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

                    🎟 Your Ticket Wallet

                </span>

                <h1
                    class="fw-black mb-3"
                    style="
                        font-size: clamp(2.8rem,6vw,5rem);
                        line-height:1;
                        font-weight:900;
                        letter-spacing:-3px;
                    ">

                    My
                    Tickets.

                </h1>

                <p
                    style="
                        color:#9ca3af;
                        font-size:1.1rem;
                        line-height:1.9;
                        max-width:700px;
                    ">

                    Manage your bookings, payments, and event tickets
                    in one place.

                </p>

            </div>

            {{-- CTA --}}
            <div>
                {{-- Hidden: Removed redundant "Explore Events" CTA for focused single-concert UX --}}
            </div>

        </div>

        {{-- EMPTY STATE --}}
        @if($bookings->count() === 0)

            <div
                class="glass-card p-5 text-center">

                <div
                    class="empty-icon mx-auto mb-4">

                    <i
                        class="bi bi-ticket-perforated-fill">
                    </i>

                </div>

                <h2
                    class="fw-bold mb-3">

                    No Tickets Yet

                </h2>

                <p
                    style="
                        color:#9ca3af;
                        max-width:500px;
                        margin:auto;
                        line-height:1.9;
                    "
                    class="mb-4">

                    You haven’t booked any events yet.
                    Explore concerts and reserve your tickets now.

                </p>

                {{-- Hidden: Removed redundant "Browse Events" CTA for focused single-concert UX --}}

            </div>

        @else

            {{-- GRID --}}
            <div class="row g-4">

                @foreach($bookings as $booking)

                    <div class="col-md-6 col-xl-4">

                        <div
                            class="glass-card overflow-hidden h-100 booking-card">

                            {{-- IMAGE --}}
                            @if($booking->touristAttraction->featured_image)

                                <img
                                    src="{{ asset('storage/' . $booking->touristAttraction->featured_image) }}"
                                    class="w-100"
                                    style="
                                        height:220px;
                                        object-fit:cover;
                                    ">

                            @else

                                <img
                                    src="https://images.unsplash.com/photo-1501386761578-eac5c94b800a?q=80&w=1600&auto=format&fit=crop"
                                    class="w-100"
                                    style="
                                        height:220px;
                                        object-fit:cover;
                                    ">

                            @endif

                            {{-- BODY --}}
                            <div class="p-4 p-lg-5">

                                {{-- TOP --}}
                                <div
                                    class="d-flex justify-content-between align-items-start gap-3 mb-4">

                                    <div>

                                        <small
                                            style="
                                                color:#9ca3af;
                                            ">

                                            ORDER ID

                                        </small>

                                        <div
                                            class="fw-bold mt-1">

                                            #{{ $booking->order_id }}

                                        </div>

                                    </div>

                                    {{-- STATUS --}}
                                    <div>

                                        @if($booking->status === 'paid')

                                            <span
                                                class="status-badge bg-success-subtle text-success">

                                                PAID

                                            </span>

                                        @elseif($booking->status === 'pending')

                                            <span
                                                class="status-badge bg-warning-subtle text-warning">

                                                PENDING

                                            </span>

                                        @elseif($booking->status === 'expired')

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

                                {{-- TITLE --}}
                                <h3
                                    class="fw-bold mb-4"
                                    style="
                                        line-height:1.3;
                                    ">

                                    {{ $booking->touristAttraction->name }}

                                </h3>

                                {{-- INFO --}}
                                <div
                                    class="mb-4">

                                    {{-- DATE --}}
                                    <div
                                        class="info-row">

                                        <div
                                            class="info-icon">

                                            <i
                                                class="bi bi-calendar-event-fill">
                                            </i>

                                        </div>

                                        <div>

                                            <small
                                                style="
                                                    color:#9ca3af;
                                                ">

                                                Event Date

                                            </small>

                                            <div
                                                class="fw-semibold">

                                                {{ \Carbon\Carbon::parse($booking->visit_date)->format('d M Y') }}

                                            </div>

                                        </div>

                                    </div>

                                    {{-- QUANTITY --}}
                                    <div
                                        class="info-row">

                                        <div
                                            class="info-icon">

                                            <i
                                                class="bi bi-ticket-perforated-fill">
                                            </i>

                                        </div>

                                        <div>

                                            <small
                                                style="
                                                    color:#9ca3af;
                                                ">

                                                Quantity

                                            </small>

                                            <div
                                                class="fw-semibold">

                                                {{ $booking->quantity }} Ticket

                                            </div>

                                        </div>

                                    </div>

                                </div>

                                {{-- TOTAL --}}
                                <div
                                    class="d-flex justify-content-between align-items-center mb-4">

                                    <div>

                                        <small
                                            style="
                                                color:#9ca3af;
                                            ">

                                            Total Payment

                                        </small>

                                        <h3
                                            class="fw-bold mb-0 mt-1">

                                            Rp {{ number_format($booking->total_price,0,',','.') }}

                                        </h3>

                                    </div>

                                </div>

                                {{-- COUNTDOWN --}}
                                @if($booking->status === 'pending')

                                    <div
                                        class="countdown-box mb-4">

                                        <div
                                            class="d-flex align-items-center gap-3">

                                            <div
                                                class="countdown-icon">

                                                <i
                                                    class="bi bi-clock-fill">
                                                </i>

                                            </div>

                                            <div>

                                                <small
                                                    style="
                                                        color:#9ca3af;
                                                    ">

                                                    PAYMENT DEADLINE

                                                </small>

                                                <div
                                                    class="fw-bold countdown-timer"
                                                    data-expired="{{ $booking->expired_at }}">

                                                    Loading...

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                @endif

                                {{-- ACTIONS --}}
                                <div
                                    class="d-grid gap-3">

                                    <a
                                        href="{{ route('bookings.show', $booking) }}"
                                        class="btn-festigo text-center">

                                        <i
                                            class="bi bi-eye-fill me-2">
                                        </i>

                                        View Booking

                                    </a>

                                    @if($booking->status === 'paid')

                                        <a
                                            href="{{ route('bookings.ticket', $booking) }}"
                                            class="secondary-btn text-center">

                                            <i
                                                class="bi bi-download me-2">
                                            </i>

                                            Download Ticket

                                        </a>

                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

            {{-- PAGINATION --}}
            <div class="mt-5">

                {{ $bookings->links() }}

            </div>

        @endif

    </div>

</section>

<style>

    .booking-card {

        transition:
            0.35s ease;
    }

    .booking-card:hover {

        transform:
            translateY(-6px);
    }

    .status-badge {

        padding:
            10px 18px;

        border-radius:
            999px;

        font-size:
            13px;

        font-weight:
            700;
    }

    .info-row {

        display:
            flex;

        align-items:
            center;

        gap:
            14px;

        margin-bottom:
            20px;
    }

    .info-icon {

        width:
            52px;

        height:
            52px;

        border-radius:
            16px;

        background:
            rgba(255,255,255,0.06);

        display:
            flex;

        align-items:
            center;

        justify-content:
            center;

        flex-shrink:
            0;
    }

    .countdown-box {

        background:
            rgba(109,93,252,0.08);

        border:
            1px solid rgba(109,93,252,0.18);

        border-radius:
            22px;

        padding:
            20px;
    }

    .countdown-icon {

        width:
            54px;

        height:
            54px;

        border-radius:
            16px;

        background:
            linear-gradient(
                135deg,
                #6d5dfc,
                #4f46e5
            );

        display:
            flex;

        align-items:
            center;

        justify-content:
            center;

        color:
            white;

        font-size:
            20px;
    }

    .secondary-btn {

        padding:
            14px 20px;

        border-radius:
            16px;

        border:
            1px solid rgba(255,255,255,0.08);

        background:
            rgba(255,255,255,0.04);

        color:
            white;

        text-decoration:
            none;

        font-weight:
            600;

        transition:
            0.3s ease;
    }

    .secondary-btn:hover {

        background:
            rgba(255,255,255,0.08);

        color:
            white;
    }

    .empty-icon {

        width:
            100px;

        height:
            100px;

        border-radius:
            28px;

        background:
            linear-gradient(
                135deg,
                #6d5dfc,
                #4f46e5
            );

        display:
            flex;

        align-items:
            center;

        justify-content:
            center;

        color:
            white;

        font-size:
            42px;
    }

</style>

<script>

    

    const countdowns =
        document.querySelectorAll(
            '.countdown-timer'
        );

    countdowns.forEach(el => {

        const expiredAt =
            new Date(
                el.dataset.expired
            ).getTime();

        const timer =
            setInterval(function () {

                const now =
                    new Date().getTime();

                const distance =
                    expiredAt - now;

                if (distance < 0) {

                    clearInterval(timer);

                    el.innerHTML =
                        "EXPIRED";

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

                el.innerHTML =
                    minutes + "m " + seconds + "s";

            }, 1000);
    });

</script>

@endsection