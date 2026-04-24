@extends('layouts.app')

@section('title', 'Gallery')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold mb-3">Our Gallery</h1>
        <p class="lead text-muted">Moments captured at Caldera Resto & Pool</p>
    </div>
    
    <!-- Featured Image -->
    @if($featured)
    <div class="row mb-5">
        <div class="col-12">
            <div class="position-relative overflow-hidden rounded-4 shadow-lg">
                <img src="{{ asset('storage/' . $featured->file_path) }}" 
                     class="w-100" 
                     alt="{{ $featured->title }}"
                     style="height: 400px; object-fit: cover;">
                <div class="position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-50 text-white p-3">
                    <h4 class="mb-0">{{ $featured->title }}</h4>
                    <p class="mb-0 small">{{ $featured->description }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Filter Categories -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-center gap-2 flex-wrap">
                <a href="{{ route('branding.gallery') }}" 
                   class="btn {{ !request('category') ? 'btn-primary' : 'btn-outline-primary' }}">
                    All
                </a>
                @foreach($categories as $cat)
                <a href="{{ route('branding.gallery.category', $cat) }}" 
                   class="btn {{ request('category') == $cat ? 'btn-primary' : 'btn-outline-primary' }}">
                    {{ ucfirst($cat) }}
                </a>
                @endforeach
            </div>
        </div>
    </div>
    
    <!-- Gallery Grid -->
    <div class="row g-4">
        @forelse($galleries as $gallery)
        <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="card border-0 shadow-sm h-100 gallery-card">
                <div class="position-relative overflow-hidden" style="height: 250px;">
                    <img src="{{ asset('storage/' . $gallery->file_path) }}" 
                         class="card-img-top gallery-img" 
                         alt="{{ $gallery->title }}"
                         style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;">
                    <div class="gallery-overlay position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-flex align-items-center justify-content-center opacity-0 transition">
                        <a href="{{ route('branding.gallery.show', $gallery) }}" class="btn btn-light rounded-circle mx-1">
                            <i class="fas fa-search"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body text-center p-3">
                    <h6 class="fw-bold mb-1">{{ $gallery->title }}</h6>
                    <small class="text-muted">{{ ucfirst($gallery->category) }}</small>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="fas fa-images fa-4x text-muted mb-3"></i>
            <p class="text-muted">Belum ada foto gallery</p>
        </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    <div class="mt-5">
        {{ $galleries->links() }}
    </div>
</div>

<style>
.gallery-card {
    cursor: pointer;
    overflow: hidden;
    border-radius: 12px;
    transition: transform 0.3s ease;
}

.gallery-card:hover {
    transform: translateY(-5px);
}

.gallery-card:hover .gallery-img {
    transform: scale(1.05);
}

.gallery-card:hover .gallery-overlay {
    opacity: 1 !important;
}

.transition {
    transition: all 0.3s ease;
}

.opacity-0 {
    opacity: 0;
}
</style>
@endsection