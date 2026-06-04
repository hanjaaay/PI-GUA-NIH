@extends('layouts.public')

@section('content')
<div class="hero-section py-5 mb-5" style="background: var(--warm-gradient); position: relative; overflow: hidden;">
    <div class="container position-relative" style="z-index: 2;">
        <div class="row align-items-center">
            <div class="col-md-6" data-aos="fade-right">
                <h1 class="display-4 mb-3 text-white fw-bold" style="text-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    Festigo
                </h1>
                <p class="lead mb-4 text-white" style="opacity: 0.9;">
                    Your Gateway to Indonesia's Finest Festivals & Events
                </p>
                <p class="text-white mb-4" style="opacity: 0.8;">
                    Discover and book tickets for the most exciting festivals, cultural events, and attractions across Indonesia. From traditional ceremonies to modern entertainment, experience the vibrant spirit of Indonesia.
                </p>
                <form action="{{ route('attractions.index') }}" method="GET" class="mb-3">
                    <div class="input-group input-group-lg glass-effect" style="border-radius: 1rem; overflow: hidden;">
                        <input type="search" 
                               name="query" 
                               class="form-control border-0" 
                               placeholder="Search festivals, events, or locations..."
                               style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px);">
                        <button type="submit" class="btn btn-light border-0" style="background: #1a237e; color: white; transition: all 0.3s ease;">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-md-6 d-none d-md-block text-end" data-aos="fade-left">
                <div class="position-relative">
                    <img src="{{ asset('storage/attractions/hero-image.jpg') }}" 
                         alt="Indonesian Festivals & Events" 
                         class="img-fluid rounded-4" 
                         style="max-height: 400px; box-shadow: var(--neon-shadow);">
                    <div class="position-absolute top-0 start-0 w-100 h-100 rounded-4" 
                         style="background: linear-gradient(45deg, rgba(255,107,53,0.2), rgba(123,44,191,0.2));">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Decorative elements -->
    <div class="position-absolute top-0 start-0 w-100 h-100" style="z-index: 1;">
        <div class="position-absolute" style="top: 20%; left: 10%; width: 100px; height: 100px; background: var(--accent-color); opacity: 0.1; border-radius: 50%;"></div>
        <div class="position-absolute" style="top: 60%; right: 15%; width: 150px; height: 150px; background: var(--accent-color); opacity: 0.1; border-radius: 50%;"></div>
        <div class="position-absolute" style="bottom: 10%; left: 30%; width: 80px; height: 80px; background: var(--accent-color); opacity: 0.1; border-radius: 50%;"></div>
    </div>
</div>

<div class="container">
    <section class="featured-attractions mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Featured Events</h2>
            <a href="{{ route('attractions.index') }}" class="btn btn-outline-primary">
                View All Events
            </a>
        </div>

        <div class="row g-4">
            @forelse($attractions as $attraction)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">
                        @if($attraction->featured_image)
                            <img src="{{ asset('storage/attractions/' . $attraction->featured_image) }}" 
                                 class="card-img-top" 
                                 alt="{{ $attraction->name }}"
                                 style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                 style="height: 200px;">
                                <i class="bi bi-image text-muted" style="font-size: 4rem;"></i>
                            </div>
                        @endif
                        
                        <div class="card-body">
                            <h5 class="card-title">{{ $attraction->name }}</h5>
                            <p class="card-text text-muted mb-2">
                                <i class="bi bi-geo-alt-fill"></i> 
                                {{ $attraction->city }}, {{ $attraction->province }}
                            </p>
                            <p class="card-text">{{ Str::limit($attraction->description, 100) }}</p>
                        </div>

                        <div class="card-footer bg-white border-top-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">Starting from</small>
                                    <div class="text-primary fw-bold">
                                        {{ number_format($attraction->tickets->min('price')) }} IDR
                                    </div>
                                </div>
                                <a href="{{ route('attractions.show', $attraction) }}" 
                                   class="btn btn-outline-primary">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">
                        No featured events available at the moment.
                    </div>
                </div>
            @endforelse
        </div>
    </section>

    <section class="popular-destinations mb-5">
        <h2 class="mb-4">Popular Festival Destinations</h2>
        <div class="row g-4">
            <div class="col-md-3">
                <a href="{{ route('attractions.index', ['search' => 'Jakarta']) }}" 
                   class="text-decoration-none">
                    <div class="card bg-dark text-white">
                        <img src="{{ asset('storage/attractions/jakarta.jpg') }}" 
                             class="card-img" 
                             alt="Jakarta Festivals"
                             style="height: 200px; object-fit: cover;">
                        <div class="card-img-overlay d-flex align-items-end" 
                             style="background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);">
                            <h5 class="card-title mb-2">Jakarta</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('attractions.index', ['search' => 'Bali']) }}" 
                   class="text-decoration-none">
                    <div class="card bg-dark text-white">
                        <img src="{{ asset('storage/attractions/bali.jpg') }}" 
                             class="card-img" 
                             alt="Bali Festivals"
                             style="height: 200px; object-fit: cover;">
                        <div class="card-img-overlay d-flex align-items-end"
                             style="background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);">
                            <h5 class="card-title mb-2">Bali</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('attractions.index', ['search' => 'Yogyakarta']) }}" 
                   class="text-decoration-none">
                    <div class="card bg-dark text-white">
                        <img src="{{ asset('storage/attractions/borobudur.jpg') }}" 
                             class="card-img" 
                             alt="Yogyakarta Festivals"
                             style="height: 200px; object-fit: cover;">
                        <div class="card-img-overlay d-flex align-items-end"
                             style="background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);">
                            <h5 class="card-title mb-2">Yogyakarta</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('attractions.index', ['search' => 'Bandung']) }}" 
                   class="text-decoration-none">
                    <div class="card bg-dark text-white">
                        <img src="{{ asset('storage/attractions/monkey-forest.jpg') }}" 
                             class="card-img" 
                             alt="Bandung Festivals"
                             style="height: 200px; object-fit: cover;">
                        <div class="card-img-overlay d-flex align-items-end"
                             style="background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);">
                            <h5 class="card-title mb-2">Bandung</h5>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <section class="why-choose-us mb-5">
        <h2 class="mb-4">Why Choose Festigo</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 text-center">
                    <div class="card-body">
                        <i class="bi bi-ticket-perforated text-primary" style="font-size: 3rem;"></i>
                        <h4 class="mt-3">Easy Booking</h4>
                        <p class="text-muted">Book your festival tickets in minutes with our simple and secure booking process.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 text-center">
                    <div class="card-body">
                        <i class="bi bi-shield-check text-primary" style="font-size: 3rem;"></i>
                        <h4 class="mt-3">Secure Payments</h4>
                        <p class="text-muted">Your payments are protected with our trusted payment gateways.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 text-center">
                    <div class="card-body">
                        <i class="bi bi-headset text-primary" style="font-size: 3rem;"></i>
                        <h4 class="mt-3">24/7 Support</h4>
                        <p class="text-muted">Our customer support team is always ready to help you with any questions about festivals and events.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection 