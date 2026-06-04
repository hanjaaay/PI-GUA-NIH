@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                @if($attraction->featured_image)
                    <img src="{{ asset('storage/attractions/' . $attraction->featured_image) }}" 
                         class="card-img-top img-fluid" alt="{{ $attraction->name }}" style="max-height: 500px; object-fit: cover;">
                @endif
                <div class="card-body">
                    <h1 class="card-title">{{ $attraction->name }}</h1>
                    <p class="text-muted"><i class="bi bi-geo-alt-fill"></i> {{ $attraction->location }}</p>
                    <p class="card-text">{!! nl2br(e($attraction->description)) !!}</p>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h4>More Information</h4>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">Category:</dt>
                        <dd class="col-sm-9">{{ $attraction->category->name ?? 'N/A' }}</dd>
                        
                        <dt class="col-sm-3">Operating Hours:</dt>
                        <dd class="col-sm-9">{{ $attraction->operating_hours ?? 'N/A' }}</dd>
                        
                        <dt class="col-sm-3">Contact:</dt>
                        <dd class="col-sm-9">{{ $attraction->contact_info ?? 'N/A' }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm sticky-top" style="top: 20px;">
                <div class="card-body">
                    <h5 class="card-title text-center">Ticket Pricing</h5>
                    <hr>
                    @if($attraction->tickets->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($attraction->tickets as $ticket)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ $ticket->name }}</span>
                                    <span class="fw-bold text-primary">Rp {{ number_format($ticket->price, 0, ',', '.') }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <div class="d-grid mt-4">
                            <a href="{{ route('bookings.create', $attraction) }}" class="btn btn-primary btn-lg">
                                <i class="bi bi-ticket-perforated me-2"></i> Book Now
                            </a>
                        </div>
                    @else
                        <div class="alert alert-warning text-center">
                            No tickets available at the moment.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection