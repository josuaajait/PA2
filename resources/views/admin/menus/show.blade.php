@extends('layouts.admin')

@section('title', 'Detail Menu')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Detail Menu: {{ $menu->name }}</h6>
        <div>
            <a href="{{ route('admin.menus.edit', $menu) }}" class="btn btn-info btn-sm">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 text-center">
                @if($menu->image)
                    <img src="{{ asset('storage/' . $menu->image) }}" class="img-fluid rounded" style="max-width: 100%;">
                @else
                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px; border-radius: 10px;">
                        <i class="fas fa-utensils fa-4x"></i>
                    </div>
                @endif
            </div>
            <div class="col-md-8">
                <table class="table table-borderless">
                    <tr>
                        <th style="width: 150px;">Nama Menu</th>
                        <td>{{ $menu->name }}</td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td>{{ $menu->category }}</td>
                    </tr>
                    <tr>
                        <th>Harga</th>
                        <td>Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td>{{ $menu->description }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{!! $menu->status_label !!}</td>
                    </tr>
                    <tr>
                        <th>Rekomendasi</th>
                        <td>
                            @if($menu->is_recommended)
                                <span class="badge bg-success">Ya</span>
                            @else
                                <span class="badge bg-secondary">Tidak</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Dibuat Pada</th>
                        <td>{{ $menu->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Terakhir Update</th>
                        <td>{{ $menu->updated_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection