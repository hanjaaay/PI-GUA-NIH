@extends('layouts.public')

@section('content')

<section
    class="position-relative overflow-hidden"
    style="
        padding-top: 120px;
        padding-bottom: 100px;
    ">

    {{-- BACKGROUND IMAGE --}}
    <div
        class="position-absolute top-0 start-0 w-100 h-100">

        @if($attraction->featured_image)

            <img
                src="{{ asset('storage/' . $attraction->featured_image) }}"
                class="w-100 h-100"
                style="
                    object-fit: cover;
                    filter: brightness(0.25);
                ">

        @else

            <img
                src="https://images.unsplash.com/photo-1501386761578-eac5c94b800a?q=80&w=1600&auto=format&fit=crop"
                class="w-100 h-100"
                style="
                    object-fit: cover;
                    filter: brightness(0.25);
                ">

        @endif

        {{-- OVERLAY --}}
        <div
            class="position-absolute top-0 start-0 w-100 h-100"
            style="
                background:
                linear-gradient(
                    to bottom,
                    rgba(0,0,0,0.5),
                    rgba(5,5,5,1)
                );
            ">
        </div>

    </div>

    <div class="container position-relative"
         style="z-index:2;">

        <div class="row align-items-end">

            {{-- LEFT --}}
            <div class="col-lg-8">

                {{-- BADGE --}}
                <div
                    class="d-inline-flex align-items-center gap-2 px-4 py-2 rounded-pill mb-4"
                    style="
                        background: rgba(255,255,255,0.08);
                        border: 1px solid rgba(255,255,255,0.08);
                        backdrop-filter: blur(10px);
                    ">

                    <i class="bi bi-music-note-beamed"></i>

                    <span
                        style="
                            color:#d1d5db;
                            font-size:14px;
                        ">

                        Live Concert Event

                    </span>

                </div>

                {{-- TITLE --}}
                <h1
                    class="fw-black mb-4"
                    style="
                        font-size: clamp(3rem,7vw,6rem);
                        line-height: 1;
                        font-weight: 900;
                        letter-spacing: -3px;
                        max-width:900px;
                    ">

                    {{ $attraction->name }}

                </h1>

                {{-- META --}}
                <div
                    class="d-flex flex-wrap align-items-center gap-4 mb-5">

                    {{-- LOCATION --}}
                    <div
                        class="d-flex align-items-center gap-2">

                        <div
                            class="d-flex align-items-center justify-content-center"
                            style="
                                width:48px;
                                height:48px;
                                border-radius:16px;
                                background:rgba(255,255,255,0.08);
                            ">

                            <i class="bi bi-geo-alt-fill"></i>

                        </div>

                        <div>

                            <small
                                style="color:#9ca3af;">

                                Location

                            </small>

                            <div class="fw-semibold">

                                {{ $attraction->city }},
                                {{ $attraction->province }}

                            </div>

                        </div>

                    </div>

                    {{-- DATE --}}
                    <div
                        class="d-flex align-items-center gap-2">

                        <div
                            class="d-flex align-items-center justify-content-center"
                            style="
                                width:48px;
                                height:48px;
                                border-radius:16px;
                                background:rgba(255,255,255,0.08);
                            ">

                            <i class="bi bi-calendar-event-fill"></i>

                        </div>

                        <div>

                            <small
                                style="color:#9ca3af;">

                                Event Date

                            </small>

                            <div class="fw-semibold">

                                @if($attraction->start_date)

                                    {{ \Carbon\Carbon::parse($attraction->start_date)->format('d M Y') }}

                                    @if($attraction->end_date)

                                        -
                                        {{ \Carbon\Carbon::parse($attraction->end_date)->format('d M Y') }}

                                    @endif

                                @else

                                    Coming Soon

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

                {{-- DESCRIPTION --}}
                <p
                    style="
                        color:#b3b3b3;
                        font-size:1.1rem;
                        line-height:2;
                        max-width:800px;
                    ">

                    {{ $attraction->description }}

                </p>

            </div>

            {{-- RIGHT --}}
            <div class="col-lg-4 mt-5 mt-lg-0">

                <div
                    class="glass-card p-4 p-lg-5">

                    {{-- PRICE --}}
                    <div class="mb-4">

                        <small
                            style="color:#9ca3af;">

                            Starting From

                        </small>

                        <h2
                            class="fw-bold mb-0"
                            style="
                                font-size:3rem;
                            ">

                            @if($attraction->tickets->isNotEmpty())

                                Rp {{ number_format($attraction->tickets->min('price'),0,',','.') }}

                            @else

                                Soon

                            @endif

                        </h2>

                    </div>

                    {{-- TICKETS --}}
                    <div class="mb-4">

                        @foreach($attraction->tickets as $ticket)

                            <div
                                class="d-flex justify-content-between align-items-center mb-3 p-3 rounded-4"
                                style="
                                    background:rgba(255,255,255,0.05);
                                    border:1px solid rgba(255,255,255,0.06);
                                ">

                                <div>

                                    <div class="fw-bold">

                                        {{ $ticket->name }}

                                    </div>

                                    <small
                                        style="
                                            color:#9ca3af;
                                        ">

                                        {{ $ticket->type ?? 'General Admission' }}

                                    </small>

                                </div>

                                <div
                                    class="fw-bold">

                                    Rp {{ number_format($ticket->price,0,',','.') }}

                                </div>

                            </div>

                        @endforeach

                    </div>

                    {{-- CTA --}}
                    @auth

                        <a
                            href="{{ route('bookings.create', $attraction) }}"
                            class="btn-festigo w-100 py-3 text-center"
                            style="
                                font-size:18px;
                                border-radius:18px;
                            ">

                            <i class="bi bi-ticket-perforated-fill me-2"></i>

                            Book Tickets

                        </a>

                    @else

                        <a
                            href="{{ route('login') }}"
                            class="btn-festigo w-100 py-3 text-center"
                            style="
                                font-size:18px;
                                border-radius:18px;
                            ">

                            Login To Continue

                        </a>

                    @endauth

                </div>

            </div>

        </div>

    </div>

</section>

{{-- GALLERY --}}
@if(
    $attraction->gallery &&
    is_array($attraction->gallery) &&
    count($attraction->gallery) > 0
)

<section
    class="pb-5">

    <div class="container">

        {{-- HEADER --}}
        <div class="mb-5">

            <h2
                class="fw-bold mb-3"
                style="
                    font-size: clamp(2rem,4vw,3rem);
                ">

                Event Gallery

            </h2>

            <p style="color:#9ca3af;">

                Feel the atmosphere before the event begins.

            </p>

        </div>

        {{-- GRID --}}
        <div class="row g-4">

            @foreach($attraction->gallery as $image)

                <div class="col-md-6 col-xl-4">

                    <div
                        class="overflow-hidden rounded-5">

                        <img
                            src="{{ asset('storage/' . $image) }}"
                            class="w-100"
                            style="
                                height:320px;
                                object-fit:cover;
                                transition:0.5s ease;
                            ">

                    </div>

                </div>

            @endforeach

        </div>

    </div>

</section>

@endif

{{-- RELATED EVENTS --}}
@if($relatedAttractions->isNotEmpty())

<section
    class="pb-5"
    style="
        padding-top:80px;
    ">

    <div class="container">

        {{-- HEADER --}}
        <div class="mb-5">

            <h2
                class="fw-bold mb-3"
                style="
                    font-size: clamp(2rem,4vw,3rem);
                ">

                You Might Also Like

            </h2>

            <p style="color:#9ca3af;">

                More exciting events waiting for you.

            </p>

        </div>

        {{-- GRID --}}
        <div class="row g-4">

            @foreach($relatedAttractions as $related)

                <div class="col-md-6 col-xl-3">

                    <div
                        class="glass-card overflow-hidden h-100">

                        {{-- IMAGE --}}
                        @if($related->featured_image)

                            <img
                                src="{{ asset('storage/' . $related->featured_image) }}"
                                class="w-100"
                                style="
                                    height:240px;
                                    object-fit:cover;
                                ">

                        @else

                            <img
                                src="https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?q=80&w=1200&auto=format&fit=crop"
                                class="w-100"
                                style="
                                    height:240px;
                                    object-fit:cover;
                                ">

                        @endif

                        {{-- BODY --}}
                        <div class="p-4">

                            <small
                                style="
                                    color:#9ca3af;
                                ">

                                {{ $related->city }},
                                {{ $related->province }}

                            </small>

                            <h4
                                class="fw-bold mt-2 mb-3">

                                {{ $related->name }}

                            </h4>

                            <div
                                class="d-flex justify-content-between align-items-center">

                                <div>

                                    <small
                                        style="
                                            color:#9ca3af;
                                        ">

                                        Starting From

                                    </small>

                                    <div class="fw-bold">

                                        @if($related->tickets->isNotEmpty())

                                            Rp {{ number_format($related->tickets->min('price'),0,',','.') }}

                                        @else

                                            Soon

                                        @endif

                                    </div>

                                </div>

                                <a
                                    href="{{ route('attractions.show', $related) }}"
                                    class="btn-festigo">

                                    View

                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

</section>

@endif

@endsection