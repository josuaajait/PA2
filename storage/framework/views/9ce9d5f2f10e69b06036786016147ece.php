

<?php $__env->startSection('title', 'Promos - Caldera Resto & Pool'); ?>


<?php $__env->startSection('content'); ?>
    <?php
    // Debug: tampilkan path yang dihasilkan
    if (isset($promo->banner_image) && $promo->banner_image) {
        $debugUrl = asset('storage/' . $promo->banner_image);
        echo "<!-- Debug: " . $debugUrl . " -->";
    }
    ?>

<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold mb-2" style="font-family: 'Playfair Display', serif; color: #1c3451;">Special Promos</h1>
        <div class="section-divider"></div>
        <p class="lead text-muted">Get exciting discounts and offers</p>
    </div>

    <?php if(session('error')): ?>
        <div class="alert alert-warning text-center">
            <i class="fas fa-exclamation-triangle me-2"></i> <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <div class="row g-4">
        <?php $__empty_1 = true; $__currentLoopData = $promos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $promo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php
            // Pastikan $promo bisa diakses sebagai object
            if (is_array($promo)) {
                $promo = (object) $promo;
            }
            

            // 🔥 PATH GAMBAR dari microservice
            $imageUrl = null;
            if (isset($promo->banner_image) && $promo->banner_image) {
                // Path dari microservice: "promos/namafile.jpg"
                $imageUrl = asset('storage/' . $promo->banner_image);
            }
            
            $endDate = isset($promo->end_date) ? \Carbon\Carbon::parse($promo->end_date)->setTimezone('Asia/Jakarta') : null;
        ?>
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 promo-card">
                <?php if($imageUrl): ?>
                    <img src="<?php echo e($imageUrl); ?>" 
                         class="card-img-top" 
                         alt="<?php echo e($promo->title ?? 'Promo'); ?>" 
                         style="height: 200px; object-fit: cover;"
                         onerror="this.onerror=null; this.src='https://placehold.co/400x200/e8e0d0/c1a067?text=No+Image';">
                <?php else: ?>
                    <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 200px; flex-direction: column;">
                        <i class="fas fa-tag fa-3x" style="color: #c1a067;"></i>
                        <code class="mt-2" style="background: transparent; color: #c1a067; font-size: 14px;">
                            <?php echo e($promo->promo_code ?? 'PROMO'); ?>

                        </code>
                    </div>
                <?php endif; ?>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge-promo">
                            <?php if(isset($promo->promo_type) && $promo->promo_type == 'ticket'): ?>
                                <i class="fas fa-ticket-alt me-1"></i> Tiket
                            <?php elseif(isset($promo->promo_type) && $promo->promo_type == 'menu'): ?>
                                <i class="fas fa-utensils me-1"></i> Menu
                            <?php elseif(isset($promo->promo_type) && $promo->promo_type == 'event'): ?>
                                <i class="fas fa-calendar-alt me-1"></i> Event
                            <?php else: ?>
                                Promo
                            <?php endif; ?>
                        </span>
                        <?php if($endDate): ?>
                        <small class="text-muted">
                            <i class="far fa-clock me-1"></i>
                            Berakhir: <?php echo e($endDate->format('d M Y')); ?>

                        </small>
                        <?php endif; ?>
                    </div>
                    <h5 class="fw-bold mb-2" style="color: #1c3451;"><?php echo e($promo->title ?? 'N/A'); ?></h5>
                    <p class="text-muted small"><?php echo e(Str::limit($promo->description ?? '', 100)); ?></p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="discount-badge">
                            <?php if(isset($promo->discount_type) && $promo->discount_type == 'percentage'): ?>
                                <span class="discount-percent"><?php echo e($promo->discount_value ?? 0); ?>% OFF</span>
                            <?php else: ?>
                                <span class="discount-amount">Rp <?php echo e(number_format($promo->discount_value ?? 0, 0, ',', '.')); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="promo-code">
                            <code class="px-2 py-1 rounded" style="background: #f0ebe0; color: #c1a067;">
                                <?php echo e($promo->promo_code ?? '-'); ?>

                            </code>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pb-3">
                    <a href="<?php echo e(route('branding.promos.detail', $promo->slug ?? '#')); ?>" class="btn btn-promo w-100">
                        View Details <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-12 text-center py-5">
            <div class="empty-state">
                <i class="fas fa-tags fa-4x mb-3" style="color: #c1a067; opacity: 0.4;"></i>
                <h5 style="color: #1c3451;">No Active Promos</h5>
                <p class="text-muted">Check back later for exciting discounts!</p>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="mt-5">
        <?php echo e($promos->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .section-divider {
        width: 60px;
        height: 3px;
        background: #c1a067;
        margin: 12px auto;
        border-radius: 2px;
    }
    .promo-card {
        border-radius: 20px !important;
        overflow: hidden;
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .promo-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 28px rgba(28,52,81,0.12) !important;
    }
    .badge-promo {
        background: rgba(193,160,103,0.15);
        color: #c1a067;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    .discount-badge {
        background: linear-gradient(135deg, #1c3451, #01516e);
        padding: 6px 12px;
        border-radius: 25px;
    }
    .discount-percent, .discount-amount {
        color: white;
        font-weight: bold;
        font-size: 14px;
    }
    .btn-promo {
        background: white;
        color: #1c3451;
        border: 1.5px solid #1c3451;
        border-radius: 12px;
        padding: 10px;
        font-weight: 600;
        transition: all 0.2s;
    }
    .btn-promo:hover {
        background: #1c3451;
        color: white;
        transform: translateY(-2px);
    }
    code {
        font-size: 12px;
        font-weight: 600;
    }
    body.dark-mode .promo-card {
        background: #1e1e2a;
    }
    body.dark-mode .btn-promo {
        background: #1e1e2a;
        border-color: #c1a067;
        color: #c1a067;
    }
    body.dark-mode .btn-promo:hover {
        background: #c1a067;
        color: #1c3451;
    }
    body.dark-mode .bg-light {
        background-color: #2d2d3a !important;
    }
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\PA2_Kel6\resources\views/pages/promos.blade.php ENDPATH**/ ?>