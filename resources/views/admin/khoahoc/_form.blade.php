<div class="row g-4 mb-5">
  <!-- Cột trái: Nội dung chính -->
  <div class="col-lg-8 animate-fade-in delay-2">
    
    <div class="table-card p-4 mb-4">
      <h2 class="card-title">Thông tin cơ bản</h2>
      
      <div class="row">
          <div class="col-md-6 mb-4">
            <label class="form-label fw-medium">Tên khóa học <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('ten_khoa_hoc') is-invalid @enderror" name="ten_khoa_hoc" value="{{ old('ten_khoa_hoc', $khoahoc->ten_khoa_hoc) }}" placeholder="Tên khóa học..." required>
            @error('ten_khoa_hoc')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-6 mb-4">
            <label class="form-label fw-medium">Slug <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('slug') is-invalid @enderror" name="slug" value="{{ old('slug', $khoahoc->slug) }}" placeholder="Slug khóa học..." required>
            @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
      </div>

      <div class="mb-4">
        <label class="form-label fw-medium">Mô tả ngắn</label>
        <textarea class="form-control @error('mo_ta_ngan') is-invalid @enderror" name="mo_ta_ngan" rows="2">{{ old('mo_ta_ngan', $khoahoc->mo_ta_ngan) }}</textarea>
        @error('mo_ta_ngan')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="mb-2">
        <label class="form-label fw-medium">Mô tả chi tiết</label>
        <textarea class="form-control @error('mo_ta') is-invalid @enderror ckeditor" name="mo_ta" rows="6">{{ old('mo_ta', $khoahoc->mo_ta) }}</textarea>
        @error('mo_ta')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
    </div>

    <div class="table-card p-4">
      <h2 class="card-title">Chỉ số Khóa học</h2>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label fw-medium">Giá (VND)</label>
          <div class="input-group">
            <input type="number" class="form-control @error('gia') is-invalid @enderror" name="gia" min="0" value="{{ old('gia', $khoahoc->gia ?? 0) }}">
            <span class="input-group-text">₫</span>
          </div>
          @error('gia')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
          <label class="form-label fw-medium">Giá khuyến mãi (VND)</label>
          <div class="input-group">
            <input type="number" class="form-control @error('gia_giam') is-invalid @enderror" name="gia_giam" min="0" value="{{ old('gia_giam', $khoahoc->gia_giam) }}">
            <span class="input-group-text">₫</span>
          </div>
          @error('gia_giam')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
          <label class="form-label fw-medium text-muted">Tổng bài học</label>
          <input type="text" class="form-control bg-light text-muted" value="{{ $khoahoc->tong_bai_hoc ?? 0 }} bài" disabled>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-medium text-muted">Tổng thời gian</label>
          <input type="text" class="form-control bg-light text-muted" value="{{ $khoahoc->tong_thoi_gian ?? 0 }} phút" disabled>
        </div>
      </div>
    </div>

  </div>

  <!-- Cột phải: Cài đặt & Ảnh -->
  <div class="col-lg-4 animate-fade-in delay-3">
    
    <div class="table-card p-4 mb-4">
      <h2 class="card-title">Ảnh đại diện khóa học</h2>
      <div class="border rounded p-4 text-center bg-light mb-2 position-relative" style="border-style: dashed !important; min-height: 180px; display: flex; flex-direction: column; justify-content: center; align-items: center;" id="image-upload-area">
        <input type="file" class="form-control position-absolute w-100 h-100 opacity-0 @error('anh_bia') is-invalid @enderror" name="anh_bia" id="anh_bia" accept="image/*" style="top:0; left:0; cursor: pointer; z-index: {{ $khoahoc->anh_bia ? '5' : '10' }};">
        <input type="hidden" name="xoa_anh_bia" id="xoa_anh_bia" value="0">
        
        <div class="position-relative {{ $khoahoc->anh_bia ? '' : 'd-none' }}" id="preview-container" style="z-index: 15;">
            <img id="image-preview" src="{{ $khoahoc->anh_bia ? Storage::url($khoahoc->anh_bia) : '' }}" alt="Preview" class="img-fluid rounded shadow-sm" style="max-height: 160px;">
            <button type="button" class="btn btn-danger btn-sm position-absolute rounded-circle" id="remove-image-btn" style="top: -10px; right: -10px; width: 28px; height: 28px; padding: 0; display: flex; align-items: center; justify-content: center;" title="Xóa ảnh">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>

        <div id="upload-placeholder" class="{{ $khoahoc->anh_bia ? 'd-none' : '' }}" style="z-index: 1;">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-muted mb-2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
            <div class="small text-muted fw-medium mb-1">Đổi ảnh (kéo thả hoặc click)</div>
        </div>
      </div>
      @error('anh_bia')<div class="text-danger small mt-1 text-center">{{ $message }}</div>@enderror
      <div class="form-text small text-center mt-2">Định dạng JPG, PNG. Tỉ lệ 16:9. Kích thước tối đa 2MB.</div>
    </div>

    <div class="table-card p-4 mb-4">
      <h2 class="card-title">Video giới thiệu</h2>
      
      <div class="mb-3">
        <label class="form-label fw-bold">Video giới thiệu <span class="fw-normal text-muted">(Chọn Video từ thư viện)</span></label>
        <select class="form-select @error('video_id') is-invalid @enderror" name="video_id">
            <option value="">-- Chọn video (không bắt buộc) --</option>
            @foreach($videos as $video)
                <option value="{{ $video->id }}" {{ old('video_id', $khoahoc->video_id) == $video->id ? 'selected' : '' }}>
                    {{ $video->ten_video }}
                </option>
            @endforeach
        </select>
        @error('video_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        
        <div class="mt-2">
            <label class="form-label fw-bold mb-1" style="font-size: 0.9em;">Hoặc nhập Link Video (Youtube/Vimeo)</label>
            <input type="text" class="form-control @error('video_url') is-invalid @enderror" name="video_url" value="{{ old('video_url', $khoahoc->video_url) }}" placeholder="https://www.youtube.com/watch?v=...">
            @error('video_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
      </div>
    </div>

    <div class="table-card p-4 mb-4">
      <h2 class="card-title">Cài đặt xuất bản</h2>
      
      <div class="mb-3">
        <label class="form-label fw-medium">Danh mục <span class="text-danger">*</span></label>
        <select class="form-select @error('id_danh_muc_khoa_hoc') is-invalid @enderror" name="id_danh_muc_khoa_hoc" required>
          <option value="">-- Chọn danh mục --</option>
          @foreach($danhMucs as $dm)
          <option value="{{ $dm->id }}" {{ old('id_danh_muc_khoa_hoc', $khoahoc->id_danh_muc_khoa_hoc) == $dm->id ? 'selected' : '' }}>{{ $dm->ten_danh_muc }}</option>
          @endforeach
        </select>
        @error('id_danh_muc_khoa_hoc')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="mb-3">
        <label class="form-label fw-medium">Cấp độ HSK <span class="text-danger">*</span></label>
        <select class="form-select @error('id_cap_do_hsk') is-invalid @enderror" name="id_cap_do_hsk" required>
          <option value="">-- Chọn cấp độ --</option>
          @foreach($capDos as $cd)
          <option value="{{ $cd->id }}" {{ old('id_cap_do_hsk', $khoahoc->id_cap_do_hsk) == $cd->id ? 'selected' : '' }}>{{ $cd->ten_cap_do }}</option>
          @endforeach
        </select>
        @error('id_cap_do_hsk')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      
      <div class="form-check form-switch mb-3">
        <input class="form-check-input" type="checkbox" id="trang_thai" name="trang_thai" value="1" {{ old('trang_thai', $khoahoc->id ? $khoahoc->trang_thai : true) ? 'checked' : '' }}>
        <label class="form-check-label" for="trang_thai">Xuất bản khóa học</label>
      </div>

      <div class="form-check form-switch mb-3">
        <input class="form-check-input" type="checkbox" id="noi_bat" name="noi_bat" value="1" {{ old('noi_bat', $khoahoc->noi_bat) ? 'checked' : '' }}>
        <label class="form-check-label" for="noi_bat">Đánh dấu khóa học Nổi bật</label>
      </div>

    </div>
    
    <div class="d-flex gap-2 w-100">
        <a href="{{ route('admin.khoahoc.index') }}" class="btn btn-light border px-4 flex-grow-1">Hủy</a>
        <button type="submit" class="btn btn-primary px-4 flex-grow-1" style="background: var(--admin-primary); border: none;">{{ $submit_text }}</button>
    </div>

  </div>
</div>

@section('scripts')
<script>
    document.getElementById('anh_bia').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewContainer = document.getElementById('preview-container');
                const preview = document.getElementById('image-preview');
                const placeholder = document.getElementById('upload-placeholder');
                
                preview.src = e.target.result;
                previewContainer.classList.remove('d-none');
                placeholder.classList.add('d-none');
                document.getElementById('anh_bia').style.zIndex = "5"; // Move input behind button so button is clickable
                document.getElementById('xoa_anh_bia').value = "0"; // Reset trạng thái xóa ảnh
            }
            reader.readAsDataURL(file);
        }
    });

    document.getElementById('remove-image-btn').addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation(); // Ngăn sự kiện click lan ra upload area
        
        const fileInput = document.getElementById('anh_bia');
        fileInput.value = ''; // Xóa file đã chọn
        
        // Đánh dấu để xóa ảnh trên server
        document.getElementById('xoa_anh_bia').value = "1";
        
        document.getElementById('preview-container').classList.add('d-none');
        document.getElementById('upload-placeholder').classList.remove('d-none');
        fileInput.style.zIndex = "10"; // Move input back to front
    });
</script>
@endsection
