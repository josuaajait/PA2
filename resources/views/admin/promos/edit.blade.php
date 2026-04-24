@extends('layouts.admin')

@section('title', 'Edit Promo')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent">
        <h6 class="mb-0">Edit Promo: {{ $promo->title }}</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.promos.update', $promo) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Judul Promo <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $promo->title) }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kode Promo <span class="text-danger">*</span></label>
                    <input type="text" name="promo_code" class="form-control @error('promo_code') is-invalid @enderror" value="{{ old('promo_code', $promo->promo_code) }}" required>
                    <small class="text-muted">Contoh: SUMMER20, WELCOME10</small>
                    @error('promo_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label class="form-label">Tipe Diskon <span class="text-danger">*</span></label>
                    <select name="discount_type" class="form-control @error('discount_type') is-invalid @enderror" id="discountType" required>
                        <option value="percentage" {{ old('discount_type', $promo->discount_type) == 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                        <option value="nominal" {{ old('discount_type', $promo->discount_type) == 'nominal' ? 'selected' : '' }}>Nominal (Rp)</option>
                    </select>
                    @error('discount_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label class="form-label" id="discountValueLabel">Nilai Diskon <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text" id="discountSymbol">%</span>
                        <input type="number" name="discount_value" class="form-control @error('discount_value') is-invalid @enderror" value="{{ old('discount_value', $promo->discount_value) }}" required>
                    </div>
                    @error('discount_value')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label class="form-label">Tipe Promo <span class="text-danger">*</span></label>
                    <select name="promo_type" class="form-control @error('promo_type') is-invalid @enderror" required>
                        <option value="menu" {{ old('promo_type', $promo->promo_type) == 'menu' ? 'selected' : '' }}>Menu</option>
                        <option value="ticket" {{ old('promo_type', $promo->promo_type) == 'ticket' ? 'selected' : '' }}>Tiket Kolam</option>
                        <option value="reservation" {{ old('promo_type', $promo->promo_type) == 'reservation' ? 'selected' : '' }}>Reservasi</option>
                    </select>
                    @error('promo_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-3 mb-3">
                    <label class="form-label">Minimal Pembelian</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="min_purchase" class="form-control @error('min_purchase') is-invalid @enderror" value="{{ old('min_purchase', $promo->min_purchase) }}">
                    </div>
                    <small class="text-muted">Kosongkan jika tidak ada minimal</small>
                    @error('min_purchase')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-3 mb-3">
                    <label class="form-label">Maksimal Diskon</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="max_discount" class="form-control @error('max_discount') is-invalid @enderror" value="{{ old('max_discount', $promo->max_discount) }}">
                    </div>
                    <small class="text-muted">Kosongkan jika tidak ada batas</small>
                    @error('max_discount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-3 mb-3">
                    <label class="form-label">Maksimal Penggunaan</label>
                    <input type="number" name="max_usage" class="form-control @error('max_usage') is-invalid @enderror" value="{{ old('max_usage', $promo->max_usage) }}" placeholder="Kosongkan jika unlimited">
                    @error('max_usage')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-3 mb-3">
                    <label class="form-label">Sudah Digunakan</label>
                    <input type="number" name="used_count" class="form-control @error('used_count') is-invalid @enderror" value="{{ old('used_count', $promo->used_count) }}" readonly>
                    <small class="text-muted">Otomatis terupdate</small>
                    @error('used_count')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', date('Y-m-d\TH:i', strtotime($promo->start_date))) }}" required>
                    @error('start_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', date('Y-m-d\TH:i', strtotime($promo->end_date))) }}" required>
                    @error('end_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-12 mb-3">
                    <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" required>{{ old('description', $promo->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Gambar Banner Saat Ini</label>
                    @if($promo->banner_image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $promo->banner_image) }}" class="img-fluid rounded" style="max-width: 200px;">
                        </div>
                    @endif
                    <label class="form-label">Ganti Gambar Banner</label>
                    <input type="file" name="banner_image" class="form-control @error('banner_image') is-invalid @enderror" accept="image/*">
                    <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar. Format: JPG, JPEG, PNG. Max: 2MB</small>
                    <div class="mt-2" id="imagePreview"></div>
                    @error('banner_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <div class="form-check mt-4">
                        <input type="checkbox" name="is_active" class="form-check-input" value="1" {{ old('is_active', $promo->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label">Aktif</label>
                    </div>
                    <div class="mt-3">
                        @if($promo->is_active && now()->between($promo->start_date, $promo->end_date))
                            <span class="badge bg-success">Promo Sedang Berlangsung</span>
                        @elseif(!$promo->is_active)
                            <span class="badge bg-secondary">Promo Nonaktif</span>
                        @elseif(now()->gt($promo->end_date))
                            <span class="badge bg-danger">Promo Telah Berakhir</span>
                        @elseif(now()->lt($promo->start_date))
                            <span class="badge bg-info">Promo Akan Datang</span>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="text-end">
                <a href="{{ route('admin.promos.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Update Promo</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Preview image before upload
    document.querySelector('input[name="banner_image"]')?.addEventListener('change', function(e) {
        const preview = document.getElementById('imagePreview');
        preview.innerHTML = '';
        
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '200px';
                img.style.maxHeight = '200px';
                img.style.borderRadius = '8px';
                img.style.marginTop = '10px';
                preview.appendChild(img);
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
    
    // Update discount symbol based on discount type
    document.getElementById('discountType')?.addEventListener('change', function() {
        const discountValueLabel = document.getElementById('discountValueLabel');
        const discountSymbol = document.getElementById('discountSymbol');
        
        if (this.value === 'percentage') {
            discountSymbol.textContent = '%';
            discountValueLabel.textContent = 'Nilai Diskon (%)';
        } else {
            discountSymbol.textContent = 'Rp';
            discountValueLabel.textContent = 'Nilai Diskon (Rp)';
        }
    });
</script>
@endpush
@endsection