@extends('layouts.admin')

@section('title', 'Edit Gallery')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent">
        <h6 class="mb-0">Edit Gallery: {{ $gallery->title }}</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.galleries.update', $gallery) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Judul Gallery <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $gallery->title) }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tipe Media <span class="text-danger">*</span></label>
                    <select name="type" class="form-control @error('type') is-invalid @enderror" id="mediaType" required>
                        <option value="image" {{ $gallery->type == 'image' ? 'selected' : '' }}>Gambar</option>
                        <option value="video" {{ $gallery->type == 'video' ? 'selected' : '' }}>Video</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Current File</label>
                    <div class="mb-2">
                        @if($gallery->type == 'image')
                            <img src="{{ asset('storage/' . $gallery->file_path) }}" class="img-fluid rounded" style="max-width: 200px;">
                        @else
                            <video width="200" controls>
                                <source src="{{ asset('storage/' . $gallery->file_path) }}" type="video/mp4">
                            </video>
                        @endif
                    </div>
                    <label class="form-label">Ganti File (Opsional)</label>
                    <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" accept="{{ $gallery->type == 'image' ? 'image/*' : 'video/*' }}" id="fileInput">
                    <small class="text-muted">Kosongkan jika tidak ingin mengganti file</small>
                    <div class="mt-2" id="filePreview"></div>
                    @error('file')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tipe Parent <span class="text-danger">*</span></label>
                    <select name="parent_type" class="form-control @error('parent_type') is-invalid @enderror" id="parentType" required>
                        <option value="">Pilih Tipe</option>
                        <option value="menu" {{ $gallery->menu_id ? 'selected' : '' }}>Menu</option>
                        <option value="event" {{ $gallery->event_id ? 'selected' : '' }}>Event</option>
                        <option value="promo" {{ $gallery->promo_id ? 'selected' : '' }}>Promo</option>
                        <option value="testimonial" {{ $gallery->testimonial_id ? 'selected' : '' }}>Testimonial</option>
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
                    <input type="text" name="category" class="form-control @error('category') is-invalid @enderror" value="{{ old('category', $gallery->category) }}" placeholder="Contoh: restaurant, pool, event">
                    @error('category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Sort Order</label>
                    <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', $gallery->sort_order) }}">
                    <small class="text-muted">Urutan tampilan (semakin kecil semakin awal)</small>
                    @error('sort_order')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-12 mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $gallery->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="is_featured" class="form-check-input" value="1" {{ old('is_featured', $gallery->is_featured) ? 'checked' : '' }}>
                        <label class="form-check-label">Jadikan Featured</label>
                    </div>
                </div>
            </div>
            
            <div class="text-end">
                <a href="{{ route('admin.galleries.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Preview file before upload
    document.getElementById('fileInput')?.addEventListener('change', function(e) {
        const preview = document.getElementById('filePreview');
        preview.innerHTML = '';
        
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            const fileType = document.getElementById('mediaType').value;
            
            reader.onload = function(e) {
                if (fileType === 'image') {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.maxWidth = '200px';
                    img.style.maxHeight = '200px';
                    img.style.borderRadius = '8px';
                    img.style.marginTop = '10px';
                    preview.appendChild(img);
                } else {
                    const video = document.createElement('video');
                    video.src = e.target.result;
                    video.style.maxWidth = '200px';
                    video.style.marginTop = '10px';
                    video.controls = true;
                    preview.appendChild(video);
                }
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
    
    // Get current parent ID based on existing data
    function getCurrentParent() {
        const parentType = document.getElementById('parentType').value;
        @php
            if($gallery->menu_id) $currentParent = $gallery->menu_id;
            elseif($gallery->event_id) $currentParent = $gallery->event_id;
            elseif($gallery->promo_id) $currentParent = $gallery->promo_id;
            elseif($gallery->testimonial_id) $currentParent = $gallery->testimonial_id;
            else $currentParent = null;
        @endphp
        return {{ $currentParent ?? 'null' }};
    }
    
    // Load parent options based on parent type
    document.getElementById('parentType')?.addEventListener('change', function() {
        const parentType = this.value;
        const parentIdSelect = document.getElementById('parentId');
        const currentParent = getCurrentParent();
        
        if (!parentType) {
            parentIdSelect.innerHTML = '<option value="">Pilih Parent</option>';
            return;
        }
        
        fetch(`/admin/galleries/get-parents?type=${parentType}`)
            .then(response => response.json())
            .then(data => {
                parentIdSelect.innerHTML = '<option value="">Pilih Parent</option>';
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item.name;
                    if (currentParent && item.id == currentParent) {
                        option.selected = true;
                    }
                    parentIdSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });
    
    // Trigger change to load existing parent
    document.getElementById('parentType')?.dispatchEvent(new Event('change'));
</script>
@endpush
@endsection