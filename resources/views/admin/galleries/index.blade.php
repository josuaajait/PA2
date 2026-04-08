@extends('layouts.admin')

@section('title', 'Gallery')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Daftar Gallery</h6>
        <div>
            <a href="{{ route('admin.galleries.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Tambah Gallery
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-4">
            @forelse($galleries as $gallery)
            <div class="col-md-3">
                <div class="card h-100 shadow-sm">
                    @if($gallery->type == 'image')
                        <img src="{{ $gallery->image_url }}" class="card-img-top" style="height: 200px; object-fit: cover;" onerror="this.src='https://placehold.co/400x300?text=No+Image'">
                    @else
                        <video class="card-img-top" style="height: 200px; object-fit: cover;" controls>
                            <source src="{{ $gallery->image_url }}" type="video/mp4">
                        </video>
                    @endif
                    <div class="card-body">
                        <h6 class="fw-bold">{{ $gallery->title }}</h6>
                        <p class="small text-muted">Kategori: {{ $gallery->category }}</p>
                        @if($gallery->is_featured)
                            <span class="badge bg-primary">Featured</span>
                        @endif
                    </div>
                    <div class="card-footer bg-transparent">
                        <a href="{{ route('admin.galleries.edit', $gallery) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.galleries.destroy', $gallery) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus gallery ini?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        <button onclick="toggleFeatured({{ $gallery->id }})" class="btn btn-sm {{ $gallery->is_featured ? 'btn-warning' : 'btn-success' }}">
                            <i class="fas fa-star"></i>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-images fa-4x text-muted mb-3"></i>
                <p>Belum ada data gallery</p>
                <a href="{{ route('admin.galleries.create') }}" class="btn btn-primary">Tambah Gallery</a>
            </div>
            @endforelse
        </div>
        {{ $galleries->links() }}
    </div>
</div>

@push('scripts')
<script>
    function toggleFeatured(id) {
        fetch(`/admin/galleries/${id}/toggle-featured`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(response => response.json()).then(data => {
            if(data.success) location.reload();
        });
    }
</script>
@endpush
@endsection