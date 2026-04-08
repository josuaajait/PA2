@extends('layouts.admin')

@section('title', 'Promo Management')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Daftar Promo</h6>
        <a href="{{ route('admin.promos.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Tambah Promo
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Judul</th>
                        <th>Kode Promo</th>
                        <th>Diskon</th>
                        <th>Periode</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($promos as $promo)
                    <tr>
                        <td>
                            <img src="{{ asset('storage/' . $promo->banner_image) }}" width="60" style="border-radius: 5px;">
                        </td>
                        <td class="fw-bold">{{ $promo->title }}</td>
                        <td><span class="badge bg-info">{{ $promo->promo_code }}</span></td>
                        <td>
                            @if($promo->discount_type == 'percentage')
                                {{ $promo->discount_value }}%
                            @else
                                Rp {{ number_format($promo->discount_value, 0, ',', '.') }}
                            @endif
                        </td>
                        <td>
                            <small>{{ \Carbon\Carbon::parse($promo->start_date)->format('d M Y') }}<br>
                            s/d {{ \Carbon\Carbon::parse($promo->end_date)->format('d M Y') }}</small>
                        </td>
                        <td>
                            @if($promo->is_active && now()->between($promo->start_date, $promo->end_date))
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Tidak Aktif</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.promos.edit', $promo) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.promos.destroy', $promo) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus promo ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada data promo</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $promos->links() }}
    </div>
</div>
@endsection