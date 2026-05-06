

<?php $__env->startSection('title', 'Notifikasi'); ?>

<?php $__env->startSection('content'); ?>
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Daftar Notifikasi</h6>
        <form action="<?php echo e(route('admin.notifications.mark-all-read')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-check-double me-1"></i> Tandai semua dibaca
            </button>
        </form>
    </div>
    <div class="card-body p-0">
        <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notif): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="border-bottom p-3 <?php echo e($notif->read_at ? '' : 'bg-light'); ?>">
            <div class="d-flex">
                <div class="me-3">
                    <i class="fas <?php echo e($notif->data['icon'] ?? 'fa-bell'); ?> fa-2x text-<?php echo e($notif->data['color'] ?? 'primary'); ?>"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-bold"><?php echo e($notif->data['title']); ?></div>
                    <div class="text-muted"><?php echo e($notif->data['body']); ?></div>
                    <div class="small text-muted mt-1"><?php echo e($notif->created_at->diffForHumans()); ?></div>
                </div>
                <?php if(!$notif->read_at): ?>
                <div>
                    <form action="<?php echo e(route('admin.notifications.mark-read', $notif->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-sm btn-link">
                            <i class="fas fa-check-circle text-success"></i>
                        </button>
                    </form>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="text-center py-5">
            <i class="fas fa-bell-slash fa-4x text-muted mb-3"></i>
            <p class="text-muted">Belum ada notifikasi</p>
        </div>
        <?php endif; ?>
    </div>
    <div class="card-footer bg-transparent">
        <?php echo e($notifications->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\PA2_Kel6\resources\views/admin/notifications/index.blade.php ENDPATH**/ ?>