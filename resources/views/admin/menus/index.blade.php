@extends('layouts.admin')

@section('title', 'Menu Management')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Daftar Menu</h6>
        <a href="{{ route('admin.menus.create') }}" class="btn btn-primary btn-sm">
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
                    @forelse($menus as $menu)
                    <tr>
                        <td>
                            @if($menu->image)
                                <img src="{{ asset('storage/' . $menu->image) }}" width="50" height="50" style="object-fit: cover; border-radius: 5px;">
                            @else
                                <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; border-radius: 5px;">
                                    <i class="fas fa-utensils"></i>
                                </div>
                            @endif
                        </td>
                        <td class="fw-bold">{{ $menu->name }}</td>
                        <td>{{ $menu->category }}</td>
                        <td>Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                        <td>{!! $menu->status_label !!}</td>
                        <td>
                            @if($menu->is_recommended)
                                <span class="badge bg-success">Ya</span>
                            @else
                                <span class="badge bg-secondary">Tidak</span>
                            @endif
                        </td>
                        <td>
                            <!-- Tombol Show (View) -->
                            <a href="{{ route('admin.menus.show', $menu) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                            <!-- Tombol Edit -->
                            <a href="{{ route('admin.menus.edit', $menu) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-edit"></i>
                            </a>
                            <!-- Tombol Delete -->
                            <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus menu ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            <!-- Tombol Toggle Availability -->
                            <button onclick="toggleAvailability({{ $menu->id }})" class="btn btn-sm {{ $menu->is_available ? 'btn-warning' : 'btn-success' }}">
                                <i class="fas {{ $menu->is_available ? 'fa-times' : 'fa-check' }}"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada data menu</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $menus->links() }}
    </div>
</div>

@push('scripts')
<script>
    function toggleAvailability(id) {
        fetch(`/admin/menus/${id}/toggle-availability`, {
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