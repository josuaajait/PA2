@extends('layouts.app')

@section('title', 'Write a Review - Caldera Resto & Pool')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold mb-2" style="font-family: 'Playfair Display', serif; color: #1c3451;">Write a Review</h1>
        <div class="section-divider"></div>
        <p class="lead text-muted">Share your experience at Caldera Resto & Pool</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm caldera-card">
                <div class="card-header bg-transparent border-bottom-0 pt-4 px-4">
                    <div class="text-center">
                        <i class="fas fa-star fa-2x mb-2" style="color: #c1a067;"></i>
                        <h4 class="fw-bold mb-1" style="color: #1c3451; font-family: 'Playfair Display', serif;">Your Feedback Matters</h4>
                        <p class="text-muted small">Help us improve by sharing your honest opinion</p>
                    </div>
                </div>
                
                <div class="card-body p-4 p-lg-5">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i> Please fix the following errors:
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <form action="{{ route('branding.testimonials.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="color: #1c3451;">
                                    <i class="fas fa-user me-1" style="color: #c1a067;"></i> Your Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="customer_name" class="form-control caldera-input @error('customer_name') is-invalid @enderror" 
                                       value="{{ old('customer_name', Auth::user()->name ?? '') }}" required>
                                @error('customer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            
                            {{-- BAGIAN EMAIL DIHAPUS --}}
                            
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="color: #1c3451;">
                                    <i class="fas fa-tag me-1" style="color: #c1a067;"></i> Service Type
                                </label>
                                <select name="service_type" class="form-control caldera-input">
                                    <option value="">Select Service</option>
                                    <option value="restaurant" {{ old('service_type') == 'restaurant' ? 'selected' : '' }}>🍽️ Restaurant</option>
                                    <option value="pool" {{ old('service_type') == 'pool' ? 'selected' : '' }}>🏊 Pool</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="color: #1c3451;">
                                    <i class="fas fa-calendar-day me-1" style="color: #c1a067;"></i> Visit Date
                                </label>
                                <input type="date" name="visit_date" class="form-control caldera-input" 
                                       value="{{ old('visit_date', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}">
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label fw-semibold" style="color: #1c3451;">
                                    <i class="fas fa-camera me-1" style="color: #c1a067;"></i> Your Photo <span class="text-muted">(Optional)</span>
                                </label>
                                <input type="file" name="customer_photo" class="form-control caldera-input" accept="image/*" onchange="previewPhoto(event)">
                                <small class="text-muted">Max 2MB. JPG, JPEG, or PNG</small>
                                <div id="photoPreview" class="mt-2" style="display: none;">
                                    <img id="previewImg" src="#" alt="Preview" style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%; border: 2px solid #c1a067;">
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label fw-semibold" style="color: #1c3451;">
                                    <i class="fas fa-star me-1" style="color: #c1a067;"></i> Rating <span class="text-danger">*</span>
                                </label>
                                <div class="rating-input">
                                    <i class="far fa-star fa-2x" data-value="1"></i>
                                    <i class="far fa-star fa-2x" data-value="2"></i>
                                    <i class="far fa-star fa-2x" data-value="3"></i>
                                    <i class="far fa-star fa-2x" data-value="4"></i>
                                    <i class="far fa-star fa-2x" data-value="5"></i>
                                    <input type="hidden" name="rating" id="rating" value="{{ old('rating') }}" required>
                                </div>
                                @error('rating')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label fw-semibold" style="color: #1c3451;">
                                    <i class="fas fa-comment me-1" style="color: #c1a067;"></i> Your Review <span class="text-danger">*</span>
                                </label>
                                <textarea name="comment" class="form-control caldera-input @error('comment') is-invalid @enderror" 
                                          rows="5" placeholder="Tell us about your experience..." required>{{ old('comment') }}</textarea>
                                <small class="text-muted">Minimum 10 characters</small>
                                @error('comment')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        
                        <div class="d-flex gap-3 justify-content-center mt-5">
                            <a href="{{ route('branding.testimonials') }}" class="btn btn-outline-custom px-4">
                                <i class="fas fa-arrow-left me-2"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-submit px-5">
                                <i class="fas fa-paper-plane me-2"></i> Submit Review
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm mt-4 caldera-card">
                <div class="card-body p-4 text-center">
                    <i class="fas fa-quote-left fa-2x mb-2" style="color: #c1a067; opacity: 0.5;"></i>
                    <p class="mb-0 text-muted">Your feedback helps us serve you better. Thank you for being part of the Caldera family!</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap');

.display-4 {
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
    border-radius: 20px !important;
    overflow: hidden;
    transition: transform 0.25s, box-shadow 0.25s;
}

.caldera-card:hover {
    box-shadow: 0 12px 28px rgba(28,52,81,0.12) !important;
}

.caldera-input {
    border: 1.5px solid #e8e0d0;
    border-radius: 12px;
    padding: 10px 16px;
    font-size: 14px;
    transition: all 0.2s;
    background: #fff;
}

.caldera-input:focus {
    border-color: #c1a067;
    box-shadow: 0 0 0 3px rgba(193,160,103,0.1);
    outline: none;
}

.caldera-input:hover:not(:focus) {
    border-color: #c1a067;
}

.rating-input i {
    cursor: pointer;
    transition: all 0.3s ease;
    margin-right: 5px;
    color: #ddd;
}

.rating-input i:hover,
.rating-input i.active {
    color: #ffc107;
}

.rating-input i.fas {
    color: #ffc107;
}

.btn-outline-custom {
    background: white;
    color: #1c3451;
    border: 1.5px solid #1c3451;
    border-radius: 12px;
    padding: 12px 24px;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.2s;
}

.btn-outline-custom:hover {
    background: #1c3451;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(28,52,81,0.2);
}

.btn-submit {
    background: linear-gradient(135deg, #1c3451, #01516e);
    color: white;
    border: none;
    border-radius: 12px;
    padding: 12px 32px;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.2s;
}

.btn-submit:hover {
    background: linear-gradient(135deg, #c1a067, #a8894f);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(193,160,103,0.3);
}

.alert-success {
    background: #e8f5e9;
    border: none;
    border-left: 4px solid #4caf50;
    color: #2e7d32;
    border-radius: 12px;
}

.alert-danger {
    background: #ffebee;
    border: none;
    border-left: 4px solid #f44336;
    color: #c62828;
    border-radius: 12px;
}

body.dark-mode .caldera-input {
    background: #1e1e2a;
    border-color: #2d2d3a;
    color: #dce8f0;
}

body.dark-mode .caldera-input:focus {
    border-color: #c1a067;
}

body.dark-mode .btn-outline-custom {
    background: #1e1e2a;
    border-color: #c1a067;
    color: #c1a067;
}

body.dark-mode .btn-outline-custom:hover {
    background: #c1a067;
    color: #1c3451;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.rating-input i');
    const ratingInput = document.getElementById('rating');
    
    function highlightStars(value) {
        stars.forEach((star, index) => {
            if (index < value) {
                star.classList.remove('far');
                star.classList.add('fas');
            } else {
                star.classList.remove('fas');
                star.classList.add('far');
            }
        });
    }
    
    if (ratingInput.value) {
        highlightStars(parseInt(ratingInput.value));
    }
    
    stars.forEach(star => {
        star.addEventListener('mouseenter', function() {
            highlightStars(parseInt(this.dataset.value));
        });
        
        star.addEventListener('click', function() {
            const value = parseInt(this.dataset.value);
            ratingInput.value = value;
            highlightStars(value);
        });
    });
    
    document.querySelector('.rating-input').addEventListener('mouseleave', function() {
        highlightStars(parseInt(ratingInput.value) || 0);
    });
});

window.previewPhoto = function(event) {
    const input = event.target;
    const previewContainer = document.getElementById('photoPreview');
    const previewImg = document.getElementById('previewImg');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewContainer.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        previewContainer.style.display = 'none';
    }
};
</script>
@endpush
@endsection