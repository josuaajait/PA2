

<?php $__env->startSection('title', 'Beli Tiket Kolam'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                <!-- Header -->
                <div class="card-header text-white text-center py-4" style="background: linear-gradient(135deg, #1c3451 0%, #01516e 100%);">
                    <h4 class="mb-0"><i class="fas fa-ticket-alt me-2" style="color: #c1a067;"></i>Beli Tiket Kolam</h4>
                    <p class="mb-0 mt-2 opacity-75">Pilih tiket kolam renang Caldera</p>
                </div>

                <div class="card-body p-5">
                    <!-- Harga Tiket -->
                    <div class="row mb-4 g-3">
                        <div class="col-md-4 text-center">
                            <div class="ticket-type-card p-3">
                                <i class="fas fa-user fa-3x mb-2" style="color: #1c3451;"></i>
                                <h6 class="fw-bold" style="color: #1c3451;">Dewasa</h6>
                                <p class="h5 mb-0" style="color: #c1a067;">Rp 35.000</p>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="ticket-type-card p-3">
                                <i class="fas fa-child fa-3x mb-2" style="color: #1c3451;"></i>
                                <h6 class="fw-bold" style="color: #1c3451;">Anak-anak</h6>
                                <p class="h5 mb-0" style="color: #c1a067;">Rp 25.000</p>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="ticket-type-card p-3">
                                <i class="fas fa-users fa-3x mb-2" style="color: #1c3451;"></i>
                                <h6 class="fw-bold" style="color: #1c3451;">Keluarga (4 org)</h6>
                                <p class="h5 mb-0" style="color: #c1a067;">Rp 100.000</p>
                            </div>
                        </div>
                    </div>

                    <hr style="border-color: #e8e0d0; margin-bottom: 28px;">

                    <form id="ticketForm" method="POST" action="<?php echo e(route('reservation.ticket.store')); ?>">
                        <?php echo csrf_field(); ?>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label caldera-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="customer_name" class="form-control caldera-input" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label caldera-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="customer_email" class="form-control caldera-input" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label caldera-label">Nomor Telepon <span class="text-danger">*</span></label>
                                <input type="text" name="customer_phone" class="form-control caldera-input" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label caldera-label">Tanggal Kunjungan <span class="text-danger">*</span></label>
                                <input type="date" name="visit_date" class="form-control caldera-input" min="<?php echo e(date('Y-m-d')); ?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label caldera-label">Jenis Tiket <span class="text-danger">*</span></label>
                                <select name="ticket_type" class="form-control caldera-input" id="ticketType" required>
                                    <option value="adult">Dewasa - Rp 35.000</option>
                                    <option value="child">Anak-anak - Rp 25.000</option>
                                    <option value="family">Keluarga (4 org) - Rp 100.000</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label caldera-label">Jumlah Tiket <span class="text-danger">*</span></label>
                                <input type="number" name="number_of_tickets" id="numberOfTickets" class="form-control caldera-input" min="1" value="1" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label caldera-label">Total Harga</label>
                                <div class="total-price-box">
                                    <span id="totalPrice">Rp 35.000</span>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-caldera btn-lg px-5">
                                <i class="fas fa-shopping-cart me-2"></i>Beli Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const prices = { adult: 35000, child: 25000, family: 100000 };

    document.getElementById('ticketType').addEventListener('change', updatePrice);
    document.getElementById('numberOfTickets').addEventListener('input', updatePrice);

    function updatePrice() {
        const type = document.getElementById('ticketType').value;
        const qty = document.getElementById('numberOfTickets').value;
        let price = prices[type];

        if (type === 'family') {
            price = 100000;
            document.getElementById('numberOfTickets').value = 1;
            document.getElementById('numberOfTickets').disabled = true;
        } else {
            document.getElementById('numberOfTickets').disabled = false;
        }

        const total = price * qty;
        document.getElementById('totalPrice').innerText = 'Rp ' + total.toLocaleString('id-ID');
    }
</script>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap');

    .ticket-type-card {
        border: 1.5px solid #e8e0d0;
        border-radius: 12px;
        background: #fdfcfa;
        transition: all 0.2s;
    }

    .ticket-type-card:hover {
        border-color: #c1a067;
        background: #fff8f0;
        transform: translateY(-3px);
        box-shadow: 0 4px 16px rgba(193,160,103,0.15);
    }

    .caldera-label {
        font-weight: 600;
        font-size: 13px;
        color: #374151;
    }

    .caldera-input {
        border-radius: 10px;
        border: 1.5px solid #d1d5db;
        padding: 10px 14px;
        font-size: 14px;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .caldera-input:focus {
        border-color: #1c3451;
        box-shadow: 0 0 0 3px rgba(28,52,81,0.08);
        outline: none;
    }

    .total-price-box {
        background: linear-gradient(135deg, #1c3451, #01516e);
        color: white;
        font-size: 1.3rem;
        font-weight: 700;
        border-radius: 10px;
        padding: 10px 14px;
        display: flex;
        align-items: center;
        height: 44px;
    }

    .btn-caldera {
        background: linear-gradient(135deg, #1c3451 0%, #01516e 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 15px;
        padding: 12px 36px;
        transition: all 0.2s;
    }

    .btn-caldera:hover {
        background: linear-gradient(135deg, #c1a067, #a8894f);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(193,160,103,0.3);
    }
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\PA2_Kel6\resources\views/reservation/ticket/create.blade.php ENDPATH**/ ?>