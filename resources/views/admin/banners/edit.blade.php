@extends('admin.layouts.main')

@section('title', 'Sửa Banner Trang Chủ')

@section('content')
<div class="page-header animate-fade-in delay-1">
    <div>
        <h1 class="fs-4 fw-bold mb-1">Sửa Banner Trang Chủ</h1>
        <p class="text-muted mb-0 small">Cập nhật thông tin cho banner.</p>
    </div>
    <div>
        <a href="{{ route('admin.banners.index') }}" class="btn btn-light d-flex align-items-center gap-2 shadow-sm border">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Quay lại
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm animate-fade-in delay-2" style="background: var(--admin-card);">
    <div class="card-body p-4">
        <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-medium">Huy hiệu (Badge) <span class="text-muted small">(Vd: Dành riêng cho người Việt...)</span></label>
                    <input type="text" class="form-control" name="badge_text" value="{{ old('badge_text', $banner->badge_text) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-medium">Tiêu đề phụ (Pinyin) <span class="text-muted small">(Vd: xué zhōngwén...)</span></label>
                    <input type="text" class="form-control" name="subtitle" value="{{ old('subtitle', $banner->subtitle) }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-medium">Tiêu đề (Phần đầu) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="title_prefix" value="{{ old('title_prefix', $banner->title_prefix) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-medium">Tiêu đề (Phần nổi bật) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="title_highlight" value="{{ old('title_highlight', $banner->title_highlight) }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium">Mô tả ngắn</label>
                <textarea class="form-control" name="description" rows="3">{{ old('description', $banner->description) }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-medium">Chữ Nút số 1 <span class="text-muted small">(Vd: Bắt đầu học miễn phí)</span></label>
                    <input type="text" class="form-control" name="button_primary_text" value="{{ old('button_primary_text', $banner->button_primary_text) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-medium">Link Nút số 1 <span class="text-muted small">(Vd: #trial)</span></label>
                    <input type="text" class="form-control" name="button_primary_link" value="{{ old('button_primary_link', $banner->button_primary_link) }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-medium">Chữ Nút số 2 <span class="text-muted small">(Vd: Xem khóa học)</span></label>
                    <input type="text" class="form-control" name="button_secondary_text" value="{{ old('button_secondary_text', $banner->button_secondary_text) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-medium">Link Nút số 2 <span class="text-muted small">(Vd: #courses)</span></label>
                    <input type="text" class="form-control" name="button_secondary_link" value="{{ old('button_secondary_link', $banner->button_secondary_link) }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-medium">Chữ Hán 1 (Ô 1) <span class="text-muted small">(Vd: 你)</span></label>
                    <input type="text" class="form-control" name="grid_char_1" value="{{ old('grid_char_1', $banner->grid_char_1 ?? '你') }}" maxlength="10">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-medium">Chữ Hán 2 (Ô 3) <span class="text-muted small">(Vd: 好)</span></label>
                    <input type="text" class="form-control" name="grid_char_2" value="{{ old('grid_char_2', $banner->grid_char_2 ?? '好') }}" maxlength="10">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-medium">Chữ Hán 3 (Ô 5) <span class="text-muted small">(Vd: 学)</span></label>
                    <input type="text" class="form-control" name="grid_char_3" value="{{ old('grid_char_3', $banner->grid_char_3 ?? '学') }}" maxlength="10">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-medium">Chữ Hán 4 (Ô 6) <span class="text-muted small">(Vd: 中)</span></label>
                    <input type="text" class="form-control" name="grid_char_4" value="{{ old('grid_char_4', $banner->grid_char_4 ?? '中') }}" maxlength="10">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-medium">Thứ tự hiển thị</label>
                    <input type="number" class="form-control" name="thu_tu" value="{{ old('thu_tu', $banner->thu_tu) }}">
                </div>
                <div class="col-md-6 mb-3 d-flex align-items-end">
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ $banner->is_active ? 'checked' : '' }}>
                        <label class="form-check-label fw-medium" for="is_active">Hiển thị Banner này</label>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-3 border-top border-light d-flex justify-content-end gap-2">
                <a href="{{ route('admin.banners.index') }}" class="btn btn-light">Hủy bỏ</a>
                <button type="submit" class="btn btn-primary" style="background: var(--admin-primary); border: none;">Cập nhật</button>
            </div>
        </form>
    </div>
</div>
@endsection
