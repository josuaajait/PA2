

<?php $__env->startSection('title', 'Gallery'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .report-page-header {
        background: linear-gradient(135deg, #1c3451 0%, #2a4a6b 100%);
        border-radius: 16px;
        padding: 24px 28px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .report-page-header h6 {
        color: #fff;
        font-size: 18px;
        font-weight: 700;
        margin: 0;
    }

    .report-page-header .header-icon {
        width: 44px;
        height: 44px;
        background: rgba(193,160,103,0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 14px;
    }

    .report-page-header .header-icon i {
        color: #c1a067;
        font-size: 18px;
    }

    .btn-add {
        background: #c1a067;
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        padding: 8px 18px;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-add:hover {
        background: #a98750;
        color: #fff;
        transform: translateY(-1px);
    }

    .report-main-card {
        border: none;
        border-radius: 16px;
        border-top: 3px solid #c1a067 !important;
        box-shadow: 0 4px 20px rgba(28,52,81,0.08);
    }

    .report-main-card .card-body {
        padding: 24px;
    }

    /* Gallery Cards */
    .gallery-card {
        border: none;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(28,52,81,0.08);
        transition: transform 0.2s, box-shadow 0.2s;
        border-top: 3px solid transparent;
    }

    .gallery-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(28,52,81,0.14);
        border-top-color: #c1a067;
    }

    .gallery-card .card-body {
        padding: 14px 16px 10px;
    }

    .gallery-card .card-body h6 {
        font-size: 13px;
        font-weight: 700;
        color: #1c3451;
        margin-bottom: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .gallery-card .card-body p {
        font-size: 12px;
        color: #9ca3af;
        margin-bottom: 6px;
    }

    .gallery-card .card-footer {
        background: #f8fafc;
        border-top: 1px solid #f0f0f0;
        padding: 10px 16px;
        display: flex;
        gap: 6px;
    }

    .btn-edit {
        background: #1c3451;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 5px 10px;
        font-size: 12px;
        transition: all 0.2s;
    }

    .btn-edit:hover { background: #c1a067; color: #fff; }

    .btn-delete {
        background: #dc2626;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 5px 10px;
        font-size: 12px;
        transition: all 0.2s;
    }

    .btn-delete:hover { background: #b91c1c; color: #fff; }

    .btn-star-on {
        background: #d97706;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 5px 10px;
        font-size: 12px;
        transition: all 0.2s;
        margin-left: auto;
    }

    .btn-star-off {
        background: #15803d;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 5px 10px;
        font-size: 12px;
        transition: all 0.2s;
        margin-left: auto;
    }

    .btn-star-on:hover, .btn-star-off:hover { opacity: 0.85; }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #9ca3af;
    }

    .empty-state i { color: #dde2e8; margin-bottom: 16px; display: block; }
    .empty-state p { font-size: 14px; margin-bottom: 16px; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<div class="report-page-header">
    <div class="d-flex align-items-center">
        <div class="header-icon">
            <i class="fas fa-images"></i>
        </div>
        <h6>Daftar Gallery</h6>
    </div>
    <a href="<?php echo e(route('admin.galleries.create')); ?>" class="btn-add">
        <i class="fas fa-plus"></i> Tambah Gallery
    </a>
</div>

<div class="card report-main-card">
    <div class="card-body">
        <div class="row g-4">
            <?php $__empty_1 = true; $__currentLoopData = $galleries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gallery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-md-3">
                <div class="card gallery-card h-100">
                    <?php if($gallery->type == 'image'): ?>
                        <img src="<?php echo e($gallery->image_url); ?>" class="card-img-top" style="height: 180px; object-fit: cover;" onerror="this.src='https://placehold.co/400x300?text=No+Image'">
                    <?php else: ?>
                        <video class="card-img-top" style="height: 180px; object-fit: cover;" controls>
                            <source src="<?php echo e($gallery->image_url); ?>" type="video/mp4">
                        </video>
                    <?php endif; ?>
                    <div class="card-body">
                        <h6><?php echo e($gallery->title); ?></h6>
                        <p>Kategori: <?php echo e($gallery->category); ?></p>
                        <?php if($gallery->is_featured): ?>
                            <span class="badge" style="background:#c1a067; font-size:11px;">
                                <i class="fas fa-star me-1"></i> Featured
                            </span>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer">
                        <a href="<?php echo e(route('admin.galleries.edit', $gallery)); ?>" class="btn btn-edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="<?php echo e(route('admin.galleries.destroy', $gallery)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-delete" onclick="return confirm('Yakin hapus gallery ini?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        <button onclick="toggleFeatured(<?php echo e($gallery->id); ?>)" class="btn <?php echo e($gallery->is_featured ? 'btn-star-on' : 'btn-star-off'); ?>">
                            <i class="fas fa-star"></i>
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12">
                <div class="empty-state">
                    <i class="fas fa-images fa-4x"></i>
                    <p>Belum ada data gallery</p>
                    <a href="<?php echo e(route('admin.galleries.create')); ?>" class="btn-add">
                        <i class="fas fa-plus"></i> Tambah Gallery
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <div class="mt-4">
            <?php echo e($galleries->links()); ?>

        </div>
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