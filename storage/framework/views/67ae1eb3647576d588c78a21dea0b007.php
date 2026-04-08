

<?php $__env->startSection('title', 'Gallery'); ?>

<?php $__env->startSection('content'); ?>
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Daftar Gallery</h6>
        <div>
            <a href="<?php echo e(route('admin.galleries.create')); ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Tambah Gallery
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-4">
            <?php $__empty_1 = true; $__currentLoopData = $galleries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gallery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-md-3">
                <div class="card h-100 shadow-sm">
                    <?php if($gallery->type == 'image'): ?>
                        <img src="<?php echo e($gallery->image_url); ?>" class="card-img-top" style="height: 200px; object-fit: cover;" onerror="this.src='https://placehold.co/400x300?text=No+Image'">
                    <?php else: ?>
                        <video class="card-img-top" style="height: 200px; object-fit: cover;" controls>
                            <source src="<?php echo e($gallery->image_url); ?>" type="video/mp4">
                        </video>
                    <?php endif; ?>
                    <div class="card-body">
                        <h6 class="fw-bold"><?php echo e($gallery->title); ?></h6>
                        <p class="small text-muted">Kategori: <?php echo e($gallery->category); ?></p>
                        <?php if($gallery->is_featured): ?>
                            <span class="badge bg-primary">Featured</span>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer bg-transparent">
                        <a href="<?php echo e(route('admin.galleries.edit', $gallery)); ?>" class="btn btn-sm btn-info">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="<?php echo e(route('admin.galleries.destroy', $gallery)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus gallery ini?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        <button onclick="toggleFeatured(<?php echo e($gallery->id); ?>)" class="btn btn-sm <?php echo e($gallery->is_featured ? 'btn-warning' : 'btn-success'); ?>">
                            <i class="fas fa-star"></i>
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12 text-center py-5">
                <i class="fas fa-images fa-4x text-muted mb-3"></i>
                <p>Belum ada data gallery</p>
                <a href="<?php echo e(route('admin.galleries.create')); ?>" class="btn btn-primary">Tambah Gallery</a>
            </div>
            <?php endif; ?>
        </div>
        <?php echo e($galleries->links()); ?>

    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    function toggleFeatured(id) {
        fetch(`/admin/galleries/${id}/toggle-featured`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Content-Type': 'application/json'
            }
        }).then(response => response.json()).then(data => {
            if(data.success) location.reload();
        });
    }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\PA2_Kel6\resources\views/admin/galleries/index.blade.php ENDPATH**/ ?>