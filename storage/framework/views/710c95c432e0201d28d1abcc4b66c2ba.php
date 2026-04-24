

<?php $__env->startSection('title', 'Menu Caldera'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold mb-3">Our Menu</h1>
        <p class="lead text-secondary">Discover our delicious selection of food and beverages</p>
    </div>
    
    <!-- Category Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-center gap-2 flex-wrap">
                <a href="<?php echo e(route('branding.menu')); ?>" class="btn <?php echo e(!request('category') ? 'btn-primary' : 'btn-outline-primary'); ?>">All</a>
                <a href="<?php echo e(route('branding.menu.category', 'makanan')); ?>" class="btn <?php echo e(request('category') == 'makanan' ? 'btn-primary' : 'btn-outline-primary'); ?>">Makanan</a>
                <a href="<?php echo e(route('branding.menu.category', 'minuman')); ?>" class="btn <?php echo e(request('category') == 'minuman' ? 'btn-primary' : 'btn-outline-primary'); ?>">Minuman</a>
                <a href="<?php echo e(route('branding.menu.category', 'dessert')); ?>" class="btn <?php echo e(request('category') == 'dessert' ? 'btn-primary' : 'btn-outline-primary'); ?>">Dessert</a>
            </div>
        </div>
    </div>
    
    <!-- Menu Grid -->
    <div class="row g-4">
        <?php $__empty_1 = true; $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <?php if($menu->image): ?>
                    <img src="<?php echo e(asset('storage/' . $menu->image)); ?>" class="card-img-top" alt="<?php echo e($menu->name); ?>" style="height: 200px; object-fit: cover;">
                <?php else: ?>
                    <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="fas fa-utensils fa-4x text-white"></i>
                    </div>
                <?php endif; ?>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="fw-bold mb-0"><?php echo e($menu->name); ?></h5>
                        <span class="text-primary fw-bold">Rp <?php echo e(number_format($menu->price, 0, ',', '.')); ?></span>
                    </div>
                    <p class="text-muted small"><?php echo e(Str::limit($menu->description, 100)); ?></p>
                    <?php if($menu->is_recommended): ?>
                        <span class="badge bg-success">Recommended</span>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-transparent border-0 pb-3">
                    <a href="<?php echo e(route('branding.menu.show', $menu)); ?>" class="btn btn-outline-primary btn-sm w-100">
                        View Details <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-12 text-center py-5">
            <i class="fas fa-utensils fa-4x text-muted mb-3"></i>
            <p class="text-muted">Menu sedang kosong</p>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Pagination -->
    <div class="mt-5">
        <?php echo e($menus->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\PA2_Kel6\resources\views/pages/menu.blade.php ENDPATH**/ ?>