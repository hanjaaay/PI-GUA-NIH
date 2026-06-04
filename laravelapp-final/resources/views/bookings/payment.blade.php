@extends('layouts.public')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('bookings.index') }}">My Bookings</a></li>
                    <li class="breadcrumb-item active">Payment</li>
                </ol>
            </nav>

            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-credit-card me-2"></i>
                        Payment for Booking #{{ $booking->booking_code }}
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Booking Summary -->
                        <div class="col-md-4">
                            <h5 class="text-primary mb-3">
                                <i class="bi bi-receipt me-2"></i>Booking Summary
                            </h5>
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">{{ $booking->touristAttraction->name }}</h6>
                                    <p class="card-text text-muted">
                                        <i class="bi bi-calendar me-1"></i>
                                        {{ $booking->visit_date->format('d M Y') }}
                                    </p>
                                    <p class="card-text text-muted">
                                        <i class="bi bi-ticket me-1"></i>
                                        {{ $booking->quantity }} ticket(s)
                                    </p>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-bold">Total Amount:</span>
                                        <span class="fw-bold text-success">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Form -->
                        <div class="col-md-8">
                            <h5 class="text-primary mb-3">
                                <i class="bi bi-credit-card me-2"></i>Payment Method
                            </h5>
                            
                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <!-- Payment Methods -->
                            <div class="mb-4">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <div class="card payment-method-card" data-method="bank_transfer">
                                            <div class="card-body text-center">
                                                <i class="bi bi-bank text-primary" style="font-size: 2rem;"></i>
                                                <h6 class="mt-2">Bank Transfer</h6>
                                                <small class="text-muted">Transfer to our bank account</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="card payment-method-card" data-method="e_wallet">
                                            <div class="card-body text-center">
                                                <i class="bi bi-phone text-primary" style="font-size: 2rem;"></i>
                                                <h6 class="mt-2">E-Wallet</h6>
                                                <small class="text-muted">GoPay, OVO, DANA</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="card payment-method-card" data-method="credit_card">
                                            <div class="card-body text-center">
                                                <i class="bi bi-credit-card text-primary" style="font-size: 2rem;"></i>
                                                <h6 class="mt-2">Credit Card</h6>
                                                <small class="text-muted">Visa, Mastercard</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Form -->
                            <form id="paymentForm" method="POST" action="{{ url('/bookings/' . $booking->id . '/payment') }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="payment_method" id="payment_method" value="">
                                
                                <!-- Bank Transfer Details -->
                                <div id="bankTransferDetails" class="payment-details" style="display: none;">
                                    <div class="card border-primary">
                                        <div class="card-header bg-primary text-white">
                                            <h6 class="mb-0">Bank Transfer Instructions</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6>BCA Bank</h6>
                                                    <p class="mb-1">Account Number: <strong>1234567890</strong></p>
                                                    <p class="mb-1">Account Name: <strong>PT. Festigo Indonesia</strong></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6>Mandiri Bank</h6>
                                                    <p class="mb-1">Account Number: <strong>0987654321</strong></p>
                                                    <p class="mb-1">Account Name: <strong>PT. Festigo Indonesia</strong></p>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="alert alert-info">
                                                <strong>Important:</strong> Please transfer the exact amount and include your booking code as payment reference.
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- E-Wallet Details -->
                                <div id="eWalletDetails" class="payment-details" style="display: none;">
                                    <div class="card border-success">
                                        <div class="card-header bg-success text-white">
                                            <h6 class="mb-0">E-Wallet Payment</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4 text-center mb-3">
                                                    <i class="bi bi-phone text-success" style="font-size: 3rem;"></i>
                                                    <h6 class="mt-2">GoPay</h6>
                                                    <small class="text-muted">Scan QR Code</small>
                                                </div>
                                                <div class="col-md-4 text-center mb-3">
                                                    <i class="bi bi-phone text-success" style="font-size: 3rem;"></i>
                                                    <h6 class="mt-2">OVO</h6>
                                                    <small class="text-muted">Scan QR Code</small>
                                                </div>
                                                <div class="col-md-4 text-center mb-3">
                                                    <i class="bi bi-phone text-success" style="font-size: 3rem;"></i>
                                                    <h6 class="mt-2">DANA</h6>
                                                    <small class="text-muted">Scan QR Code</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Credit Card Details -->
                                <div id="creditCardDetails" class="payment-details" style="display: none;">
                                    <div class="card border-warning">
                                        <div class="card-header bg-warning text-dark">
                                            <h6 class="mb-0">Credit Card Payment</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="card_number" class="form-label">Card Number</label>
                                                    <input type="text" class="form-control" id="card_number" placeholder="1234 5678 9012 3456">
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="expiry" class="form-label">Expiry Date</label>
                                                    <input type="text" class="form-control" id="expiry" placeholder="MM/YY">
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="cvv" class="form-label">CVV</label>
                                                    <input type="text" class="form-control" id="cvv" placeholder="123">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Proof Upload -->
                                <div class="mb-4">
                                    <label for="payment_proof" class="form-label">Payment Proof (Required for Bank Transfer)</label>
                                    <input type="file" class="form-control @error('payment_proof') is-invalid @enderror" 
                                           id="payment_proof" name="payment_proof" accept="image/*">
                                    <div class="form-text">Upload screenshot of your payment confirmation (JPG, PNG, max 2MB)</div>
                                    @error('payment_proof')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Payment Summary -->
                                <div class="card bg-light mb-4">
                                    <div class="card-body">
                                        <h6 class="card-title">Payment Summary</h6>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Booking Amount:</span>
                                            <span>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Payment Fee:</span>
                                            <span>Rp 0</span>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between fw-bold">
                                            <span>Total Payment:</span>
                                            <span class="text-success">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-success btn-lg" id="submitPayment">
                                        <i class="bi bi-check-circle me-2"></i>Confirm Payment
                                    </button>
                                    <a href="{{ route('bookings.show', $booking) }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left me-2"></i>Back to Booking
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.payment-method-card {
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.payment-method-card:hover {
    border-color: var(--bs-primary);
    transform: translateY(-2px);
}

.payment-method-card.selected {
    border-color: var(--bs-primary);
    background-color: var(--bs-primary);
    color: white;
}

.payment-method-card.selected i,
.payment-method-card.selected h6,
.payment-method-card.selected small {
    color: white !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethodCards = document.querySelectorAll('.payment-method-card');
    const paymentMethodInput = document.getElementById('payment_method');
    const paymentDetails = document.querySelectorAll('.payment-details');
    const submitButton = document.getElementById('submitPayment');

    // Payment method selection
    paymentMethodCards.forEach(card => {
        card.addEventListener('click', function() {
            // Remove selected class from all cards
            paymentMethodCards.forEach(c => c.classList.remove('selected'));
            
            // Add selected class to clicked card
            this.classList.add('selected');
            
            // Set payment method value
            const method = this.dataset.method;
            paymentMethodInput.value = method;
            
            // Show/hide payment details
            paymentDetails.forEach(detail => detail.style.display = 'none');
            
            if (method === 'bank_transfer') {
                document.getElementById('bankTransferDetails').style.display = 'block';
            } else if (method === 'e_wallet') {
                document.getElementById('eWalletDetails').style.display = 'block';
            } else if (method === 'credit_card') {
                document.getElementById('creditCardDetails').style.display = 'block';
            }
            
            // Enable submit button
            submitButton.disabled = false;
        });
    });

    // Form validation
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        if (!paymentMethodInput.value) {
            e.preventDefault();
            alert('Please select a payment method');
            return false;
        }
        
        if (paymentMethodInput.value === 'bank_transfer') {
            const paymentProof = document.getElementById('payment_proof');
            if (!paymentProof.files.length) {
                e.preventDefault();
                alert('Please upload payment proof for bank transfer');
                return false;
            }
        }
    });
});
</script>
@endsection 
