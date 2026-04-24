@extends('layouts.app')

@section('title', 'Testimonials')

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold mb-3">What Our Customers Say</h1>
        <p class="lead text-muted">Real experiences from real people</p>
    </div>
    
    <!-- Rating Summary -->
    <div class="row mb-5">
        <div class="col-md-4 text-center mb-4">
            <div class="card border-0 shadow-sm p-4 h-100">
                <div class="display-1 fw-bold text-primary">{{ number_format($ratingStats['average'], 1) }}</div>
                <div class="mb-2">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= floor($ratingStats['average']))
                            <i class="fas fa-star text-warning fa-lg"></i>
                        @elseif($i - $ratingStats['average'] <= 0.5)
                            <i class="fas fa-star-half-alt text-warning fa-lg"></i>
                        @else
                            <i class="far fa-star text-warning fa-lg"></i>
                        @endif
                    @endfor
                </div>
                <p class="text-muted">Average Rating</p>
                <p class="mb-0">Based on <strong>{{ $ratingStats['total'] }}</strong> reviews</p>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card border-0 shadow-sm p-4 h-100">
                <div class="row">
                    <div class="col-6">
                        @foreach([5,4,3] as $star)
                        <div class="d-flex align-items-center mb-2">
                            <div class="me-2" style="width: 50px;">
                                <span class="fw-bold">{{ $star }} Star</span>
                            </div>
                            <div class="progress flex-grow-1" style="height: 10px;">
                                @php
                                    $percent = $ratingStats['total'] > 0 ? ($ratingStats[$star . '_star'] / $ratingStats['total']) * 100 : 0;
                                @endphp
                                <div class="progress-bar bg-warning" style="width: {{ $percent }}%"></div>
                            </div>
                            <div class="ms-2" style="width: 40px;">
                                <span class="text-muted small">{{ $ratingStats[$star . '_star'] }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="col-6">
                        <div class="d-flex flex-column justify-content-between h-100">
                            <a href="{{ route('branding.testimonials.create') }}" class="btn btn-primary w-100 mb-3">
                                <i class="fas fa-star me-2"></i>Write a Review
                            </a>
                            <div class="dropdown w-100">
                                <button class="btn btn-outline-secondary w-100 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-filter me-2"></i>Filter Reviews
                                </button>
                                <ul class="dropdown-menu w-100">
                                    <li><a class="dropdown-item" href="{{ route('branding.testimonials') }}">All Reviews</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('branding.testimonials', ['rating' => 5]) }}">5 Star Only</a></li>
                                    <li><a class="dropdown-item" href="{{ route('branding.testimonials', ['rating' => 4]) }}">4 Star & Up</a></li>
                                    <li><a class="dropdown-item" href="{{ route('branding.testimonials', ['rating' => 3]) }}">3 Star & Up</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('branding.testimonials', ['service' => 'restaurant']) }}">Restaurant</a></li>
                                    <li><a class="dropdown-item" href="{{ route('branding.testimonials', ['service' => 'pool']) }}">Pool</a></li>
                                    <li><a class="dropdown-item" href="{{ route('branding.testimonials', ['service' => 'event']) }}">Event</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Testimonials Grid -->
    <div class="row g-4">
        @forelse($testimonials as $testimonial)
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 testimonial-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        @if($testimonial->customer_photo)
                            <img src="{{ asset('storage/' . $testimonial->customer_photo) }}" 
                                 class="rounded-circle me-3" 
                                 width="60" height="60" 
                                 alt="{{ $testimonial->customer_name }}">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($testimonial->customer_name) }}&background=667eea&color=fff&size=60" 
                                 class="rounded-circle me-3" 
                                 width="60" height="60" 
                                 alt="{{ $testimonial->customer_name }}">
                        @endif
                        <div>
                            <h6 class="fw-bold mb-0">{{ $testimonial->customer_name }}</h6>
                            <small class="text-muted">
                                @if($testimonial->service_type)
                                    {{ ucfirst($testimonial->service_type) }} Visitor
                                @else
                                    Customer
                                @endif
                            </small>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $testimonial->rating)
                                <i class="fas fa-star text-warning"></i>
                            @else
                                <i class="far fa-star text-warning"></i>
                            @endif
                        @endfor
                    </div>
                    
                    <p class="text-muted mb-3 fst-italic">"{{ \Illuminate\Support\Str::limit($testimonial->comment, 150) }}"</p>
                    
                    @if($testimonial->visit_date)
                        <small class="text-muted">
                            <i class="fas fa-calendar-alt me-1"></i> 
                            Visited: {{ \Carbon\Carbon::parse($testimonial->visit_date)->format('d M Y') }}
                        </small>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="fas fa-star fa-4x text-muted mb-3"></i>
            <h5>No testimonials yet</h5>
            <p class="text-muted">Be the first to write a review!</p>
            <a href="{{ route('branding.testimonials.create') }}" class="btn btn-primary">Write a Review</a>
        </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    <div class="mt-5">
        {{ $testimonials->links() }}
    </div>
</div>

<style>
.testimonial-card {
    transition: transform 0.3s ease;
}

.testimonial-card:hover {
    transform: translateY(-5px);
}

/* Dark mode */
body.dark-mode .testimonial-card {
    background: #1e1e2a !important;
}

body.dark-mode .testimonial-card .text-muted {
    color: #b0b0b0 !important;
}
</style>
@endsection