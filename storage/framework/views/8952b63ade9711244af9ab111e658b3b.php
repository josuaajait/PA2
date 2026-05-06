

<?php $__env->startSection('title', 'Caldera Resto & Pool'); ?>

<?php $__env->startSection('content'); ?>

<!-- Intro Section -->
<div class="container mt-5">
    <div class="row align-items-center">
        <div class="col-md-5 mb-4 mb-md-0">
            <div class="position-relative">
                <img src="https://images.unsplash.com/photo-1575429198097-0414ec08e8cd?w=800"
                     class="img-fluid rounded-4 shadow-lg" alt="Pool View"
                     style="border: 4px solid #e8e0d0;">
                <div class="carousel-dots-overlay">
                    <span></span><span class="active"></span><span></span>
                </div>
            </div>
        </div>
        <div class="col-md-7 ps-md-5">
            <h2 class="display-6 mb-4 text-center text-md-start"
                style="font-family: 'Playfair Display', serif; color: #1c3451;">
                CALDERA RESTO <br> & POOL
            </h2>
            <p class="lead text-secondary" style="line-height: 1.8; font-size: 1.1rem;">
                Caldera Resto and Pool menghadirkan suasana yang tenang dan nyaman, cocok untuk bersantai bersama keluarga maupun teman. Dengan konsep restoran dan kolam renang dalam satu lokasi, kami menawarkan pengalaman rekreasi yang lengkap dalam satu tempat.
            </p>
            <div class="mt-4 d-flex gap-3">
                <a href="<?php echo e(route('reservation.table')); ?>" class="btn btn-home-primary px-4 py-2">Book Table</a>
                <a href="<?php echo e(route('reservation.ticket')); ?>" class="btn btn-home-outline px-4 py-2">Buy Ticket</a>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="container mt-5">
    <div class="row g-4">
        <?php
            $stats = [
                ['id' => 'counter1', 'value' => 1500, 'label' => 'Happy Clients', 'icon' => 'fa-smile'],
                ['id' => 'counter2', 'value' => 2500, 'label' => 'Projects Completed', 'icon' => 'fa-check-circle'],
                ['id' => 'counter3', 'value' => 50, 'label' => 'Team Members', 'icon' => 'fa-users'],
                ['id' => 'counter4', 'value' => 25, 'label' => 'Awards Won', 'icon' => 'fa-trophy'],
            ];
        ?>

        <?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 text-center p-4 rounded-4 stat-card">
                    <i class="fas <?php echo e($stat['icon']); ?> fa-2x mb-3" style="color: #c1a067;"></i>
                    <div class="stats-number mb-1" id="<?php echo e($stat['id']); ?>" style="color: #1c3451;">0</div>
                    <p class="fw-bold small text-uppercase mb-0" style="color: #6b7280; letter-spacing: 0.5px;"><?php echo e($stat['label']); ?></p>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>

<!-- Features Section -->
<div class="container mt-5 pt-4">
    <div class="text-center mb-5">
        <h2 class="fw-bold" style="font-family: 'Playfair Display', serif; color: #1c3451;">Why Choose Us?</h2>
        <div style="width: 50px; height: 3px; background: #c1a067; margin: 12px auto 0; border-radius: 2px;"></div>
    </div>
    <div class="row g-4">
        <?php
            $features = [
                ['icon' => 'fa-paint-brush', 'title' => 'Modern Design', 'description' => 'Beautiful and intuitive interface with smooth animations.'],
                ['icon' => 'fa-code', 'title' => 'Clean Code', 'description' => 'Well-organized and documented code for easy maintenance.'],
                ['icon' => 'fa-headset', 'title' => '24/7 Support', 'description' => 'Dedicated support team ready to help you with any issues.'],
            ];
        ?>
        <?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm p-4 text-center rounded-4 feature-card">
                    <div class="feature-icon-wrap mx-auto mb-3">
                        <i class="fas <?php echo e($feature['icon']); ?> fa-2x" style="color: #c1a067;"></i>
                    </div>
                    <h5 class="fw-bold" style="color: #1c3451;"><?php echo e($feature['title']); ?></h5>
                    <p class="text-secondary small mb-0"><?php echo e($feature['description']); ?></p>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>

<!-- Testimonial Section -->
<div class="container my-5 py-5 testimonial-section rounded-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold" style="font-family: 'Playfair Display', serif; color: #1c3451;">What Our Clients Say</h2>
        <div style="width: 50px; height: 3px; background: #c1a067; margin: 12px auto 0; border-radius: 2px;"></div>
    </div>
    <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php $__currentLoopData = [
                ['image' => 'https://randomuser.me/api/portraits/women/68.jpg', 'name' => 'Emily Rodriguez', 'quote' => 'The support team is amazing!'],
                ['image' => 'https://randomuser.me/api/portraits/men/32.jpg', 'name' => 'Michael Chen', 'quote' => 'Incredible attention to detail!']
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $testimonial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="carousel-item <?php echo e($index === 0 ? 'active' : ''); ?>">
                    <div class="text-center px-lg-5">
                        <i class="fas fa-quote-left fa-2x mb-3" style="color: #c1a067; opacity: 0.5;"></i>
                        <img src="<?php echo e($testimonial['image']); ?>" class="rounded-circle mb-3"
                             width="70" height="70"
                             style="object-fit: cover; border: 3px solid #c1a067;">
                        <p class="fst-italic text-secondary fs-5">"<?php echo e($testimonial['quote']); ?>"</p>
                        <h6 class="fw-bold" style="color: #1c3451;"><?php echo e($testimonial['name']); ?></h6>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" style="filter: invert(1);"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" style="filter: invert(1);"></span>
        </button>
    </div>
</div>

<!-- CTA Section -->
<div class="container mb-5">
    <div class="p-5 text-center text-white rounded-5" style="background: linear-gradient(135deg, #1c3451 0%, #01516e 100%);">
        <h2 class="fw-bold mb-3" style="font-family: 'Playfair Display', serif;">Ready to Visit Caldera?</h2>
        <p class="mb-4 opacity-75">Experience the perfect blend of culinary delight and refreshing pool</p>
        <a href="<?php echo e(route('reservation.table')); ?>" class="btn px-5 py-3 fw-bold rounded-pill"
           style="background: #c1a067; border: none; color: white; transition: all 0.2s;">
            Book Now
        </a>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap');

    body { background-color: #ffffff; }

    .btn-home-primary {
        background-color: #1c3451;
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-home-primary:hover {
        background-color: #c1a067;
        color: white;
        transform: translateY(-2px);
    }

    .btn-home-outline {
        border: 2px solid #1c3451;
        color: #1c3451;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-home-outline:hover {
        background-color: #1c3451;
        color: white;
        transform: translateY(-2px);
    }

    .carousel-dots-overlay {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 10px;
    }

    .carousel-dots-overlay span {
        width: 10px;
        height: 10px;
        border: 2px solid white;
        border-radius: 50%;
    }

    .carousel-dots-overlay span.active {
        background: white;
    }

    /* Stats */
    .stat-card {
        background: #fdfcfa;
        border-top: 3px solid #c1a067 !important;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(28,52,81,0.1) !important;
    }

    .stats-number {
        font-size: 2rem;
        font-weight: 700;
        font-family: 'Playfair Display', serif;
    }

    /* Features */
    .feature-card {
        transition: transform 0.2s, box-shadow 0.2s;
        border-top: 3px solid transparent !important;
    }

    .feature-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(28,52,81,0.1) !important;
        border-top: 3px solid #c1a067 !important;
    }

    .feature-icon-wrap {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #f0ebe0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Testimonial */
    .testimonial-section {
        background: #fdfcfa;
        border: 1px solid #f0ebe0;
    }

    .fade-in-up { opacity: 1; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
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

    document.addEventListener('DOMContentLoaded', function() {
        animateCounter('counter1', 1500);
        animateCounter('counter2', 2500);
        animateCounter('counter3', 50);
        animateCounter('counter4', 25);
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\PA2_Kel6\resources\views/pages/home.blade.php ENDPATH**/ ?>