@extends('layouts.public')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 mb-0">Konser & Festival</h1>
            <p class="text-muted">Temukan konser dan festival terbaik di Indonesia</p>
        </div>
    </div>

    @if($attractions->isEmpty())
        <div class="alert alert-info">
            Belum ada konser yang tersedia saat ini.
        </div>
    @else
        <div class="row g-4">
            @foreach($attractions as $attraction)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        @if($attraction->featured_image)
                            <img src="{{ asset('storage/attractions/' . $attraction->featured_image) }}" 
                                 class="card-img-top" 
                                 alt="{{ $attraction->name }}"
                                 style="height: 200px; object-fit: cover;">
                        @else
                            <img src="{{ asset('images/tickets/' . Str::slug($attraction->name) . '.jpg') }}"
                                 class="card-img-top"
                                 alt="{{ $attraction->name }}"
                                 style="height: 200px; object-fit: cover;">
                        @endif
                        
                        <div class="card-body">
                            <h5 class="card-title">{{ $attraction->name }}</h5>
                            <p class="card-text text-muted mb-2">
                                <i class="bi bi-geo-alt-fill"></i> 
                                {{ $attraction->city }}, {{ $attraction->province }}
                            </p>
                            <p class="card-text">{{ Str::limit($attraction->description, 100) }}</p>
                            
                            @if($attraction->facilities)
                                <div class="mt-2">
                                    <small class="text-muted">Fasilitas:</small>
                                    <div class="d-flex flex-wrap gap-1 mt-1">
                                        @foreach(json_decode($attraction->facilities) as $facility)
                                            <span class="badge bg-light text-dark">{{ $facility }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="card-footer bg-white border-top-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">Mulai dari</small>
                                    <div class="text-primary fw-bold">
                                        Rp {{ number_format($attraction->tickets->min('price'), 0, ',', '.') }}
                                    </div>
                                </div>
                                <a href="{{ route('attractions.show', $attraction) }}" 
                                   class="btn btn-primary">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $attractions->links() }}
        </div>
    @endif
</div>
@endsection 