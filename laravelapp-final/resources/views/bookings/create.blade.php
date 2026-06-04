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

                {{-- TITLE --}}
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

                        🎟 Secure Checkout

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
                        Your Booking.

                    </h1>

                    <p
                        style="
                            color:#9ca3af;
                            font-size:1.1rem;
                            line-height:1.9;
                            max-width:700px;
                        ">

                        Your selected tickets will be reserved temporarily
                        while you complete payment.

                    </p>

                </div>

                {{-- FORM CARD --}}
                <div
                    class="glass-card p-4 p-lg-5">

                    <form
                        method="POST"
                        action="{{ route('bookings.store', $attraction->id) }}">

                        @csrf

                        {{-- TICKET --}}
                        <div class="mb-5">

                            <label
                                class="fw-bold mb-4"
                                style="
                                    font-size:1.1rem;
                                ">

                                Select Ticket

                            </label>

                            <div class="row g-3">

                                @foreach($tickets as $ticket)

                                    <div class="col-12">

                                        <label
                                            class="ticket-option w-100">

                                            <input
                                                type="radio"
                                                name="ticket_id"
                                                value="{{ $ticket->id }}"
                                                data-price="{{ $ticket->price }}"
                                                hidden
                                                required>

                                            <div
                                                class="ticket-card p-4 rounded-4">

                                                <div
                                                    class="d-flex justify-content-between align-items-center">

                                                    <div>

                                                        <h4
                                                            class="fw-bold mb-2">

                                                            {{ $ticket->name }}

                                                        </h4>

                                                        <div
                                                            class="d-flex flex-wrap gap-2">

                                                            <span
                                                                class="ticket-badge">

                                                                {{ $ticket->type ?? 'General Admission' }}

                                                            </span>

                                                            <span
                                                                class="ticket-badge">

                                                                Stock:
                                                                {{ $ticket->available_quantity }}

                                                            </span>

                                                        </div>

                                                    </div>

                                                    <div
                                                        class="text-end">

                                                        <small
                                                            style="
                                                                color:#9ca3af;
                                                            ">

                                                            Price

                                                        </small>

                                                        <h3
                                                            class="fw-bold mb-0">

                                                            Rp {{ number_format($ticket->price,0,',','.') }}

                                                        </h3>

                                                    </div>

                                                </div>

                                            </div>

                                        </label>

                                    </div>

                                @endforeach

                            </div>

                        </div>

                        {{-- QUANTITY --}}
                        <div class="mb-5">

                            <label
                                class="fw-bold mb-4"
                                style="
                                    font-size:1.1rem;
                                ">

                                Ticket Quantity

                            </label>

                            <div
                                class="quantity-wrapper">

                                <button
                                    type="button"
                                    class="quantity-btn"
                                    onclick="decreaseQty()">

                                    -

                                </button>

                                <input
                                    type="number"
                                    id="quantity"
                                    name="quantity"
                                    value="1"
                                    min="1"
                                    class="quantity-input"
                                    readonly>

                                <button
                                    type="button"
                                    class="quantity-btn"
                                    onclick="increaseQty()">

                                    +

                                </button>

                            </div>

                        </div>

                        {{-- INFO --}}
                        <div
                            class="glass-card p-4 mb-5"
                            style="
                                background:rgba(109,93,252,0.08);
                                border:1px solid rgba(109,93,252,0.15);
                            ">

                            <div
                                class="d-flex align-items-start gap-3">

                                <div
                                    class="d-flex align-items-center justify-content-center"
                                    style="
                                        width:52px;
                                        height:52px;
                                        border-radius:16px;
                                        background:linear-gradient(135deg,#6d5dfc,#4f46e5);
                                        flex-shrink:0;
                                    ">

                                    <i
                                        class="bi bi-shield-lock-fill text-white">
                                    </i>

                                </div>

                                <div>

                                    <h5
                                        class="fw-bold mb-2">

                                        Secure Reservation

                                    </h5>

                                    <p
                                        class="mb-0"
                                        style="
                                            color:#b3b3b3;
                                            line-height:1.8;
                                        ">

                                        Your selected tickets will be reserved
                                        for a limited time during payment.

                                    </p>

                                </div>

                            </div>

                        </div>

                        {{-- SUBMIT --}}
                        <button
                            type="submit"
                            class="btn-festigo w-100 py-3"
                            style="
                                font-size:18px;
                                border-radius:20px;
                            ">

                            <i
                                class="bi bi-credit-card-fill me-2">
                            </i>

                            Continue To Payment

                        </button>

                    </form>

                </div>

            </div>

            {{-- RIGHT --}}
            <div class="col-lg-5">

                <div
                    class="glass-card overflow-hidden sticky-top"
                    style="
                        top:120px;
                    ">

                    {{-- IMAGE --}}
                    @if($attraction->featured_image)

                        <img
                            src="{{ asset('storage/' . $attraction->featured_image) }}"
                            class="w-100"
                            style="
                                height:280px;
                                object-fit:cover;
                            ">

                    @else

                        <img
                            src="https://images.unsplash.com/photo-1501386761578-eac5c94b800a?q=80&w=1600&auto=format&fit=crop"
                            class="w-100"
                            style="
                                height:280px;
                                object-fit:cover;
                            ">

                    @endif

                    {{-- BODY --}}
                    <div class="p-4 p-lg-5">

                        <small
                            style="
                                color:#9ca3af;
                            ">

                            EVENT

                        </small>

                        <h2
                            class="fw-bold mb-4">

                            {{ $attraction->name }}

                        </h2>

                        {{-- META --}}
                        <div class="mb-4">

                            {{-- DATE --}}
                            <div
                                class="d-flex align-items-center gap-3 mb-3">

                                <div
                                    class="d-flex align-items-center justify-content-center"
                                    style="
                                        width:48px;
                                        height:48px;
                                        border-radius:16px;
                                        background:rgba(255,255,255,0.06);
                                    ">

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

                                        {{ \Carbon\Carbon::parse($attraction->start_date)->format('d M Y') }}

                                        -

                                        {{ \Carbon\Carbon::parse($attraction->end_date)->format('d M Y') }}

                                    </div>

                                </div>

                            </div>

                            {{-- LOCATION --}}
                            <div
                                class="d-flex align-items-center gap-3">

                                <div
                                    class="d-flex align-items-center justify-content-center"
                                    style="
                                        width:48px;
                                        height:48px;
                                        border-radius:16px;
                                        background:rgba(255,255,255,0.06);
                                    ">

                                    <i
                                        class="bi bi-geo-alt-fill">
                                    </i>

                                </div>

                                <div>

                                    <small
                                        style="
                                            color:#9ca3af;
                                        ">

                                        Location

                                    </small>

                                    <div
                                        class="fw-semibold">

                                        {{ $attraction->city }},
                                        {{ $attraction->province }}

                                    </div>

                                </div>

                            </div>

                        </div>

                        <hr
                            style="
                                border-color:
                                rgba(255,255,255,0.08);
                            ">

                        {{-- TOTAL --}}
                        <div
                            class="d-flex justify-content-between align-items-center mt-4">

                            <div>

                                <small
                                    style="
                                        color:#9ca3af;
                                    ">

                                    Estimated Total

                                </small>

                                <h2
                                    class="fw-bold mb-0"
                                    id="total-price">

                                    Rp 0

                                </h2>

                            </div>

                            <div
                                class="text-end">

                                <small
                                    style="
                                        color:#9ca3af;
                                    ">

                                    Quantity

                                </small>

                                <div
                                    class="fw-bold"
                                    id="qty-display">

                                    1 Ticket

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<style>

    .ticket-card {

        background:
            rgba(255,255,255,0.04);

        border:
            1px solid rgba(255,255,255,0.06);

        transition:
            0.3s ease;

        cursor:
            pointer;
    }

    .ticket-card:hover {

        border-color:
            rgba(109,93,252,0.5);

        transform:
            translateY(-2px);
    }

    .ticket-option input:checked + .ticket-card {

        border:
            2px solid #6d5dfc;

        background:
            rgba(109,93,252,0.12);

        box-shadow:
            0 0 30px rgba(109,93,252,0.2);
    }

    .ticket-badge {

        background:
            rgba(255,255,255,0.06);

        border:
            1px solid rgba(255,255,255,0.08);

        padding:
            6px 12px;

        border-radius:
            999px;

        font-size:
            12px;

        color:
            #d1d5db;
    }

    .quantity-wrapper {

        display:
            flex;

        align-items:
            center;

        width:
            fit-content;

        border:
            1px solid rgba(255,255,255,0.08);

        border-radius:
            20px;

        overflow:
            hidden;

        background:
            rgba(255,255,255,0.04);
    }

    .quantity-btn {

        width:
            60px;

        height:
            60px;

        border:
            none;

        background:
            transparent;

        color:
            white;

        font-size:
            24px;

        transition:
            0.3s ease;
    }

    .quantity-btn:hover {

        background:
            rgba(255,255,255,0.08);
    }

    .quantity-input {

        width:
            80px;

        height:
            60px;

        border:
            none;

        outline:
            none;

        background:
            transparent;

        text-align:
            center;

        color:
            white;

        font-size:
            20px;

        font-weight:
            700;
    }

</style>

<script>

    let currentPrice = 0;

    const quantityInput =
        document.getElementById('quantity');

    const totalPrice =
        document.getElementById('total-price');

    const qtyDisplay =
        document.getElementById('qty-display');

    const radios =
        document.querySelectorAll(
            'input[name="ticket_id"]'
        );

    radios.forEach(radio => {

        radio.addEventListener('change', () => {

            currentPrice =
                parseInt(radio.dataset.price);

            updateTotal();
        });
    });

    function increaseQty() {

        quantityInput.value =
            parseInt(quantityInput.value) + 1;

        updateTotal();
    }

    function decreaseQty() {

        if (parseInt(quantityInput.value) > 1) {

            quantityInput.value =
                parseInt(quantityInput.value) - 1;

            updateTotal();
        }
    }

    function updateTotal() {

        const qty =
            parseInt(quantityInput.value);

        const total =
            currentPrice * qty;

        totalPrice.innerText =
            'Rp ' + total.toLocaleString('id-ID');

        qtyDisplay.innerText =
            qty + ' Ticket';
    }

</script>

@endsection