@extends('layouts.admin')

@section('title', 'Tambah Gallery')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent">
        <h6 class="mb-0">Tambah Gallery Baru</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.galleries.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Judul Gallery <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tipe Media <span class="text-danger">*</span></label>
                    <select name="type" class="form-control @error('type') is-invalid @enderror" id="mediaType" required>
                        <option value="image">Gambar</option>
                        <option value="video">Video</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3" id="imageUpload">
                    <label class="form-label">Upload Gambar <span class="text-danger">*</span></label>
                    <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" accept="image/jpeg,image/png,image/jpg" id="imageInput">
                    <small class="text-muted">Format: JPG, JPEG, PNG. Max: 2MB</small>
                    <div class="mt-2" id="imagePreview"></div>
                    @error('file')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3" id="videoUpload" style="display: none;">
                    <label class="form-label">Upload Video <span class="text-danger">*</span></label>
                    <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" accept="video/mp4,video/mov,video/avi">
                    <small class="text-muted">Format: MP4, MOV, AVI. Max: 10MB</small>
                    @error('file')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tipe Parent <span class="text-danger">*</span></label>
                    <select name="parent_type" class="form-control @error('parent_type') is-invalid @enderror" id="parentType" required>
                        <option value="">Pilih Tipe</option>
                        <option value="menu">Menu</option>
                        <option value="event">Event</option>
                        <option value="promo">Promo</option>
                        <option value="testimonial">Testimonial</option>
                    </select>
                    @error('parent_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Parent ID <span class="text-danger">*</span></label>
                    <select name="parent_id" class="form-control @error('parent_id') is-invalid @enderror" id="parentId" required>
                        <option value="">Pilih Parent</option>
                    </select>
                    @error('parent_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kategori</label>
                    <input type="text" name="category" class="form-control @error('category') is-invalid @enderror" value="{{ old('category') }}" placeholder="Contoh: restaurant, pool, event">
                    @error('category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Sort Order</label>
                    <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', 0) }}">
                    <small class="text-muted">Urutan tampilan (semakin kecil semakin awal)</small>
                    @error('sort_order')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-12 mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="is_featured" class="form-check-input" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                        <label class="form-check-label">Jadikan Featured</label>
                    </div>
                </div>
            </div>
            
            <div class="text-end">
                <a href="{{ route('admin.galleries.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Preview image before upload
    document.getElementById('imageInput')?.addEventListener('change', function(e) {
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
    
    // Toggle between image and video upload
    document.getElementById('mediaType')?.addEventListener('change', function() {
        const imageUpload = document.getElementById('imageUpload');
        const videoUpload = document.getElementById('videoUpload');
        
        if (this.value === 'image') {
            imageUpload.style.display = 'block';
            videoUpload.style.display = 'none';
        } else {
            imageUpload.style.display = 'none';
            videoUpload.style.display = 'block';
        }
    });
    
    // Load parent options based on parent type
    document.getElementById('parentType')?.addEventListener('change', function() {
        const parentType = this.value;
        const parentIdSelect = document.getElementById('parentId');
        
        if (!parentType) {
            parentIdSelect.innerHTML = '<option value="">Pilih Parent</option>';
            return;
        }
        
        // Fetch data based on parent type
        fetch(`/admin/galleries/get-parents?type=${parentType}`)
            .then(response => response.json())
            .then(data => {
                parentIdSelect.innerHTML = '<option value="">Pilih Parent</option>';
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item.name;
                    parentIdSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });
</script>
@endpush
@endsection