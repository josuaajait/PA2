

<?php $__env->startSection('title', 'Testimonials'); ?>

<?php $__env->startSection('content'); ?>
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
                <div class="display-1 fw-bold text-primary"><?php echo e(number_format($ratingStats['average'], 1)); ?></div>
                <div class="mb-2">
                    <?php for($i = 1; $i <= 5; $i++): ?>
                        <?php if($i <= floor($ratingStats['average'])): ?>
                            <i class="fas fa-star text-warning fa-lg"></i>
                        <?php elseif($i - $ratingStats['average'] <= 0.5): ?>
                            <i class="fas fa-star-half-alt text-warning fa-lg"></i>
                        <?php else: ?>
                            <i class="far fa-star text-warning fa-lg"></i>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
                <p class="text-muted">Average Rating</p>
                <p class="mb-0">Based on <strong><?php echo e($ratingStats['total']); ?></strong> reviews</p>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card border-0 shadow-sm p-4 h-100">
                <div class="row">
                    <div class="col-6">
                        <?php $__currentLoopData = [5,4,3]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $star): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="d-flex align-items-center mb-2">
                            <div class="me-2" style="width: 50px;">
                                <span class="fw-bold"><?php echo e($star); ?> Star</span>
                            </div>
                            <div class="progress flex-grow-1" style="height: 10px;">
                                <?php
                                    $percent = $ratingStats['total'] > 0 ? ($ratingStats[$star . '_star'] / $ratingStats['total']) * 100 : 0;
                                ?>
                                <div class="progress-bar bg-warning" style="width: <?php echo e($percent); ?>%"></div>
                            </div>
                            <div class="ms-2" style="width: 40px;">
                                <span class="text-muted small"><?php echo e($ratingStats[$star . '_star']); ?></span>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div class="col-6">
                        <div class="d-flex flex-column justify-content-between h-100">
                            <a href="<?php echo e(route('branding.testimonials.create')); ?>" class="btn btn-primary w-100 mb-3">
                                <i class="fas fa-star me-2"></i>Write a Review
                            </a>
                            <div class="dropdown w-100">
                                <button class="btn btn-outline-secondary w-100 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-filter me-2"></i>Filter Reviews
                                </button>
                                <ul class="dropdown-menu w-100">
                                    <li><a class="dropdown-item" href="<?php echo e(route('branding.testimonials')); ?>">All Reviews</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="<?php echo e(route('branding.testimonials', ['rating' => 5])); ?>">5 Star Only</a></li>
                                    <li><a class="dropdown-item" href="<?php echo e(route('branding.testimonials', ['rating' => 4])); ?>">4 Star & Up</a></li>
                                    <li><a class="dropdown-item" href="<?php echo e(route('branding.testimonials', ['rating' => 3])); ?>">3 Star & Up</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="<?php echo e(route('branding.testimonials', ['service' => 'restaurant'])); ?>">Restaurant</a></li>
                                    <li><a class="dropdown-item" href="<?php echo e(route('branding.testimonials', ['service' => 'pool'])); ?>">Pool</a></li>
                                    <li><a class="dropdown-item" href="<?php echo e(route('branding.testimonials', ['service' => 'event'])); ?>">Event</a></li>
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
        <?php $__empty_1 = true; $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $testimonial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 testimonial-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <?php if($testimonial->customer_photo): ?>
                            <img src="<?php echo e(asset('storage/' . $testimonial->customer_photo)); ?>" 
                                 class="rounded-circle me-3" 
                                 width="60" height="60" 
                                 alt="<?php echo e($testimonial->customer_name); ?>">
                        <?php else: ?>
                            <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($testimonial->customer_name)); ?>&background=667eea&color=fff&size=60" 
                                 class="rounded-circle me-3" 
                                 width="60" height="60" 
                                 alt="<?php echo e($testimonial->customer_name); ?>">
                        <?php endif; ?>
                        <div>
                            <h6 class="fw-bold mb-0"><?php echo e($testimonial->customer_name); ?></h6>
                            <small class="text-muted">
                                <?php if($testimonial->service_type): ?>
                                    <?php echo e(ucfirst($testimonial->service_type)); ?> Visitor
                                <?php else: ?>
                                    Customer
                                <?php endif; ?>
                            </small>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <?php if($i <= $testimonial->rating): ?>
                                <i class="fas fa-star text-warning"></i>
                            <?php else: ?>
                                <i class="far fa-star text-warning"></i>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                    
                    <p class="text-muted mb-3 fst-italic">"<?php echo e(\Illuminate\Support\Str::limit($testimonial->comment, 150)); ?>"</p>
                    
                    <?php if($testimonial->visit_date): ?>
                        <small class="text-muted">
                            <i class="fas fa-calendar-alt me-1"></i> 
                            Visited: <?php echo e(\Carbon\Carbon::parse($testimonial->visit_date)->format('d M Y')); ?>

                        </small>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-12 text-center py-5">
            <i class="fas fa-star fa-4x text-muted mb-3"></i>
            <h5>No testimonials yet</h5>
            <p class="text-muted">Be the first to write a review!</p>
            <a href="<?php echo e(route('branding.testimonials.create')); ?>" class="btn btn-primary">Write a Review</a>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Pagination -->
    <div class="mt-5">
        <?php echo e($testimonials->links()); ?>

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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\PA2_Kel6\resources\views/pages/testimonials.blade.php ENDPATH**/ ?>