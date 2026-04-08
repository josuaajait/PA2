@extends('layouts.admin')

@section('title', 'Tambah Promo')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent">
        <h6 class="mb-0">Tambah Promo Baru</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.promos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Judul Promo <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kode Promo <span class="text-danger">*</span></label>
                    <input type="text" name="promo_code" class="form-control @error('promo_code') is-invalid @enderror" value="{{ old('promo_code') }}" required>
                    @error('promo_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label class="form-label">Tipe Diskon <span class="text-danger">*</span></label>
                    <select name="discount_type" class="form-control @error('discount_type') is-invalid @enderror" required>
                        <option value="percentage">Persentase (%)</option>
                        <option value="nominal">Nominal (Rp)</option>
                    </select>
                    @error('discount_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label class="form-label">Nilai Diskon <span class="text-danger">*</span></label>
                    <input type="number" name="discount_value" class="form-control @error('discount_value') is-invalid @enderror" value="{{ old('discount_value') }}" required>
                    @error('discount_value')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label class="form-label">Tipe Promo <span class="text-danger">*</span></label>
                    <select name="promo_type" class="form-control @error('promo_type') is-invalid @enderror" required>
                        <option value="menu">Menu</option>
                        <option value="ticket">Tiket Kolam</option>
                        <option value="reservation">Reservasi</option>
                    </select>
                    @error('promo_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}" required>
                    @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}" required>
                    @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="col-12 mb-3">
                    <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" required>{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="col-12 mb-3">
                    <label class="form-label">Gambar Banner <span class="text-danger">*</span></label>
                    <input type="file" name="banner_image" class="form-control @error('banner_image') is-invalid @enderror" accept="image/*" required>
                    @error('banner_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="is_active" class="form-check-input" value="1" checked>
                        <label class="form-check-label">Aktif</label>
                    </div>
                </div>
            </div>
            
            <div class="text-end">
                <a href="{{ route('admin.promos.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection