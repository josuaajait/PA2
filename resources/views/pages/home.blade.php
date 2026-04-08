@extends('layouts.app')

@section('title', 'Material Kit 2 Pro - Landing Page Interaktif')

@section('content')
<!-- Hero Section dengan Parallax efek -->
<div class="page-header min-vh-100 position-relative d-flex align-items-center" 
     style="background-image: url('https://images.unsplash.com/photo-1575429198097-0414ec08e8cd?w=1600'); background-attachment: fixed; background-size: cover; background-position: center;">
    
    <!-- Dark Overlay -->
    <span class="position-absolute top-0 start-0 w-100 h-100 bg-gradient-dark opacity-6" style="z-index: 1;"></span>
    
    <div class="container position-relative" style="z-index: 2; padding-top: 80px;">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center text-white">
                <span class="badge bg-gradient-primary mb-4 px-4 py-2" style="font-size: 0.9rem; letter-spacing: 1px; display: inline-block;">
                    🏊 Family Fun & Culinary Experience 🍽️
                </span>
                <h1 class="display-3 fw-bold mb-3">Caldera Resto & Pool</h1>
                <p class="lead fs-4 mb-5 opacity-75">The perfect destination for family gathering and relaxation</p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="{{ route('reservation.table') }}" class="btn btn-lg bg-gradient-primary px-5 py-3 hover-lift" 
                       style="min-width: 180px;">
                        <i class="fas fa-calendar-alt me-2"></i>Book a Table
                    </a>
                    <a href="{{ route('reservation.ticket') }}" class="btn btn-lg btn-outline-light px-5 py-3 hover-lift" 
                       style="min-width: 180px; border-width: 2px;">
                        <i class="fas fa-ticket-alt me-2"></i>Buy Pool Ticket
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards - Sekarang terpisah dengan baik -->
<div class="container" style="margin-top: -5rem; position: relative; z-index: 10;">
    <div class="row g-4">
        @php
            $stats = [
                ['id' => 'counter1', 'value' => 1500, 'label' => 'Happy Clients', 'icon' => 'fa-smile', 'color' => 'primary'],
                ['id' => 'counter2', 'value' => 2500, 'label' => 'Projects Completed', 'icon' => 'fa-check-circle', 'color' => 'success'],
                ['id' => 'counter3', 'value' => 50, 'label' => 'Team Members', 'icon' => 'fa-users', 'color' => 'info'],
                ['id' => 'counter4', 'value' => 25, 'label' => 'Awards Won', 'icon' => 'fa-trophy', 'color' => 'warning'],
            ];
        @endphp

        @foreach($stats as $index => $stat)
            <div class="col-lg-3 col-md-6 fade-in-up" style="animation-delay: {{ $index * 0.1 }}s">
                <div class="card border-0 shadow-lg h-100 hover-lift" style="border-radius: 1rem; background: white;">
                    <div class="card-body text-center p-4">
                        <div class="stats-number" id="{{ $stat['id'] }}">0</div>
                        <p class="fw-bold mb-3">{{ $stat['label'] }}</p>
                        <div class="icon-circle bg-{{ $stat['color'] }} bg-opacity-10 mx-auto" 
                             style="width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas {{ $stat['icon'] }} text-{{ $stat['color'] }}" style="font-size: 1.8rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Main Content Section -->
<div class="container mt-5 pt-4">
    <!-- Features Section -->
    <div class="text-center mb-5">
        <h2 class="display-5 fw-bold mb-3">Why Choose Us?</h2>
        <p class="lead text-secondary mb-5">Discover what makes us different from the rest</p>
        
<div class="row g-4">
    @php
        $features = [
            [
                'icon' => 'fa-paint-brush',
                'gradient' => 'primary',
                'title' => 'Modern Design',
                'description' => 'Beautiful and intuitive interface with smooth animations and interactions.',
                'feature' => 'design'
            ],
            [
                'icon' => 'fa-code',
                'gradient' => 'success',
                'title' => 'Clean Code',
                'description' => 'Well-organized and documented code for easy customization and maintenance.',
                'feature' => 'code'
            ],
            [
                'icon' => 'fa-headset',
                'gradient' => 'info',
                'title' => '24/7 Support',
                'description' => 'Dedicated support team ready to help you with any questions or issues.',
                'feature' => 'support'
            ],
        ];
    @endphp

    @foreach($features as $index => $feature)
        <div class="col-md-4 fade-in-up" style="animation-delay: {{ $index * 0.1 }}s">
            <div class="card feature-card h-100 p-4 hover-lift border-0 shadow-sm" 
                 onclick="showFeature('{{ $feature['feature'] }}')"
                 style="border-radius: 1.5rem; cursor: pointer; background: linear-gradient(135deg, #fff, #f8f9fa); transition: all 0.3s ease; overflow: visible;">
                
                <!-- Icon di dalam card, tanpa margin-top negatif -->
                <div class="icon-circle bg-gradient-{{ $feature['gradient'] }} mx-auto mb-4"
                     style="width: 70px; height: 70px; border-radius: 20px; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease;">
                    <i class="fas {{ $feature['icon'] }} text-white" style="font-size: 2rem;"></i>
                </div>
                
                <h4 class="fw-bold mb-3 text-center" style="color: #333;">{{ $feature['title'] }}</h4>
                <p class="text-secondary mb-4 text-center" style="line-height: 1.6;">{{ $feature['description'] }}</p>
                
                <span class="text-{{ $feature['gradient'] }} fw-bold mt-auto d-flex justify-content-center align-items-center" style="gap: 0.5rem; transition: gap 0.3s ease;">
                    Learn more <i class="fas fa-arrow-right"></i>
                </span>
            </div>
        </div>
    @endforeach
</div>

<style>
/* Feature Cards */
.feature-card {
    transition: all 0.3s ease !important;
    overflow: visible !important;
    position: relative;
    padding-top: 1.5rem !important;
}

.feature-card:hover {
    transform: translateY(-10px) !important;
    box-shadow: 0 20px 30px -10px rgba(102, 126, 234, 0.3) !important;
}

.feature-card .icon-circle {
    transition: all 0.3s ease;
}

.feature-card:hover .icon-circle {
    transform: scale(1.1) rotate(5deg);
}

.feature-card:hover span {
    gap: 1rem !important;
}

/* Gradient backgrounds */
.bg-gradient-primary {
    background: linear-gradient(45deg, #667eea, #764ba2);
}

.bg-gradient-success {
    background: linear-gradient(45deg, #4caf50, #8bc34a);
}

.bg-gradient-info {
    background: linear-gradient(45deg, #00bcd4, #2196f3);
}

.text-primary { color: #667eea !important; }
.text-success { color: #4caf50 !important; }
.text-info { color: #00bcd4 !important; }

/* Dark mode support */
body.dark-mode .feature-card {
    background: linear-gradient(135deg, #2d2d2d, #353535) !important;
}

body.dark-mode .feature-card h4 {
    color: #fff !important;
}

body.dark-mode .feature-card p {
    color: #ccc !important;
}

/* Animasi fade-in-up */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.fade-in-up {
    animation: fadeInUp 0.6s ease forwards;
    opacity: 0;
}
</style>

    </div>

<!-- Testimonial Section -->
<div class="container my-5 py-4">
    <div class="text-center mb-5">
        <h2 class="display-5 fw-bold mb-3">What Our Clients Say</h2>
        <p class="lead text-secondary">Don't just take our word for it</p>
    </div>
    
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @php
                        $testimonials = [
                            [
                                'image' => 'https://randomuser.me/api/portraits/women/68.jpg',
                                'quote' => 'The support team is amazing! They helped me customize everything I needed within hours.',
                                'name' => 'Emily Rodriguez',
                                'position' => 'Marketing Director',
                                'rating' => 5
                            ],
                            [
                                'image' => 'https://randomuser.me/api/portraits/men/32.jpg',
                                'quote' => 'Incredible attention to detail and the animations are buttery smooth. Highly recommended!',
                                'name' => 'Michael Chen',
                                'position' => 'Product Designer',
                                'rating' => 5
                            ],
                            [
                                'image' => 'https://randomuser.me/api/portraits/women/44.jpg',
                                'quote' => 'This is by far the best template I\'ve ever used. The design is stunning and the code is so clean!',
                                'name' => 'Sarah Johnson',
                                'position' => 'CEO, TechStart',
                                'rating' => 5
                            ],
                        ];
                    @endphp

                    @foreach($testimonials as $index => $testimonial)
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                            <div class="card border-0 shadow-sm mx-auto" style="max-width: 700px; border-radius: 20px;">
                                <div class="card-body p-5 text-center">
                                    <!-- Avatar -->
                                    <img src="{{ $testimonial['image'] }}" 
                                         class="rounded-circle mb-4" 
                                         width="80" height="80" 
                                         alt="{{ $testimonial['name'] }}"
                                         style="object-fit: cover;">
                                    
                                    <!-- Rating -->
                                    <div class="mb-3">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star text-warning"></i>
                                        @endfor
                                    </div>
                                    
                                    <!-- Quote -->
                                    <p class="text-secondary mb-4" style="font-size: 1.1rem; line-height: 1.6;">"{{ $testimonial['quote'] }}"</p>
                                    
                                    <!-- Name -->
                                    <h6 class="fw-bold mb-0">{{ $testimonial['name'] }}</h6>
                                    <small class="text-muted">{{ $testimonial['position'] }}</small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Carousel Controls -->
                <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon bg-primary rounded-circle p-3" aria-hidden="true" style="background-size: 60%;"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon bg-primary rounded-circle p-3" aria-hidden="true" style="background-size: 60%;"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Testimonial Carousel */
#testimonialCarousel {
    position: relative;
}

#testimonialCarousel .carousel-control-prev,
#testimonialCarousel .carousel-control-next {
    width: 40px;
    height: 40px;
    top: 50%;
    transform: translateY(-50%);
    opacity: 0.7;
    transition: all 0.3s;
}

#testimonialCarousel .carousel-control-prev:hover,
#testimonialCarousel .carousel-control-next:hover {
    opacity: 1;
    transform: translateY(-50%) scale(1.1);
}

#testimonialCarousel .carousel-control-prev {
    left: -20px;
}

#testimonialCarousel .carousel-control-next {
    right: -20px;
}

#testimonialCarousel .carousel-control-prev-icon,
#testimonialCarousel .carousel-control-next-icon {
    background-color: #667eea;
    border-radius: 50%;
    padding: 12px;
}

/* Dark mode */
body.dark-mode .card {
    background: #2d2d2d;
}

body.dark-mode .text-secondary {
    color: #ccc !important;
}

body.dark-mode .text-muted {
    color: #aaa !important;
}

/* Responsive */
@media (max-width: 768px) {
    #testimonialCarousel .carousel-control-prev {
        left: -10px;
    }
    
    #testimonialCarousel .carousel-control-next {
        right: -10px;
    }
    
    .card-body.p-5 {
        padding: 1.5rem !important;
    }
}
</style>

    <!-- CTA Section -->
<div class="cta-section text-white text-center my-5 p-5" style="border-radius: 2rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <h2 class="display-5 fw-bold mb-3">Ready to Visit Caldera?</h2>
    <p class="lead mb-4 opacity-75">Experience the perfect blend of culinary delight and refreshing pool</p>
    <div class="d-flex justify-content-center gap-3 flex-wrap">
        <a href="{{ route('reservation.table') }}" class="btn btn-light btn-lg px-5 py-3 hover-lift" 
           style="border-radius: 50px; font-weight: 600;">
            <i class="fas fa-calendar-alt me-2"></i>Book a Table
        </a>
        <a href="{{ route('reservation.ticket') }}" class="btn btn-outline-light btn-lg px-5 py-3 hover-lift" 
           style="border-radius: 50px; font-weight: 600; border-width: 2px;">
            <i class="fas fa-ticket-alt me-2"></i>Buy Pool Ticket
        </a>
    </div>
</div>
</div>
@endsection

@push('styles')
<style>
/* Custom styles untuk halaman home */
.feature-card {
    transition: all 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 30px -10px rgba(102, 126, 234, 0.3) !important;
}

.icon-circle {
    transition: all 0.3s ease;
}

.feature-card:hover .icon-circle {
    transform: scale(1.1) rotate(5deg);
}

.stats-number {
    font-size: 2.5rem;
    font-weight: 700;
    background: linear-gradient(45deg, #667eea, #764ba2);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 0.5rem;
}

/* Dark mode styles */
body.dark-mode .feature-card {
    background: linear-gradient(135deg, #2d2d2d, #353535) !important;
}

body.dark-mode .feature-card h4,
body.dark-mode .feature-card p {
    color: #fff !important;
}

body.dark-mode .testimonial-card,
body.dark-mode .card.border-0.shadow-lg {
    background: #2d2d2d !important;
}

body.dark-mode .text-secondary {
    color: #aaa !important;
}

/* Responsive */
@media (max-width: 768px) {
    .page-header {
        min-height: 80vh !important;
    }
    
    .page-header h1 {
        font-size: 2.2rem;
    }
    
    .page-header .lead {
        font-size: 1.1rem;
    }
    
    .stats-number {
        font-size: 2rem;
    }
    
    .container[style*="margin-top: -5rem"] {
        margin-top: -3rem !important;
    }
}

@media (max-width: 576px) {
    .page-header {
        min-height: 70vh !important;
    }
    
    .page-header h1 {
        font-size: 1.8rem;
    }
    
    .buttons .btn {
        width: 100%;
        margin: 0.5rem 0;
    }
    
    .container[style*="margin-top: -5rem"] {
        margin-top: -2rem !important;
    }
    
    .cta-section {
        padding: 3rem 1rem !important;
    }
    
    .cta-section h2 {
        font-size: 1.8rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
    // Counter animation
    function animateCounter(elementId, target, duration = 2000) {
        const element = document.getElementById(elementId);
        if (!element) return;
        
        let start = 0;
        const increment = target / (duration / 16);
        
        const timer = setInterval(() => {
            start += increment;
            if (start >= target) {
                element.textContent = target + '+';
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(start);
            }
        }, 16);
    }

    // Start counters when page loads
    document.addEventListener('DOMContentLoaded', function() {
        animateCounter('counter1', 1500);
        animateCounter('counter2', 2500);
        animateCounter('counter3', 50);
        animateCounter('counter4', 25);
    });

    // Global notification function
    window.showNotification = function() {
        const existingNotif = document.querySelector('.notification');
        if (existingNotif) existingNotif.remove();
        
        const notification = document.createElement('div');
        notification.className = 'notification alert alert-success alert-dismissible fade show';
        notification.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            <i class="fas fa-check-circle me-2"></i>
            <strong>Success!</strong> This is a demo notification.
            <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
        `;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            if (notification.parentElement) notification.remove();
        }, 3000);
    };

    // Feature click handler
    window.showFeature = function(feature) {
        const messages = {
            design: '✨ Modern Design: 1000+ components, 5 color schemes, dark mode support',
            code: '💻 Clean Code: Best practices, well-documented, easy to customize',
            support: '🎯 24/7 Support: Email, chat, and video calls from expert team'
        };
        alert(messages[feature]);
    };

    // Parallax effect with throttle for performance
    let ticking = false;
    window.addEventListener('scroll', () => {
        if (!ticking) {
            window.requestAnimationFrame(() => {
                const scrolled = window.pageYOffset;
                const parallax = document.querySelector('.page-header');
                if (parallax) {
                    parallax.style.backgroundPositionY = `${scrolled * 0.5}px`;
                }
                ticking = false;
            });
            ticking = true;
        }
    });
</script>
@endpush