@extends('layouts.public')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('bookings.index') }}">My Bookings</a></li>
            <li class="breadcrumb-item"><a href="{{ route('bookings.show', $booking) }}">{{ $booking->booking_code }}</a></li>
            <li class="breadcrumb-item active">Edit Booking</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h1 class="card-title h3 mb-4">Edit Booking</h1>

                    <form action="{{ route('bookings.update', $booking) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <h5>Ticket Details</h5>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6>{{ $booking->ticket->name }}</h6>
                                    <p class="text-muted mb-2">{{ $booking->ticket->description }}</p>
                                    <p class="h5 mb-0">{{ number_format($booking->ticket->price) }} IDR per ticket</p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="visit_date" class="form-label">Visit Date</label>
                            <input type="date" class="form-control @error('visit_date') is-invalid @enderror" 
                                   id="visit_date" name="visit_date" 
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                   value="{{ old('visit_date', $booking->visit_date->format('Y-m-d')) }}" required>
                            @error('visit_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="quantity" class="form-label">Number of Tickets</label>
                            <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                   id="quantity" name="quantity" min="1" max="{{ $booking->ticket->max_tickets_per_day }}"
                                   value="{{ old('quantity', $booking->quantity) }}" required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Maximum {{ $booking->ticket->max_tickets_per_day }} tickets per booking</small>
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="form-label">Special Requests/Notes (Optional)</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3">{{ old('notes', $booking->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Update Booking</button>
                            <a href="{{ route('bookings.show', $booking) }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Booking Summary</h5>
                    <div class="mb-3">
                        <h6>{{ $booking->ticket->attraction->name }}</h6>
                        <p class="text-muted">
                            <i class="bi bi-geo-alt"></i> {{ $booking->ticket->attraction->city }}, {{ $booking->ticket->attraction->province }}
                        </p>
                    </div>

                    <div class="mb-3">
                        <h6>Important Information</h6>
                        <ul class="list-unstyled">
                            <li><i class="bi bi-info-circle text-info"></i> Changes are allowed up to 24 hours before the visit date</li>
                            <li><i class="bi bi-info-circle text-info"></i> Additional charges may apply if the price has changed</li>
                            <li><i class="bi bi-info-circle text-info"></i> Subject to ticket availability</li>
                        </ul>
                    </div>

                    <div id="booking-calculation" class="d-none">
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Price per ticket</span>
                            <span>{{ number_format($booking->ticket->price) }} IDR</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Quantity</span>
                            <span id="summary-quantity">0</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Total Amount</strong>
                            <strong id="summary-total">0 IDR</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const quantityInput = document.getElementById('quantity');
    const summaryQuantity = document.getElementById('summary-quantity');
    const summaryTotal = document.getElementById('summary-total');
    const bookingCalculation = document.getElementById('booking-calculation');
    const ticketPrice = {{ $booking->ticket->price }};

    function updateSummary() {
        const quantity = parseInt(quantityInput.value) || 0;
        const total = quantity * ticketPrice;

        if (quantity > 0) {
            bookingCalculation.classList.remove('d-none');
            summaryQuantity.textContent = quantity;
            summaryTotal.textContent = total.toLocaleString() + ' IDR';
        } else {
            bookingCalculation.classList.add('d-none');
        }
    }

    quantityInput.addEventListener('input', updateSummary);
    updateSummary();
});
</script>
@endpush 