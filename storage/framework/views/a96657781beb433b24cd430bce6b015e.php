

<?php $__env->startSection('title', 'Pool Tickets'); ?>

<?php $__env->startSection('content'); ?>
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Daftar Tiket Kolam</h6>
    </div>
    <div class="card-body">
        <!-- Filter Section -->
        <div class="row mb-3">
            <div class="col-md-3">
                <select class="form-select form-select-sm" id="statusFilter" onchange="filterTickets()">
                    <option value="">Semua Status</option>
                    <option value="active">Aktif</option>
                    <option value="used">Digunakan</option>
                    <option value="expired">Kadaluarsa</option>
                    <option value="cancelled">Dibatalkan</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select form-select-sm" id="paymentFilter" onchange="filterTickets()">
                    <option value="">Semua Pembayaran</option>
                    <option value="paid">Lunas</option>
                    <option value="unpaid">Belum Bayar</option>
                </select>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control form-control-sm" id="searchFilter" placeholder="Cari nama atau kode tiket..." onkeyup="filterTickets()">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover" id="ticketsTable">
                <thead>
                    <tr>
                        <th>Kode Tiket</th>
                        <th>Customer</th>
                        <th>Tanggal Kunjungan</th>
                        <th>Jenis Tiket</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="fw-bold"><?php echo e($ticket->ticket_code); ?></td>
                        <td>
                            <?php echo e($ticket->customer_name); ?><br>
                            <small class="text-muted"><?php echo e($ticket->customer_phone); ?></small>
                        </td>
                        <td><?php echo e(\Carbon\Carbon::parse($ticket->visit_date)->format('d M Y')); ?><br>
                        <small><?php echo e($ticket->visit_time ?? 'Fleksibel'); ?></small></td>
                        <td>
                            <?php if($ticket->ticket_type == 'adult'): ?>
                                <span class="badge bg-primary">Dewasa</span>
                            <?php elseif($ticket->ticket_type == 'child'): ?>
                                <span class="badge bg-info">Anak</span>
                            <?php else: ?>
                                <span class="badge bg-success">Keluarga</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center"><?php echo e($ticket->number_of_tickets); ?> tiket</td>
                        <td>Rp <?php echo e(number_format($ticket->total_amount, 0, ',', '.')); ?></td>
                        <td><?php echo $ticket->status_label; ?><br>
                        <small><?php echo $ticket->payment_status_label; ?></small></td>
                        <td>
                            <a href="<?php echo e(route('admin.tickets.show', $ticket)); ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                            <?php if($ticket->status == 'active' && $ticket->payment_status == 'paid'): ?>
                                <button onclick="verifyTicket(<?php echo e($ticket->id); ?>)" class="btn btn-sm btn-success">
                                    <i class="fas fa-check"></i>
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="text-center">Belum ada data tiket kolam</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <?php echo e($tickets->links()); ?>

    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    function verifyTicket(id) {
        if(confirm('Tandai tiket ini sebagai sudah digunakan?')) {
            fetch(`/admin/tickets/${id}/verify`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                    'Content-Type': 'application/json'
                }
            }).then(response => response.json()).then(data => {
                if(data.success) location.reload();
            });
        }
    }
    
    function filterTickets() {
        const status = document.getElementById('statusFilter').value;
        const payment = document.getElementById('paymentFilter').value;
        const search = document.getElementById('searchFilter').value.toLowerCase();
        
        const rows = document.querySelectorAll('#ticketsTable tbody tr');
        
        rows.forEach(row => {
            let show = true;
            
            if (status) {
                const statusCell = row.cells[6]?.innerText.toLowerCase();
                if (!statusCell || !statusCell.includes(status)) show = false;
            }
            
            if (payment && show) {
                const paymentCell = row.cells[6]?.innerHTML.toLowerCase();
                if (!paymentCell || !paymentCell.includes(payment)) show = false;
            }
            
            if (search && show) {
                const text = row.innerText.toLowerCase();
                if (!text.includes(search)) show = false;
            }
            
            row.style.display = show ? '' : 'none';
        });
    }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\PA2_Kel6\resources\views/admin/tickets/index.blade.php ENDPATH**/ ?>