

<?php $__env->startSection('title', 'Gallery'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold mb-3">Our Gallery</h1>
        <p class="lead text-muted">Moments captured at Caldera Resto & Pool</p>
    </div>
    
    <!-- Featured Image -->
    <?php if($featured): ?>
    <div class="row mb-5">
        <div class="col-12">
            <div class="position-relative overflow-hidden rounded-4 shadow-lg">
                <img src="<?php echo e(asset('storage/' . $featured->file_path)); ?>" 
                     class="w-100" 
                     alt="<?php echo e($featured->title); ?>"
                     style="height: 400px; object-fit: cover;">
                <div class="position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-50 text-white p-3">
                    <h4 class="mb-0"><?php echo e($featured->title); ?></h4>
                    <p class="mb-0 small"><?php echo e($featured->description); ?></p>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Filter Categories -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-center gap-2 flex-wrap">
                <a href="<?php echo e(route('branding.gallery')); ?>" 
                   class="btn <?php echo e(!request('category') ? 'btn-primary' : 'btn-outline-primary'); ?>">
                    All
                </a>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('branding.gallery.category', $cat)); ?>" 
                   class="btn <?php echo e(request('category') == $cat ? 'btn-primary' : 'btn-outline-primary'); ?>">
                    <?php echo e(ucfirst($cat)); ?>

                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
    
    <!-- Gallery Grid -->
    <div class="row g-4">
        <?php $__empty_1 = true; $__currentLoopData = $galleries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gallery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="card border-0 shadow-sm h-100 gallery-card">
                <div class="position-relative overflow-hidden" style="height: 250px;">
                    <img src="<?php echo e(asset('storage/' . $gallery->file_path)); ?>" 
                         class="card-img-top gallery-img" 
                         alt="<?php echo e($gallery->title); ?>"
                         style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;">
                    <div class="gallery-overlay position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-flex align-items-center justify-content-center opacity-0 transition">
                        <a href="<?php echo e(route('branding.gallery.show', $gallery)); ?>" class="btn btn-light rounded-circle mx-1">
                            <i class="fas fa-search"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body text-center p-3">
                    <h6 class="fw-bold mb-1"><?php echo e($gallery->title); ?></h6>
                    <small class="text-muted"><?php echo e(ucfirst($gallery->category)); ?></small>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-12 text-center py-5">
            <i class="fas fa-images fa-4x text-muted mb-3"></i>
            <p class="text-muted">Belum ada foto gallery</p>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Pagination -->
    <div class="mt-5">
        <?php echo e($galleries->links()); ?>

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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\PA2_Kel6\resources\views/pages/gallery.blade.php ENDPATH**/ ?>