@extends('layouts.public')

@section('content')
<div class="hero-section text-white position-relative overflow-hidden" style="background: var(--warm-gradient); padding-top: 76px; padding-bottom: 50px;">
    <div class="container position-relative" style="z-index: 2; padding-top: 100px;">
        <div class="row align-items-center">
            <div class="col-md-7" data-aos="fade-right" data-aos-duration="1000">
                <h1 class="display-3 mb-3 fw-bold" style="text-shadow: 0 2px 4px rgba(0,0,0,0.2);">Festigo</h1>
                <p class="lead mb-4" style="opacity: 0.9;">Your Gateway to Indonesia's Finest Festivals & Events</p>
                <p class="mb-4" style="opacity: 0.9;">Discover and book tickets for the most exciting festivals, cultural events, and attractions across Indonesia. From traditional ceremonies to modern entertainment, experience the vibrant spirit of Indonesia.</p>
                <form action="{{ route('attractions.index') }}" method="GET" class="d-flex flex-column flex-md-row gap-2 mb-4">
                    <input type="text" name="search" class="form-control form-control-lg flex-grow-1" placeholder="Search festivals, events, or locations..." style="background: rgba(255, 255, 255, 0.9); border: none; backdrop-filter: blur(10px);">
                    <button type="submit" class="btn btn-light btn-lg" style="background: var(--accent-color); color: var(--text-color); border: none; transition: all 0.3s ease;">
                        <i class="bi bi-search me-2"></i>Search
                    </button>
                </form>
                <p class="small mb-0" style="opacity: 0.7;">Popular searches: <a href="{{ route('attractions.index', ['search' => 'Concerts']) }}" class="text-white text-decoration-none border-bottom border-white border-opacity-50">Concerts</a>, <a href="{{ route('attractions.index', ['search' => 'Festival']) }}" class="text-white text-decoration-none border-bottom border-white border-opacity-50">Festivals</a>, <a href="{{ route('attractions.index', ['city' => 'Bali']) }}" class="text-white text-decoration-none border-bottom border-white border-opacity-50">Bali</a></p>
            </div>
            <div class="col-md-5 d-none d-md-block" data-aos="fade-left" data-aos-duration="1000">
                <img src="{{ asset('images/hero-illustration.png') }}" alt="Indonesian Festivals & Events" class="img-fluid" style="max-height: 350px; opacity: 0.9;">
            </div>
        </div>
    </div>
    <div class="position-absolute top-0 start-0 w-100 h-100" style="z-index: 1;">
        <div class="position-absolute" style="top: 10%; left: 5%; width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%; animation: float1 8s infinite ease-in-out;"></div>
        <div class="position-absolute" style="bottom: 15%; right: 10%; width: 120px; height: 120px; background: rgba(255,255,255,0.15); border-radius: 50%; animation: float2 10s infinite ease-in-out;"></div>
        <div class="position-absolute" style="top: 40%; right: 5%; width: 50px; height: 50px; background: rgba(255,255,255,0.05); border-radius: 50%; animation: float3 7s infinite ease-in-out;"></div>
        <div class="position-absolute" style="bottom: 5%; left: 20%; width: 90px; height: 90px; background: rgba(255,255,255,0.1); border-radius: 50%; animation: float4 9s infinite ease-in-out;"></div>
    </div>
</div>

<div class="container">
    <section class="mb-5">
        <h2 class="mb-4">Kategori</h2>
        <div class="row g-4">
            <div class="col-6 col-md-3">
                <div class="category-card text-center p-3">
                    <div class="category-icon">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <h5>Destinasi</h5>
                    <p class="text-muted small">Temukan tempat wisata menarik</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="category-card text-center p-3">
                    <div class="category-icon">
                        <i class="bi bi-music-note-beamed"></i>
                    </div>
                    <h5>Konser</h5>
                    <p class="text-muted small">Tiket konser terbaik</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="category-card text-center p-3">
                    <div class="category-icon">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                    <h5>Event</h5>
                    <p class="text-muted small">Event seru di Indonesia</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="category-card text-center p-3">
                    <div class="category-icon">
                        <i class="bi bi-tag"></i>
                    </div>
                    <h5>Promo</h5>
                    <p class="text-muted small">Penawaran spesial</p>
                </div>
            </div>
        </div>
    </section>

    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Destinasi Populer</h2>
            <a href="{{ route('attractions.index') }}" class="btn btn-outline-primary">Lihat Semua</a>
        </div>
        <div class="row g-4">
            @foreach($featuredAttractions as $attraction)
            <div class="col-md-4">
                <div class="card attraction-card h-100 shadow-sm">
                    <img src="{{ asset('storage/' . $attraction->featured_image) }}" 
                             class="card-img-top" 
                             alt="{{ $attraction->name }}"
                             style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title">{{ $attraction->name }}</h5>
                        <p class="card-text text-muted">
                            <i class="bi bi-geo-alt me-2"></i>{{ $attraction->location }}
                        </p>
                        <p class="card-text small">{{ Str::limit($attraction->description, 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="price-tag fw-bold text-primary">Mulai Rp {{ number_format($attraction->tickets->min('price'), 0, ',', '.') }}</span>
                            <a href="{{ route('attractions.show', $attraction) }}" class="btn btn-primary">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <section class="popular-destinations mb-5">
        <h2 class="mb-4">Destinasi Wisata Populer</h2>
        <div class="row g-4">
            <div class="col-md-3">
                <a href="{{ route('attractions.index', ['search' => 'Jakarta']) }}" class="text-decoration-none">
                    <div class="card bg-dark text-white">
                        <img src="{{ asset('storage/attractions/jakarta.jpg') }}" 
                                 class="card-img" 
                                 alt="Jakarta"
                                 style="height: 200px; object-fit: cover;">
                        <div class="card-img-overlay d-flex align-items-end" 
                                 style="background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);">
                            <h5 class="card-title mb-2">Jakarta</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('attractions.index', ['search' => 'Bali']) }}" class="text-decoration-none">
                    <div class="card bg-dark text-white">
                        <img src="{{ asset('storage/attractions/bali.jpg') }}" 
                                 class="card-img" 
                                 alt="Bali"
                                 style="height: 200px; object-fit: cover;">
                        <div class="card-img-overlay d-flex align-items-end"
                                 style="background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);">
                            <h5 class="card-title mb-2">Bali</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('attractions.index', ['search' => 'Yogyakarta']) }}" class="text-decoration-none">
                    <div class="card bg-dark text-white">
                        <img src="{{ asset('storage/attractions/borobudur.jpg') }}" 
                                 class="card-img" 
                                 alt="Yogyakarta"
                                 style="height: 200px; object-fit: cover;">
                        <div class="card-img-overlay d-flex align-items-end"
                                 style="background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);">
                            <h5 class="card-title mb-2">Yogyakarta</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('attractions.index', ['search' => 'Bandung']) }}" class="text-decoration-none">
                    <div class="card bg-dark text-white">
                        <img src="{{ asset('storage/attractions/bandung.jpg') }}" 
                                 class="card-img" 
                                 alt="Bandung"
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

    <section class="mb-5">
        <h2 class="text-center mb-4">Mengapa Memilih Kami?</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="text-center">
                    <i class="bi bi-shield-check display-4 text-primary mb-3"></i>
                    <h4>Aman & Terpercaya</h4>
                    <p class="text-muted">Transaksi aman dan terjamin</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <i class="bi bi-currency-dollar display-4 text-primary mb-3"></i>
                    <h4>Harga Terbaik</h4>
                    <p class="text-muted">Dapatkan harga terbaik untuk setiap tiket</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <i class="bi bi-headset display-4 text-primary mb-3"></i>
                    <h4>24/7 Support</h4>
                    <p class="text-muted">Layanan pelanggan siap membantu Anda</p>
                </div>
            </div>
        </div>
    </section>
</div>

<div id="global-loading" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;z-index:3000;background:rgba(255,255,255,0.7);align-items:center;justify-content:center;">
    <div class="spinner-border text-primary" role="status" aria-label="Loading">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.showLoading = function() {
            document.getElementById('global-loading').style.display = 'flex';
        };
        window.hideLoading = function() {
            document.getElementById('global-loading').style.display = 'none';
        };
    });
</script>
@endsection