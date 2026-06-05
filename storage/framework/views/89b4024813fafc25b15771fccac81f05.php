

<?php $__env->startSection('title', 'Detail Tiket'); ?>

<?php $__env->startSection('content'); ?>
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Detail Tiket: <?php echo e($ticket->ticket_code); ?></h6>
        <div>
            <a href="<?php echo e(route('admin.tickets.index')); ?>" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6 class="fw-bold mb-3">Informasi Customer</h6>
                <table class="table table-borderless">
                    <tr>
                        <th style="width: 120px;">Nama</th>
                        <td><?php echo e($ticket->customer_name); ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?php echo e($ticket->customer_email); ?></td>
                    </tr>
                    <tr>
                        <th>Telepon</th>
                        <td><?php echo e($ticket->customer_phone); ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6 class="fw-bold mb-3">Informasi Tiket</h6>
                <table class="table table-borderless">
                    <tr>
                        <th style="width: 120px;">Kode Tiket</th>
                        <td><?php echo e($ticket->ticket_code); ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal Kunjungan</th>
                        <td><?php echo e(\Carbon\Carbon::parse($ticket->visit_date)->format('d M Y')); ?></td>
                    </tr>
                    <tr>
                        <th>Jam Kunjungan</th>
                        <td><?php echo e($ticket->visit_time ?? 'Fleksibel'); ?></td>
                    </tr>
                    <tr>
                        <th>Jenis Tiket</th>
                        <td>
                            <?php if($ticket->ticket_type == 'adult'): ?>
                                <span class="badge bg-primary">Dewasa</span>
                            <?php elseif($ticket->ticket_type == 'child'): ?>
                                <span class="badge bg-info">Anak</span>
                            <?php else: ?>
                                <span class="badge bg-success">Keluarga</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Jumlah Tiket</th>
                        <td><?php echo e($ticket->number_of_tickets); ?> tiket</td>
                    </tr>
                    <tr>
                        <th>Harga per Tiket</th>
                        <td>Rp <?php echo e(number_format($ticket->price_per_ticket, 0, ',', '.')); ?></td>
                    </tr>
                    <tr>
                        <th>Total Harga</th>
                        <td>Rp <?php echo e(number_format($ticket->total_amount, 0, ',', '.')); ?></td>
                    </tr>
                    <tr>
                        <th>Status Tiket</th>
                        <td><?php echo $ticket->status_label; ?></td>
                    </tr>
                    <tr>
                        <th>Status Pembayaran</th>
                        <td><?php echo $ticket->payment_status_label; ?></td>
                    </tr>
                    <?php if($ticket->used_at): ?>
                    <tr>
                        <th>Digunakan Pada</th>
                        <td><?php echo e(\Carbon\Carbon::parse($ticket->used_at)->format('d M Y H:i')); ?></td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
        
        <?php if($ticket->status == 'active' && $ticket->payment_status == 'paid'): ?>
        <div class="text-end mt-3">
            <form action="<?php echo e(route('admin.tickets.verify', $ticket)); ?>" method="POST" class="d-inline">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-success" onclick="return confirm('Tandai tiket ini sebagai sudah digunakan?')">
                    <i class="fas fa-check me-1"></i> Tandai Digunakan
                </button>
            </form>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\PA2_Kel6\resources\views/admin/tickets/show.blade.php ENDPATH**/ ?>