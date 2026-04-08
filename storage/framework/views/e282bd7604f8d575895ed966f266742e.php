

<?php $__env->startSection('title', 'Detail Menu'); ?>

<?php $__env->startSection('content'); ?>
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Detail Menu: <?php echo e($menu->name); ?></h6>
        <div>
            <a href="<?php echo e(route('admin.menus.edit', $menu)); ?>" class="btn btn-info btn-sm">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="<?php echo e(route('admin.menus.index')); ?>" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 text-center">
                <?php if($menu->image): ?>
                    <img src="<?php echo e(asset('storage/' . $menu->image)); ?>" class="img-fluid rounded" style="max-width: 100%;">
                <?php else: ?>
                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px; border-radius: 10px;">
                        <i class="fas fa-utensils fa-4x"></i>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-md-8">
                <table class="table table-borderless">
                    <tr>
                        <th style="width: 150px;">Nama Menu</th>
                        <td><?php echo e($menu->name); ?></td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td><?php echo e($menu->category); ?></td>
                    </tr>
                    <tr>
                        <th>Harga</th>
                        <td>Rp <?php echo e(number_format($menu->price, 0, ',', '.')); ?></td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td><?php echo e($menu->description); ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td><?php echo $menu->status_label; ?></td>
                    </tr>
                    <tr>
                        <th>Rekomendasi</th>
                        <td>
                            <?php if($menu->is_recommended): ?>
                                <span class="badge bg-success">Ya</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Tidak</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Dibuat Pada</th>
                        <td><?php echo e($menu->created_at->format('d M Y H:i')); ?></td>
                    </tr>
                    <tr>
                        <th>Terakhir Update</th>
                        <td><?php echo e($menu->updated_at->format('d M Y H:i')); ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\PA2_Kel6\resources\views/admin/menus/show.blade.php ENDPATH**/ ?>