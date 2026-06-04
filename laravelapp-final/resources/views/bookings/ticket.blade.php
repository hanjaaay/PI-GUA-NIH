@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-ticket-perforated me-2"></i>
                        E-Ticket #{{ $booking->booking_code }}
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Ticket Header -->
                    <div class="text-center mb-4">
                        <h2 class="text-primary mb-2">FESTIGO</h2>
                        <p class="text-muted mb-0">Your Gateway to Indonesia's Finest Festivals & Events</p>
                    </div>

                    <!-- Ticket Content -->
                    <div class="row">
                        <div class="col-md-8">
                            <div class="ticket-info">
                                <h5 class="text-primary mb-3">{{ $booking->touristAttraction->name }}</h5>
                                
                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <small class="text-muted">Booking Code</small><br>
                                        <strong>{{ $booking->booking_code }}</strong>
                                    </div>
                                    <div class="col-sm-4">
                                        <small class="text-muted">Visit Date</small><br>
                                        <strong>{{ $booking->visit_date->format('d M Y') }}</strong>
                                    </div>
                                    <div class="col-sm-4">
                                        <small class="text-muted">Tickets</small><br>
                                        <strong>{{ $booking->quantity }} ticket(s)</strong>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-6">
                                        <small class="text-muted">Location</small><br>
                                        <strong>{{ $booking->touristAttraction->location }}</strong>
                                    </div>
                                    <div class="col-sm-6">
                                        <small class="text-muted">Total Amount</small><br>
                                        <strong class="text-success">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</strong>
                                    </div>
                                </div>

                                @if($booking->touristAttraction->opening_hours)
                                    <div class="row mb-3">
                                        <div class="col-sm-6">
                                            <small class="text-muted">Opening Hours</small><br>
                                            <strong>{{ $booking->touristAttraction->opening_hours }}</strong>
                                        </div>
                                        <div class="col-sm-6">
                                            <small class="text-muted">Closing Hours</small><br>
                                            <strong>{{ $booking->touristAttraction->closing_hours }}</strong>
                                        </div>
                                    </div>
                                @endif

                                @if($booking->notes)
                                    <div class="mb-3">
                                        <small class="text-muted">Special Notes</small><br>
                                        <strong>{{ $booking->notes }}</strong>
                                    </div>
                                @endif

                                <!-- Important Information -->
                                <div class="alert alert-info">
                                    <h6 class="alert-heading">
                                        <i class="bi bi-info-circle me-2"></i>Important Information
                                    </h6>
                                    <ul class="mb-0">
                                        <li>Please arrive 15 minutes before your scheduled visit time</li>
                                        <li>Bring a valid ID for verification</li>
                                        <li>This ticket is valid only for the specified date</li>
                                        <li>No refunds for no-shows or late arrivals</li>
                                        <li>Follow all safety protocols and guidelines</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- QR Code Placeholder -->
                            <div class="text-center mb-3">
                                <div class="qr-code-placeholder bg-light border rounded p-3">
                                    <i class="bi bi-qr-code text-muted" style="font-size: 4rem;"></i>
                                    <p class="text-muted mt-2 mb-0">QR Code</p>
                                    <small class="text-muted">{{ $booking->booking_code }}</small>
                                </div>
                            </div>

                            <!-- Ticket Status -->
                            <div class="text-center">
                                <div class="badge bg-success fs-6 mb-2">CONFIRMED</div>
                                <p class="text-muted small mb-0">Ticket is valid for entry</p>
                            </div>

                            <!-- Contact Information -->
                            <div class="mt-4">
                                <h6 class="text-primary">Need Help?</h6>
                                <p class="text-muted small mb-1">
                                    <i class="bi bi-telephone me-1"></i>+62 21 1234 5678
                                </p>
                                <p class="text-muted small mb-1">
                                    <i class="bi bi-envelope me-1"></i>support@festigo.com
                                </p>
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-clock me-1"></i>24/7 Support
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="text-center mt-4 pt-3 border-top">
                        <button onclick="window.print()" class="btn btn-primary me-2">
                            <i class="bi bi-printer me-2"></i>Print Ticket
                        </button>
                        <a href="{{ route('bookings.show', $booking) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Back to Booking
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .btn, .navbar, footer {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    
    .ticket-info {
        font-size: 12px;
    }
}

.qr-code-placeholder {
    min-height: 150px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
</style>
@endsection 
