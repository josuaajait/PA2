

<?php $__env->startSection('title', 'Pool Info'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold mb-3">Caldera Pool</h1>
        <p class="lead text-muted">Enjoy our refreshing and family-friendly swimming pool</p>
    </div>

    <div class="row align-items-center mb-5">
        <div class="col-lg-6 mb-4 mb-lg-0">
            <img src="https://images.unsplash.com/photo-1575429198097-0414ec08e8cd?w=600" class="img-fluid rounded-4 shadow-lg" alt="Caldera Pool">
        </div>
        <div class="col-lg-6">
            <h3 class="fw-bold mb-3">Tiket Masuk Kolam Renang</h3>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-primary">
                        <tr>
                            <th>Jenis Tiket</th>
                            <th>Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Dewasa</td>
                            <td>Rp 35.000</td>
                        </tr>
                        <tr>
                            <td>Anak-anak (3-12 tahun)</td>
                            <td>Rp 25.000</td>
                        </tr>
                        <tr>
                            <td>Keluarga (4 orang)</td>
                            <td>Rp 100.000</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                <a href="<?php echo e(route('reservation.ticket')); ?>" class="btn btn-primary btn-lg">
                    <i class="fas fa-ticket-alt me-2"></i>Beli Tiket Sekarang
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center p-4">
                <i class="fas fa-clock fa-3x text-primary mb-3"></i>
                <h5 class="fw-bold">Jam Operasional</h5>
                <p class="text-muted mb-0">Setiap Hari</p>
                <p class="text-muted">08:00 - 18:00 WIB</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center p-4">
                <i class="fas fa-check-circle fa-3x text-primary mb-3"></i>
                <h5 class="fw-bold">Fasilitas</h5>
                <p class="text-muted mb-0">Kolam Dewasa, Kolam Anak, Water Slide, Gazebo, Locker, Shower</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center p-4">
                <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                <h5 class="fw-bold">Peraturan</h5>
                <p class="text-muted mb-0">Menggunakan pakaian renang, dilarang merokok, anak harus didampingi orang tua</p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\PA2_Kel6\resources\views/pages/pool.blade.php ENDPATH**/ ?>