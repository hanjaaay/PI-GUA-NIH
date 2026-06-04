@extends('layouts.public')

@section('content')

{{-- HERO --}}
<section
    class="position-relative overflow-hidden"
    style="
        padding-top: 140px;
        padding-bottom: 96px;
    ">

    <div class="container position-relative"
         style="z-index:2;">

        <div class="row align-items-center">

            {{-- LEFT --}}
            <div class="col-lg-7 mb-5 mb-lg-0">

                <div data-aos="fade-up">

                    <span
                        class="px-4 py-2 rounded-pill"
                        style="
                            background: rgba(255,255,255,0.06);
                            border: 1px solid rgba(255,255,255,0.08);
                            color: #b3b3b3;
                            font-size: 14px;
                            display:inline-block;
                            margin-bottom:25px;
                        ">

                        🎵 Indonesia’s Modern Event Ticketing Platform

                    </span>

                    <h1
                        class="fw-black mb-4"
                        style="
                            font-size: clamp(3rem,7vw,6rem);
                            line-height: 1;
                            font-weight: 900;
                            letter-spacing: -3px;
                        ">

                        Experience
                        The Best
                        Concerts &
                        Events.

                    </h1>

                    <p
                        style="
                            color:#9ca3af;
                            font-size:1.1rem;
                            line-height:1.9;
                            max-width:650px;
                        "
                        class="mb-0">

                        Discover concerts, festivals, gigs, and unforgettable experiences.
                        Secure your tickets instantly with real-time booking and seamless payments.

                    </p>

                </div>

            </div>

            {{-- RIGHT --}}
            <div class="col-lg-5">

                <div
                    class="position-relative"
                    data-aos="fade-left">

                    <img
                        src="https://images.unsplash.com/photo-1501386761578-eac5c94b800a?q=80&w=1200&auto=format&fit=crop"
                        class="img-fluid rounded-5 shadow-lg"
                        style="
                            object-fit:cover;
                            min-height:500px;
                        ">

                    {{-- FLOATING CARD --}}
                    <div
                        class="glass-card p-4 position-absolute"
                        style="
                            bottom:30px;
                            left:-30px;
                            width:240px;
                        ">

                        <div
                            class="d-flex align-items-center gap-3">

                            <div
                                style="
                                    width:50px;
                                    height:50px;
                                    border-radius:16px;
                                    background:linear-gradient(135deg,#6d5dfc,#7c3aed);
                                "
                                class="d-flex align-items-center justify-content-center">

                                <i class="bi bi-ticket-perforated-fill text-white"></i>

                            </div>

                            <div>

                                <h5 class="mb-1 fw-bold">
                                    Fast Booking
                                </h5>

                                <small style="color:#9ca3af;">
                                    Real-time ticket reservation
                                </small>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

{{-- EVENTS --}}
<section
    class="pb-5">

    <div class="container">

        {{-- HEADER --}}
        <div
            class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center mb-5">

            <div>

                <h2
                    class="fw-bold mb-3"
                    style="
                        font-size: clamp(2rem,4vw,3rem);
                    ">

                    Trending Events

                </h2>

                <p style="color:#9ca3af;">

                    Discover the hottest concerts and experiences happening right now.

                </p>

            </div>

        </div>

        {{-- EMPTY --}}
        @if($attractions->isEmpty())

            <div
                class="glass-card p-5 text-center">

                <h3 class="fw-bold mb-3">
                    No Events Found
                </h3>

                <p style="color:#9ca3af;">
                    Try searching with different keywords.
                </p>

            </div>

        @else

            {{-- GRID --}}
            <div class="row g-4">

                @foreach($attractions as $attraction)

                    <div class="col-md-6 col-xl-4">

                        <div
                            class="glass-card overflow-hidden h-100"
                            data-aos="fade-up">

                            {{-- IMAGE --}}
                            <div
                                class="position-relative overflow-hidden">

                                @if($attraction->featured_image)

                                    <img
                                        src="{{ asset('storage/' . $attraction->featured_image) }}"
                                        alt="{{ $attraction->name }}"
                                        class="w-100"
                                        style="
                                            height:260px;
                                            object-fit:cover;
                                            transition:0.5s ease;
                                        ">

                                @else

                                    <img
                                        src="https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?q=80&w=1200&auto=format&fit=crop"
                                        class="w-100"
                                        style="
                                            height:260px;
                                            object-fit:cover;
                                        ">

                                @endif

                                {{-- OVERLAY --}}
                                <div
                                    class="position-absolute bottom-0 start-0 w-100 p-4"
                                    style="
                                        background:
                                        linear-gradient(
                                            to top,
                                            rgba(0,0,0,0.85),
                                            transparent
                                        );
                                    ">

                                    <div
                                        class="d-flex align-items-center gap-2 mb-2">

                                        <i class="bi bi-geo-alt-fill"
                                           style="color:#c084fc;"></i>

                                        <small style="color:#d1d5db;">

                                            {{ $attraction->city }},
                                            {{ $attraction->province }}

                                        </small>

                                    </div>

                                    <h3
                                        class="fw-bold mb-0"
                                        style="
                                            font-size:1.5rem;
                                        ">

                                        {{ $attraction->name }}

                                    </h3>

                                </div>

                            </div>

                            {{-- BODY --}}
                            <div class="p-4">

                                <p
                                    style="
                                        color:#9ca3af;
                                        line-height:1.8;
                                        min-height:80px;
                                    ">

                                    {{ Str::limit($attraction->description, 120) }}

                                </p>

                                {{-- FACILITIES --}}
                                @if(
                                    $attraction->facilities &&
                                    is_array($attraction->facilities)
                                )

                                    <div
                                        class="d-flex flex-wrap gap-2 mb-4">

                                        @foreach(array_slice($attraction->facilities,0,3) as $facility)

                                            <span
                                                style="
                                                    background:rgba(255,255,255,0.06);
                                                    border:1px solid rgba(255,255,255,0.08);
                                                    padding:8px 14px;
                                                    border-radius:999px;
                                                    font-size:12px;
                                                    color:#d1d5db;
                                                ">

                                                {{ $facility }}

                                            </span>

                                        @endforeach

                                    </div>

                                @endif

                                {{-- FOOTER --}}
                                <div
                                    class="d-flex justify-content-between align-items-center mt-auto">

                                    <div>

                                        <small
                                            style="color:#9ca3af;">

                                            Starting From

                                        </small>

                                        <h4
                                            class="fw-bold mb-0">

                                            @if($attraction->tickets->isNotEmpty())

                                                Rp {{ number_format($attraction->tickets->min('price'),0,',','.') }}

                                            @else

                                                Soon

                                            @endif

                                        </h4>

                                    </div>

                                    <a
                                        href="{{ route('attractions.show', $attraction) }}"
                                        class="btn-festigo">

                                        View Event

                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

            {{-- PAGINATION --}}
            @if($attractions instanceof \Illuminate\Pagination\LengthAwarePaginator)

                <div class="mt-5">

                    {{ $attractions->links() }}

                </div>

            @endif

        @endif

    </div>

</section>

@endsection
