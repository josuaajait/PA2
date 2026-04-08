@extends('layouts.admin')

@section('title', 'Detail Testimoni')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Detail Testimoni</h6>
        <div>
            <a href="{{ route('admin.testimonials.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="text-center mb-4">
                    <div class="mb-3">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $testimonial->rating)
                                <i class="fas fa-star text-warning fa-2x"></i>
                            @else
                                <i class="far fa-star text-warning fa-2x"></i>
                            @endif
                        @endfor
                    </div>
                    <h4 class="fw-bold">{{ $testimonial->customer_name }}</h4>
                    <p class="text-muted">{{ $testimonial->customer_email }}</p>
                    <p class="text-muted">Layanan: {{ ucfirst($testimonial->service_type) }}</p>
                    @if($testimonial->visit_date)
                        <p class="text-muted">Tanggal Kunjungan: {{ \Carbon\Carbon::parse($testimonial->visit_date)->format('d M Y') }}</p>
                    @endif
                </div>
                
                <div class="card bg-light">
                    <div class="card-body">
                        <p class="fs-5 fst-italic mb-0">"{{ $testimonial->comment }}"</p>
                    </div>
                </div>
                
                <div class="mt-4">
                    <h6>Status</h6>
                    @if($testimonial->is_approved)
                        <span class="badge bg-success">Disetujui</span>
                    @else
                        <span class="badge bg-warning">Pending</span>
                    @endif
                    @if($testimonial->is_featured)
                        <span class="badge bg-primary">Featured</span>
                    @endif
                </div>
                
                @if(!$testimonial->is_approved)
                <div class="text-end mt-4">
                    <form action="{{ route('admin.testimonials.approve', $testimonial->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check me-1"></i> Setujui Testimoni
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection