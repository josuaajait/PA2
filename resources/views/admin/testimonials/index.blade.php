@extends('layouts.admin')

@section('title', 'Testimonials')

@section('content')
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
                <a href="{{ route('admin.testimonials.export') }}" class="btn btn-success btn-sm">
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
                    @forelse($testimonials as $testimonial)
                    <tr>
                        <td>
                            {{ $testimonial->customer_name }}<br>
                            <small class="text-muted">{{ $testimonial->customer_email }}</small>
                        </td>
                        <td>
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $testimonial->rating)
                                    <i class="fas fa-star text-warning"></i>
                                @else
                                    <i class="far fa-star text-warning"></i>
                                @endif
                            @endfor
                        </td>
                        <td>{{ \Str::limit($testimonial->comment, 100) }}</td>
                        <td>
                            @if($testimonial->service_type == 'restaurant')
                                <span class="badge bg-primary">Restoran</span>
                            @elseif($testimonial->service_type == 'pool')
                                <span class="badge bg-info">Kolam</span>
                            @else
                                <span class="badge bg-success">Event</span>
                            @endif
                        </td>
                        <td>
                            @if($testimonial->is_approved)
                                <span class="badge bg-success">Disetujui</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                            @if($testimonial->is_featured)
                                <span class="badge bg-primary">Featured</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.testimonials.show', $testimonial->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if(!$testimonial->is_approved)
                                <form action="{{ route('admin.testimonials.approve', $testimonial->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            @endif
                            <button onclick="toggleFeatured({{ $testimonial->id }})" class="btn btn-sm btn-info">
                                <i class="fas fa-star"></i>
                            </button>
                            <form action="{{ route('admin.testimonials.destroy', $testimonial->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus testimoni ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada data testimoni</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $testimonials->links() }}
    </div>
</div>

@push('scripts')
<script>
    function toggleFeatured(id) {
        fetch(`/admin/testimonials/${id}/toggle-featured`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
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
@endpush
@endsection