@extends('layouts.public')

@section('content')

<section
    class="min-vh-100 d-flex align-items-center justify-content-center py-5">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-lg-7">

                @php

                    $config = [

                        'success' => [

                            'title' =>
                                'ACCESS GRANTED',

                            'icon' =>
                                'bi-check-circle-fill',

                            'bg' =>
                                'linear-gradient(135deg,#22c55e,#16a34a)',

                            'glow' =>
                                'rgba(34,197,94,0.25)',
                        ],

                        'valid' => [

                            'title' =>
                                'VALID TICKET',

                            'icon' =>
                                'bi-check-circle',

                            'bg' =>
                                'linear-gradient(135deg,#3b82f6,#2563eb)',

                            'glow' =>
                                'rgba(59,130,246,0.25)',
                        ],

                        'used' => [

                            'title' =>
                                'TICKET ALREADY USED',

                            'icon' =>
                                'bi-x-circle-fill',

                            'bg' =>
                                'linear-gradient(135deg,#ef4444,#dc2626)',

                            'glow' =>
                                'rgba(239,68,68,0.25)',
                        ],

                        'expired' => [

                            'title' =>
                                'BOOKING EXPIRED',

                            'icon' =>
                                'bi-exclamation-triangle-fill',

                            'bg' =>
                                'linear-gradient(135deg,#ef4444,#dc2626)',

                            'glow' =>
                                'rgba(239,68,68,0.25)',
                        ],

                        'unpaid' => [

                            'title' =>
                                'UNPAID BOOKING',

                            'icon' =>
                                'bi-clock-fill',

                            'bg' =>
                                'linear-gradient(135deg,#f59e0b,#d97706)',

                            'glow' =>
                                'rgba(245,158,11,0.25)',
                        ],

                        'invalid' => [

                            'title' =>
                                'INVALID TICKET',

                            'icon' =>
                                'bi-x-octagon-fill',

                            'bg' =>
                                'linear-gradient(135deg,#ef4444,#dc2626)',

                            'glow' =>
                                'rgba(239,68,68,0.25)',
                        ],

                        'error' => [

                            'title' =>
                                'SCANNER ERROR',

                            'icon' =>
                                'bi-bug-fill',

                            'bg' =>
                                'linear-gradient(135deg,#ef4444,#dc2626)',

                            'glow' =>
                                'rgba(239,68,68,0.25)',
                        ],

                    ];

                    $current =
                        $config[$status]
                        ?? $config['invalid'];

                @endphp

                {{-- CARD --}}
                <div
                    class="scanner-card position-relative overflow-hidden">

                    {{-- GLOW --}}
                    <div
                        class="scanner-glow"
                        style="
                            background:
                            {{ $current['glow'] }};
                        ">
                    </div>

                    {{-- BODY --}}
                    <div
                        class="p-4 p-lg-5 text-center position-relative"
                        style="z-index:2;">

                        {{-- ICON --}}
                        <div
                            class="scanner-icon mx-auto mb-4"
                            style="
                                background:
                                {{ $current['bg'] }};
                            ">

                            <i
                                class="bi {{ $current['icon'] }}">
                            </i>

                        </div>

                        {{-- TITLE --}}
                        <h1
                            class="fw-black mb-3"
                            style="
                                font-size:
                                clamp(2.2rem,5vw,4rem);

                                letter-spacing:
                                -2px;
                            ">

                            {{ $current['title'] }}

                        </h1>

                        {{-- MESSAGE --}}
                        <p
                            class="scanner-message mb-5">

                            {{ $message }}

                        </p>

                        {{-- DETAILS --}}
                        @isset($booking)

                            <div
                                class="scanner-details text-start">

                                {{-- EVENT --}}
                                <div class="detail-row">

                                    <span>
                                        Event
                                    </span>

                                    <strong>

                                        {{ $event?->name ?? '-' }}

                                    </strong>

                                </div>

                                {{-- ATTENDEE --}}
                                <div class="detail-row">

                                    <span>
                                        Attendee
                                    </span>

                                    <strong>

                                        {{ $user?->name ?? '-' }}

                                    </strong>

                                </div>

                                {{-- ORDER --}}
                                <div class="detail-row">

                                    <span>
                                        Order ID
                                    </span>

                                    <strong>

                                        {{ $booking?->order_id ?? '-' }}

                                    </strong>

                                </div>

                                {{-- TICKET --}}
                                <div class="detail-row">

                                    <span>
                                        Ticket Code
                                    </span>

                                    <strong>

                                        {{ $ticket?->ticket_code ?? '-' }}

                                    </strong>

                                </div>

                                {{-- USED --}}
                                @if(
                                    $ticket &&
                                    $ticket->used_at
                                )

                                    <div class="detail-row">

                                        <span>
                                            Checked In At
                                        </span>

                                        <strong>

                                            {{ \Carbon\Carbon::parse($ticket->used_at)->format('d M Y H:i:s') }}

                                        </strong>

                                    </div>

                                @endif

                            </div>

                        @endisset

                        {{-- BUTTON --}}
                        <div class="mt-5">

                            <a
                                href="{{ url()->previous() }}"
                                class="btn-festigo px-5 py-3">

                                <i
                                    class="bi bi-arrow-left me-2">
                                </i>

                                Back

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<style>

    .scanner-card {

        background:
            rgba(255,255,255,0.05);

        border:
            1px solid rgba(255,255,255,0.06);

        backdrop-filter:
            blur(24px);

        border-radius:
            36px;
    }

    .scanner-glow {

        position:
            absolute;

        width:
            350px;

        height:
            350px;

        border-radius:
            999px;

        filter:
            blur(100px);

        top:
            -100px;

        right:
            -100px;

        opacity:
            0.9;
    }

    .scanner-icon {

        width:
            140px;

        height:
            140px;

        border-radius:
            40px;

        display:
            flex;

        align-items:
            center;

        justify-content:
            center;

        font-size:
            64px;

        color:
            white;

        box-shadow:
            0 20px 60px rgba(0,0,0,0.35);
    }

    .scanner-message {

        color:
            #d1d5db;

        font-size:
            1.1rem;

        line-height:
            1.9;

        max-width:
            700px;

        margin:
            auto;
    }

    .scanner-details {

        background:
            rgba(255,255,255,0.04);

        border:
            1px solid rgba(255,255,255,0.06);

        border-radius:
            24px;

        padding:
            30px;
    }

    .detail-row {

        display:
            flex;

        justify-content:
            space-between;

        align-items:
            center;

        gap:
            20px;

        padding:
            18px 0;

        border-bottom:
            1px solid rgba(255,255,255,0.06);

        color:
            #d1d5db;
    }

    .detail-row:last-child {

        border-bottom:
            none;
    }

    .detail-row span {

        color:
            #9ca3af;
    }

    @media(max-width:768px) {

        .scanner-card {

            border-radius:
                28px;
        }

        .scanner-icon {

            width:
                110px;

            height:
                110px;

            border-radius:
                32px;

            font-size:
                48px;
        }

        .detail-row {

            flex-direction:
                column;

            align-items:
                flex-start;
        }
    }

</style>

@endsection
