@extends('admin.layouts.main')

@section('title', 'Sửa Hội Thoại - Hanyu Admin')

@section('content')
<div class="page-header animate-fade-in delay-1 mb-4">
  <div class="d-flex align-items-center">
    <a href="{{ route('admin.hoithoai.index') }}" class="btn btn-light btn-sm me-3 text-muted">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
    </a>
    <div>
      <h1 class="fs-4 fw-bold mb-1">Cập nhật Hội Thoại</h1>
      <p class="text-muted mb-0 small">Chỉnh sửa nội dung hoặc thay đổi file audio.</p>
    </div>
  </div>
</div>

<div class="card bg-white border-0 shadow-sm rounded-3 animate-fade-in delay-2">
  <div class="card-body p-4">
    <form action="{{ route('admin.hoithoai.update', $hoiThoai->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-medium">Thuộc bài học <span class="text-danger">*</span></label>
                <select name="id_bai_hoc" class="form-select @error('id_bai_hoc') is-invalid @enderror" required>
                    <option value="">-- Chọn bài học --</option>
                    @foreach($baiHocs as $bh)
                        <option value="{{ $bh->id }}" {{ (old('id_bai_hoc') ?? $hoiThoai->id_bai_hoc) == $bh->id ? 'selected' : '' }}>{{ $bh->ten_bai_hoc }}</option>
                    @endforeach
                </select>
                @error('id_bai_hoc') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            
            <div class="col-md-6 mb-3">
                <label class="form-label fw-medium">Tiêu đề đoạn hội thoại</label>
                <input type="text" name="tieu_de" class="form-control @error('tieu_de') is-invalid @enderror" value="{{ old('tieu_de', $hoiThoai->tieu_de) }}" placeholder="VD: Đoạn hội thoại 1 (Không bắt buộc)">
                @error('tieu_de') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-medium">Mô tả nhóm hội thoại</label>
            <textarea name="mo_ta" rows="4" class="form-control @error('mo_ta') is-invalid @enderror" placeholder="Nhập mô tả ngắn về nhóm hội thoại này (không bắt buộc)...">{{ old('mo_ta', $hoiThoai->mo_ta) }}</textarea>
            @error('mo_ta') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-4">
            <label class="form-label fw-medium">Thứ tự hiển thị</label>
            <input type="number" name="thu_tu" class="form-control @error('thu_tu') is-invalid @enderror" value="{{ old('thu_tu', $hoiThoai->thu_tu) }}" min="0">
            <div class="form-text">Số nhỏ hơn sẽ hiển thị trước.</div>
            @error('thu_tu') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="d-flex justify-content-end gap-2 border-top pt-4">
            <a href="{{ route('admin.hoithoai.index') }}" class="btn btn-light shadow-sm">Hủy</a>
            <button type="submit" class="btn btn-primary shadow-sm" style="background: var(--admin-primary); border: none;">Lưu Thay Đổi</button>
        </div>
    </form>
  </div>
</div>
@endsection
