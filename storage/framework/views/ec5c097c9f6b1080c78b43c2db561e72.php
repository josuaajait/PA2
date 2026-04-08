

<?php $__env->startSection('title', 'Menu Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Daftar Menu</h6>
        <a href="<?php echo e(route('admin.menus.create')); ?>" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Tambah Menu
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th>Rekomendasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <?php if($menu->image): ?>
                                <img src="<?php echo e(asset('storage/' . $menu->image)); ?>" width="50" height="50" style="object-fit: cover; border-radius: 5px;">
                            <?php else: ?>
                                <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; border-radius: 5px;">
                                    <i class="fas fa-utensils"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="fw-bold"><?php echo e($menu->name); ?></td>
                        <td><?php echo e($menu->category); ?></td>
                        <td>Rp <?php echo e(number_format($menu->price, 0, ',', '.')); ?></td>
                        <td><?php echo $menu->status_label; ?></td>
                        <td>
                            <?php if($menu->is_recommended): ?>
                                <span class="badge bg-success">Ya</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Tidak</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <!-- Tombol Show (View) -->
                            <a href="<?php echo e(route('admin.menus.show', $menu)); ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                            <!-- Tombol Edit -->
                            <a href="<?php echo e(route('admin.menus.edit', $menu)); ?>" class="btn btn-sm btn-info">
                                <i class="fas fa-edit"></i>
                            </a>
                            <!-- Tombol Delete -->
                            <form action="<?php echo e(route('admin.menus.destroy', $menu)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus menu ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            <!-- Tombol Toggle Availability -->
                            <button onclick="toggleAvailability(<?php echo e($menu->id); ?>)" class="btn btn-sm <?php echo e($menu->is_available ? 'btn-warning' : 'btn-success'); ?>">
                                <i class="fas <?php echo e($menu->is_available ? 'fa-times' : 'fa-check'); ?>"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center">Belum ada data menu</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php echo e($menus->links()); ?>

    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    function toggleAvailability(id) {
        fetch(`/admin/menus/${id}/toggle-availability`, {
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
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\PA2_Kel6\resources\views/admin/menus/index.blade.php ENDPATH**/ ?>