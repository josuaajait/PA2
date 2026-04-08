

<?php $__env->startSection('title', 'Testimonials'); ?>

<?php $__env->startSection('content'); ?>
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Daftar Testimoni</h6>
    </div>
    <div class="card-body">
        <!-- Filter Section -->
        <div class="row mb-3">
            <div class="col-md-2">
                <select class="form-select form-select-sm" id="approveFilter" onchange="filterTestimonials()">
                    <option value="">Semua</option>
                    <option value="1">Disetujui</option>
                    <option value="0">Pending</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select form-select-sm" id="ratingFilter" onchange="filterTestimonials()">
                    <option value="">Semua Rating</option>
                    <option value="5">5 Bintang</option>
                    <option value="4">4 Bintang</option>
                    <option value="3">3 Bintang</option>
                    <option value="2">2 Bintang</option>
                    <option value="1">1 Bintang</option>
                </select>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control form-control-sm" id="searchFilter" placeholder="Cari nama atau komentar..." onkeyup="filterTestimonials()">
            </div>
            <div class="col-md-4 text-end">
                <a href="<?php echo e(route('admin.testimonials.export')); ?>" class="btn btn-success btn-sm">
                    <i class="fas fa-download"></i> Export
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover" id="testimonialsTable">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Rating</th>
                        <th>Komentar</th>
                        <th>Layanan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $testimonial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <?php echo e($testimonial->customer_name); ?><br>
                            <small class="text-muted"><?php echo e($testimonial->customer_email); ?></small>
                        </td>
                        <td>
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <?php if($i <= $testimonial->rating): ?>
                                    <i class="fas fa-star text-warning"></i>
                                <?php else: ?>
                                    <i class="far fa-star text-warning"></i>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </td>
                        <td><?php echo e(\Str::limit($testimonial->comment, 100)); ?></td>
                        <td>
                            <?php if($testimonial->service_type == 'restaurant'): ?>
                                <span class="badge bg-primary">Restoran</span>
                            <?php elseif($testimonial->service_type == 'pool'): ?>
                                <span class="badge bg-info">Kolam</span>
                            <?php else: ?>
                                <span class="badge bg-success">Event</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($testimonial->is_approved): ?>
                                <span class="badge bg-success">Disetujui</span>
                            <?php else: ?>
                                <span class="badge bg-warning">Pending</span>
                            <?php endif; ?>
                            <?php if($testimonial->is_featured): ?>
                                <span class="badge bg-primary">Featured</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo e(route('admin.testimonials.show', $testimonial->id)); ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                            <?php if(!$testimonial->is_approved): ?>
                                <form action="<?php echo e(route('admin.testimonials.approve', $testimonial->id)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            <?php endif; ?>
                            <button onclick="toggleFeatured(<?php echo e($testimonial->id); ?>)" class="btn btn-sm btn-info">
                                <i class="fas fa-star"></i>
                            </button>
                            <form action="<?php echo e(route('admin.testimonials.destroy', $testimonial->id)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus testimoni ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center">Belum ada data testimoni</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <?php echo e($testimonials->links()); ?>

    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    function toggleFeatured(id) {
        fetch(`/admin/testimonials/${id}/toggle-featured`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Content-Type': 'application/json'
            }
        }).then(response => response.json()).then(data => {
            if(data.success) location.reload();
        });
    }
    
    function filterTestimonials() {
        const approved = document.getElementById('approveFilter').value;
        const rating = document.getElementById('ratingFilter').value;
        const search = document.getElementById('searchFilter').value.toLowerCase();
        
        const rows = document.querySelectorAll('#testimonialsTable tbody tr');
        
        rows.forEach(row => {
            let show = true;
            
            if (approved !== '') {
                const statusCell = row.cells[4]?.innerText.toLowerCase();
                const isApproved = approved === '1' ? 'disetujui' : 'pending';
                if (!statusCell || !statusCell.includes(isApproved)) show = false;
            }
            
            if (rating && show) {
                const ratingStars = row.cells[1]?.innerHTML;
                const starCount = (ratingStars.match(/fa-star/g) || []).length;
                if (starCount != rating) show = false;
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
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\PA2_Kel6\resources\views/admin/testimonials/index.blade.php ENDPATH**/ ?>