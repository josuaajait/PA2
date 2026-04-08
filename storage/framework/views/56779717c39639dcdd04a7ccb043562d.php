

<?php $__env->startSection('title', 'Promo Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Daftar Promo</h6>
        <a href="<?php echo e(route('admin.promos.create')); ?>" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Tambah Promo
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Judul</th>
                        <th>Kode Promo</th>
                        <th>Diskon</th>
                        <th>Periode</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $promos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $promo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <img src="<?php echo e(asset('storage/' . $promo->banner_image)); ?>" width="60" style="border-radius: 5px;">
                        </td>
                        <td class="fw-bold"><?php echo e($promo->title); ?></td>
                        <td><span class="badge bg-info"><?php echo e($promo->promo_code); ?></span></td>
                        <td>
                            <?php if($promo->discount_type == 'percentage'): ?>
                                <?php echo e($promo->discount_value); ?>%
                            <?php else: ?>
                                Rp <?php echo e(number_format($promo->discount_value, 0, ',', '.')); ?>

                            <?php endif; ?>
                        </td>
                        <td>
                            <small><?php echo e(\Carbon\Carbon::parse($promo->start_date)->format('d M Y')); ?><br>
                            s/d <?php echo e(\Carbon\Carbon::parse($promo->end_date)->format('d M Y')); ?></small>
                        </td>
                        <td>
                            <?php if($promo->is_active && now()->between($promo->start_date, $promo->end_date)): ?>
                                <span class="badge bg-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Tidak Aktif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo e(route('admin.promos.edit', $promo)); ?>" class="btn btn-sm btn-info">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="<?php echo e(route('admin.promos.destroy', $promo)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus promo ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center">Belum ada data promo</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php echo e($promos->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\PA2_Kel6\resources\views/admin/promos/index.blade.php ENDPATH**/ ?>