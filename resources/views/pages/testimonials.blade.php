@extends('layouts.app')

@section('title', 'Testimonials')

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold mb-2">What Our Customers Say</h1>
        <div class="section-divider"></div>
        <p class="lead text-muted">Real experiences from real people</p>
    </div>

    <!-- Rating Summary -->
    <div class="row mb-5">
        <div class="col-md-4 text-center mb-4">
            <div class="card border-0 shadow-sm p-4 h-100 caldera-card">
                <div class="display-1 fw-bold mb-1" style="color: #1c3451; font-family: 'Playfair Display', serif;">{{ number_format($ratingStats['average'], 1) }}</div>
                <div class="mb-2">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= floor($ratingStats['average']))
                            <i class="fas fa-star fa-lg" style="color: #c1a067;"></i>
                        @elseif($i - $ratingStats['average'] <= 0.5)
                            <i class="fas fa-star-half-alt fa-lg" style="color: #c1a067;"></i>
                        @else
                            <i class="far fa-star fa-lg" style="color: #c1a067;"></i>
                        @endif
                    @endfor
                </div>
                <p class="text-muted">Average Rating</p>
                <p class="mb-0">Based on <strong style="color: #1c3451;">{{ $ratingStats['total'] }}</strong> reviews</p>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm p-4 h-100 caldera-card">
                <div class="row">
                    <div class="col-6">
                        @foreach([5,4,3] as $star)
                        <div class="d-flex align-items-center mb-2">
                            <div class="me-2" style="width: 50px;">
                                <span class="fw-bold" style="font-size: 13px;">{{ $star }} Star</span>
                            </div>
                            <div class="progress flex-grow-1" style="height: 8px; border-radius: 4px; background: #f0ebe0;">
                                @php
                                    $percent = $ratingStats['total'] > 0 ? ($ratingStats[$star . '_star'] / $ratingStats['total']) * 100 : 0;
                                @endphp
                                <div class="progress-bar" style="width: {{ $percent }}%; background: #c1a067; border-radius: 4px;"></div>
                            </div>
                            <div class="ms-2" style="width: 40px;">
                                <span class="text-muted small">{{ $ratingStats[$star . '_star'] }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="col-6">
                        <div class="d-flex flex-column justify-content-between h-100">
                            <a href="{{ route('branding.testimonials.create') }}" class="btn btn-caldera w-100 mb-3">
                                <i class="fas fa-star me-2"></i>Write a Review
                            </a>
                            <div class="dropdown w-100">
                                <button class="btn btn-filter-outline w-100 dropdown-toggle" type="button" data-bs-toggle="dropdown">
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
                    <!-- Quote icon -->
                    <div class="mb-3">
                        <i class="fas fa-quote-left fa-lg" style="color: #c1a067; opacity: 0.5;"></i>
                    </div>

                    <div class="d-flex align-items-center mb-3">
                        @if($testimonial->customer_photo)
                            <img src="{{ asset('storage/' . $testimonial->customer_photo) }}"
                                 class="rounded-circle me-3"
                                 width="55" height="55"
                                 style="object-fit: cover; border: 2px solid #c1a067;"
                                 alt="{{ $testimonial->customer_name }}">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($testimonial->customer_name) }}&background=1c3451&color=c1a067&size=60"
                                 class="rounded-circle me-3"
                                 width="55" height="55"
                                 style="border: 2px solid #c1a067;"
                                 alt="{{ $testimonial->customer_name }}">
                        @endif
                        <div>
                            <h6 class="fw-bold mb-0" style="color: #1c3451;">{{ $testimonial->customer_name }}</h6>
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
                                <i class="fas fa-star" style="color: #c1a067;"></i>
                            @else
                                <i class="far fa-star" style="color: #c1a067;"></i>
                            @endif
                        @endfor
                    </div>

                    <p class="fst-italic text-muted mb-3">"{{ \Illuminate\Support\Str::limit($testimonial->comment, 150) }}"</p>

                    @if($testimonial->visit_date)
                        <small class="text-muted">
                            <i class="fas fa-calendar-alt me-1" style="color: #c1a067;"></i>
                            Visited: {{ \Carbon\Carbon::parse($testimonial->visit_date)->format('d M Y') }}
                        </small>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="fas fa-star fa-4x mb-3" style="color: #c1a067; opacity: 0.4;"></i>
            <h5 style="color: #1c3451;">No testimonials yet</h5>
            <p class="text-muted">Be the first to write a review!</p>
            <a href="{{ route('branding.testimonials.create') }}" class="btn btn-caldera">Write a Review</a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-5">
        {{ $testimonials->links() }}
    </div>
</div>
@endsection

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap');

    h1.display-4 {
        font-family: 'Playfair Display', serif;
        color: #1c3451;
    }

    .section-divider {
        width: 50px;
        height: 3px;
        background: #c1a067;
        margin: 12px auto 16px;
        border-radius: 2px;
    }

    .caldera-card {
        border-radius: 16px !important;
    }

    .testimonial-card {
        border-radius: 16px !important;
        border-top: 3px solid transparent !important;
        transition: transform 0.25s, box-shadow 0.25s, border-color 0.25s;
    }

    .testimonial-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 28px rgba(28,52,81,0.1) !important;
        border-top: 3px solid #c1a067 !important;
    }

    .btn-caldera {
        background: linear-gradient(135deg, #1c3451, #01516e);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        padding: 10px 20px;
        transition: all 0.2s;
    }

    .btn-caldera:hover {
        background: linear-gradient(135deg, #c1a067, #a8894f);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(193,160,103,0.3);
    }

    .btn-filter-outline {
        border: 1.5px solid #1c3451;
        color: #1c3451;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        padding: 10px 20px;
        background: white;
        transition: all 0.2s;
    }

    .btn-filter-outline:hover {
        background: #1c3451;
        color: white;
    }

    /* Dark mode */
    body.dark-mode .testimonial-card {
        background: #1e1e2a !important;
    }

    body.dark-mode .testimonial-card .text-muted {
        color: #b0b0b0 !important;
    }
</style>
@endpush