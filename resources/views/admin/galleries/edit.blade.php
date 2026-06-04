@extends('layouts.admin')

@section('title', 'Edit Gallery')

@push('styles')
<style>
    .report-page-header {
        background: linear-gradient(135deg, #1c3451 0%, #2a4a6b 100%);
        border-radius: 16px;
        padding: 24px 28px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .report-page-header h6 {
        color: #fff;
        font-size: 18px;
        font-weight: 700;
        margin: 0;
    }
    .report-page-header .header-icon {
        width: 44px;
        height: 44px;
        background: rgba(193,160,103,0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 14px;
    }
    .report-page-header .header-icon i { color: #c1a067; font-size: 18px; }
    .gallery-title-badge {
        background: rgba(193,160,103,0.2);
        color: #c1a067;
        border: 1px solid rgba(193,160,103,0.4);
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        padding: 5px 14px;
        max-width: 260px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .btn-back {
        background: rgba(255,255,255,0.15);
        color: #fff;
        border: 1px solid rgba(255,255,255,0.3);
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        padding: 8px 16px;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-back:hover { background: rgba(255,255,255,0.25); color: #fff; }
    .report-main-card {
        border: none;
        border-radius: 16px;
        border-top: 3px solid #c1a067 !important;
        box-shadow: 0 4px 20px rgba(28,52,81,0.08);
    }
    .report-main-card .card-body { padding: 28px; }
    .form-section-title {
        font-size: 13px;
        font-weight: 700;
        color: #1c3451;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        padding-bottom: 10px;
        border-bottom: 2px solid #c1a067;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .form-section-title i { color: #c1a067; }
    .form-label {
        font-size: 12px;
        font-weight: 600;
        color: #1c3451;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        margin-bottom: 6px;
    }
    .form-control, .form-select {
        border-radius: 10px;
        border: 1px solid #dde2e8;
        font-size: 13px;
        padding: 9px 13px;
        color: #1c3451;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-control:focus, .form-select:focus {
        border-color: #c1a067;
        box-shadow: 0 0 0 3px rgba(193,160,103,0.15);
    }
    .form-control.is-invalid, .form-select.is-invalid { border-color: #dc2626; }
    .form-text { font-size: 11px; color: #9ca3af; margin-top: 4px; }
    .form-check-input:checked { background-color: #1c3451; border-color: #1c3451; }
    .form-check-label { font-size: 13px; color: #374151; }

    /* Current file display */
    .current-file-wrap {
        background: #f8fafc;
        border: 1px solid #e8edf2;
        border-radius: 12px;
        padding: 12px;
        margin-bottom: 12px;
        display: inline-block;
    }
    .current-file-wrap img,
    .current-file-wrap video {
        max-width: 200px;
        max-height: 160px;
        border-radius: 8px;
        display: block;
    }
    .current-file-label {
        font-size: 11px;
        font-weight: 600;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        margin-bottom: 8px;
    }

    /* New file preview */
    .preview-wrap {
        margin-top: 12px;
        border-radius: 10px;
        overflow: hidden;
        background: #f1f5f9;
        display: none;
        position: relative;
    }
    .preview-wrap img, .preview-wrap video {
        width: 100%;
        max-height: 220px;
        object-fit: cover;
        display: block;
    }
    .preview-remove {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 28px;
        height: 28px;
        background: rgba(220,38,38,0.85);
        color: #fff;
        border: none;
        border-radius: 50%;
        font-size: 12px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .upload-area {
        border: 2px dashed #dde2e8;
        border-radius: 12px;
        padding: 28px 16px;
        text-align: center;
        background: #f8fafc;
        cursor: pointer;
        transition: border-color 0.2s, background 0.2s;
        position: relative;
    }
    .upload-area:hover, .upload-area.drag-over {
        border-color: #c1a067;
        background: #fffbf5;
    }
    .upload-area input[type="file"] {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
        width: 100%;
        height: 100%;
    }
    .upload-area .upload-icon { font-size: 28px; color: #c1a067; margin-bottom: 8px; }
    .upload-area .upload-text { font-size: 13px; color: #6b7280; }
    .upload-area .upload-text strong { color: #1c3451; }

    .category-chips { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 8px; }
    .chip {
        font-size: 11px;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
        border: 1px solid #dde2e8;
        background: #f8fafc;
        color: #374151;
        cursor: pointer;
        transition: all 0.15s;
        user-select: none;
    }
    .chip:hover, .chip.active {
        background: #1c3451;
        border-color: #1c3451;
        color: #fff;
    }

    .section-divider {
        height: 1px;
        background: linear-gradient(90deg, #c1a067 0%, transparent 100%);
        margin: 24px 0;
        border: none;
    }
    .btn-cancel {
        background: #f1f5f9;
        color: #1c3451;
        border: none;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        padding: 10px 22px;
        transition: all 0.2s;
        text-decoration: none;
    }
    .btn-cancel:hover { background: #e2e8f0; color: #1c3451; }
    .btn-save {
        background: linear-gradient(135deg, #1c3451, #2a4a6b);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        padding: 10px 28px;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(28,52,81,0.25);
    }
    .btn-save:hover {
        background: linear-gradient(135deg, #c1a067, #a98750);
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(193,160,103,0.3);
    }
</style>
@endpush

@section('content')

<div class="report-page-header">
    <div class="d-flex align-items-center gap-3">
        <div class="header-icon"><i class="fas fa-edit"></i></div>
        <h6>Edit Gallery</h6>
        <span class="gallery-title-badge">{{ $gallery->title }}</span>
    </div>
    <a href="{{ route('admin.galleries.index') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="card report-main-card">
    <div class="card-body">
        <form action="{{ route('admin.galleries.update', $gallery) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Informasi Dasar --}}
            <div class="form-section-title">
                <i class="fas fa-info-circle"></i> Informasi Dasar
            </div>

            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Judul <span class="text-danger">*</span></label>
                    <input type="text" name="title"
                           class="form-control @error('title') is-invalid @enderror"
                           value="{{ old('title', $gallery->title) }}" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tipe Media <span class="text-danger">*</span></label>
                    <select name="type" id="mediaType"
                            class="form-select @error('type') is-invalid @enderror" required>
                        <option value="image" {{ $gallery->type == 'image' ? 'selected' : '' }}>🖼 Gambar</option>
                        <option value="video" {{ $gallery->type == 'video' ? 'selected' : '' }}>🎬 Video</option>
                    </select>
                    @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <hr class="section-divider">

            {{-- File Saat Ini & Upload Baru --}}
            <div class="form-section-title">
                <i class="fas fa-cloud-upload-alt"></i> File
            </div>

            <div class="row g-3">
                <div class="col-12">
                    <div class="current-file-label">File Saat Ini</div>
                    <div class="current-file-wrap">
                        @if($gallery->type == 'image')
                            <img src="{{ asset('storage/' . $gallery->file_path) }}" alt="{{ $gallery->title }}">
                        @else
                            <video controls>
                                <source src="{{ asset('storage/' . $gallery->file_path) }}" type="video/mp4">
                            </video>
                        @endif
                    </div>

                    <label class="form-label mt-3">Ganti File <span class="text-muted" style="font-size:11px; text-transform:none;">(Opsional)</span></label>

                    <div id="panelUpload">
                        <div class="upload-area" id="dropArea">
                            <input type="file" name="file" id="fileInput"
                                   accept="{{ $gallery->type == 'image' ? 'image/jpeg,image/png,image/jpg' : 'video/mp4,video/mov,video/avi' }}">
                            <div class="upload-icon"><i class="fas fa-{{ $gallery->type == 'image' ? 'image' : 'video' }}"></i></div>
                            <div class="upload-text">
                                <strong>Klik untuk pilih file</strong> atau drag & drop di sini<br>
                                <span>{{ $gallery->type == 'image' ? 'JPG, JPEG, PNG — maks. 2 MB' : 'MP4, MOV, AVI — maks. 10 MB' }}</span>
                            </div>
                        </div>
                        <div class="preview-wrap" id="filePreview">
                            <button type="button" class="preview-remove" onclick="clearFile()">
                                <i class="fas fa-times"></i>
                            </button>
                            <div id="previewContent"></div>
                        </div>
                    </div>
                    @error('file')<div class="text-danger mt-1" style="font-size:12px">{{ $message }}</div>@enderror
                </div>
            </div>

            <hr class="section-divider">

            {{-- Pengaturan --}}
            <div class="form-section-title">
                <i class="fas fa-sliders-h"></i> Pengaturan
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Kategori</label>
                    <input type="text" name="category" id="categoryInput"
                           class="form-control @error('category') is-invalid @enderror"
                           value="{{ old('category', $gallery->category) }}"
                           placeholder="Ketik atau pilih kategori di bawah">
                    <div class="category-chips">
                        @foreach(['pool', 'restaurant', 'event', 'exterior', 'interior', 'room'] as $cat)
                            <span class="chip" onclick="setCategory('{{ $cat }}')">{{ ucfirst($cat) }}</span>
                        @endforeach
                    </div>
                    @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Sort Order</label>
                    <input type="number" name="sort_order"
                           class="form-control @error('sort_order') is-invalid @enderror"
                           value="{{ old('sort_order', $gallery->sort_order) }}" min="0">
                    <div class="form-text">Semakin kecil, semakin awal tampil.</div>
                    @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3 d-flex align-items-center pt-3">
                    <div class="form-check form-switch">
                        <input type="checkbox" name="is_featured" class="form-check-input"
                               id="isFeatured" value="1" {{ old('is_featured', $gallery->is_featured) ? 'checked' : '' }}
                               style="width:40px; height:22px;">
                        <label class="form-check-label ms-2" for="isFeatured">
                            <strong>Featured</strong><br>
                            <span style="font-size:11px;color:#6b7280">Tampil di halaman utama</span>
                        </label>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">Deskripsi <span style="font-weight:400;text-transform:none;color:#9ca3af">(opsional)</span></label>
                    <textarea name="description"
                              class="form-control @error('description') is-invalid @enderror"
                              rows="3">{{ old('description', $gallery->description) }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <hr class="section-divider">

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.galleries.index') }}" class="btn-cancel">Batal</a>
                <button type="submit" class="btn-save">
                    <i class="fas fa-save me-1"></i> Update
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const mediaTypeEl = document.getElementById('mediaType');
    const fileInput = document.getElementById('fileInput');
    const previewWrap = document.getElementById('filePreview');
    const previewContent = document.getElementById('previewContent');

    // Update accept attribute saat tipe media berubah
    mediaTypeEl.addEventListener('change', function() {
        const isVideo = this.value === 'video';
        fileInput.accept = isVideo ? 'video/mp4,video/mov,video/avi' : 'image/jpeg,image/png,image/jpg';
        // Update icon dan teks pada upload area
        const uploadIcon = document.querySelector('#dropArea .upload-icon i');
        const uploadTextSpan = document.querySelector('#dropArea .upload-text span');
        if (isVideo) {
            uploadIcon.className = 'fas fa-video';
            uploadTextSpan.innerHTML = 'MP4, MOV, AVI — maks. 10 MB';
        } else {
            uploadIcon.className = 'fas fa-image';
            uploadTextSpan.innerHTML = 'JPG, JPEG, PNG — maks. 2 MB';
        }
        // Hapus preview jika ada
        clearFile();
    });

    // Preview file baru
    fileInput.addEventListener('change', function() {
        previewContent.innerHTML = '';
        if (!this.files[0]) {
            previewWrap.style.display = 'none';
            return;
        }
        const file = this.files[0];
        const url = URL.createObjectURL(file);
        const isVideo = mediaTypeEl.value === 'video';
        if (isVideo) {
            const video = document.createElement('video');
            video.src = url;
            video.controls = true;
            video.style.maxWidth = '100%';
            video.style.maxHeight = '220px';
            previewContent.appendChild(video);
        } else {
            const img = document.createElement('img');
            img.src = url;
            img.style.maxWidth = '100%';
            img.style.maxHeight = '220px';
            previewContent.appendChild(img);
        }
        previewWrap.style.display = 'block';
    });

    function clearFile() {
        fileInput.value = '';
        previewContent.innerHTML = '';
        previewWrap.style.display = 'none';
    }

    // Category chips
    function setCategory(val) {
        document.getElementById('categoryInput').value = val;
        document.querySelectorAll('.chip').forEach(c => c.classList.remove('active'));
        event.target.classList.add('active');
    }

    // Sync chip aktif saat ada old value
    (function syncChip() {
        const current = document.getElementById('categoryInput').value.toLowerCase();
        if (!current) return;
        document.querySelectorAll('.chip').forEach(c => {
            if (c.textContent.trim().toLowerCase() === current) c.classList.add('active');
        });
    })();

    // Drag & drop visual
    const dropArea = document.getElementById('dropArea');
    dropArea.addEventListener('dragover',  e => { e.preventDefault(); dropArea.classList.add('drag-over'); });
    dropArea.addEventListener('dragleave', ()  => dropArea.classList.remove('drag-over'));
    dropArea.addEventListener('drop',      e => {
        e.preventDefault();
        dropArea.classList.remove('drag-over');
        if (e.dataTransfer.files.length) {
            fileInput.files = e.dataTransfer.files;
            fileInput.dispatchEvent(new Event('change'));
        }
    });
</script>
@endpush

@endsection