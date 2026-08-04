@extends('admin.layouts.main')

@section('title', 'Thêm Tính năng mới — Hányǔ Admin')

@section('content')
<div class="page-header animate-fade-in delay-1">
  <div class="d-flex align-items-center gap-3">
    <a href="{{ route('admin.tinhnang.index') }}" class="btn btn-light btn-sm text-muted">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
      Quay lại
    </a>
    <div>
      <h1 class="fs-4 fw-bold mb-1">Thêm Tính năng mới</h1>
      <p class="text-muted mb-0 small">Thiết lập nội dung và giao diện cho khối tính năng hiển thị trên web.</p>
    </div>
  </div>
</div>

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show animate-fade-in" role="alert">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card bg-white shadow-sm border-0 rounded-3 animate-fade-in delay-2">
  <div class="card-body p-4 p-lg-5">
    <form action="{{ route('admin.tinhnang.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      
      <div class="row g-4">
        <!-- Thông tin cơ bản -->
        <div class="col-md-8">
          <h5 class="fw-bold mb-3">Thông tin chính</h5>
          
          <div class="mb-3">
            <label class="form-label fw-medium">Tiêu đề chính <span class="text-danger">*</span></label>
            <input type="text" name="tieu_de" class="form-control" required placeholder="VD: Thuật toán Spaced Repetition">
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium">Chữ trong Huy hiệu (Badge)</label>
            <input type="text" name="badge_text" class="form-control" placeholder="VD: Học từ vựng siêu tốc">
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium">Mô tả đoạn văn</label>
            <textarea name="mo_ta" class="form-control" rows="4" placeholder="Nhập đoạn văn mô tả chi tiết..."></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium">Danh sách gạch đầu dòng (Mỗi mục 1 dòng)</label>
            <textarea name="danh_sach_bullet_raw" class="form-control" rows="4" placeholder="Ví dụ:&#10;Đồng bộ hóa từ vựng với bài giảng&#10;Phân tích chỉ số trí nhớ cá nhân&#10;Học mọi lúc mọi nơi trên điện thoại"></textarea>
            <div class="form-text">Các mục này sẽ có dấu tick (v) ở phía trước.</div>
          </div>
        </div>

        <!-- Cột thiết lập hiển thị -->
        <div class="col-md-4">
          <h5 class="fw-bold mb-3">Hiển thị & Sắp xếp</h5>
          
          <div class="mb-3">
            <label class="form-label fw-medium">Tải ảnh minh họa lên (Tùy chọn)</label>
            <input type="file" name="image_file" class="form-control" accept="image/*">
            <div class="form-text">Hoặc nhập link ảnh trực tiếp vào ô bên dưới:</div>
          </div>

          <div class="mb-3">
            <input type="text" name="image_url" class="form-control" placeholder="https://...">
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium">Vị trí ảnh</label>
            <select name="vi_tri_anh" class="form-select">
              <option value="right">Bên phải (Chữ trái - Ảnh phải)</option>
              <option value="left">Bên trái (Ảnh trái - Chữ phải)</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium">Thứ tự hiển thị</label>
            <input type="number" name="thu_tu" class="form-control" value="1">
          </div>

          <div class="mb-3 form-check form-switch mt-4">
            <input class="form-check-input" type="checkbox" role="switch" name="trang_thai" id="statusSwitch" checked value="1">
            <label class="form-check-label ms-2" for="statusSwitch">Hiển thị (Bật/Tắt)</label>
          </div>
        </div>
      </div>

      <hr class="my-4 text-muted">

      <div class="row g-4">
        <!-- Nút bấm -->
        <div class="col-md-6">
          <h5 class="fw-bold mb-3">Nút Kêu gọi hành động (Call to Action)</h5>
          
          <div class="mb-3">
            <label class="form-label fw-medium">Chữ trên nút</label>
            <input type="text" name="button_text" class="form-control" placeholder="VD: Thử Flashcard ngay (Bỏ trống sẽ ẩn nút)">
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium">Đường dẫn (Link)</label>
            <input type="text" name="button_link" class="form-control" placeholder="VD: # hoặc /hoc-thu">
          </div>
        </div>

        <!-- Thống kê nổi bật -->
        <div class="col-md-6">
          <h5 class="fw-bold mb-3">Khối thống kê nổi bật (Floating Stat)</h5>
          
          <div class="mb-3">
            <label class="form-label fw-medium">Con số thống kê</label>
            <input type="text" name="stat_number" class="form-control" placeholder="VD: +200%">
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium">Tiêu đề thống kê</label>
            <input type="text" name="stat_label" class="form-control" placeholder="VD: Tốc độ ghi nhớ từ">
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium">Mã Icon SVG</label>
            <textarea name="stat_icon" class="form-control" rows="2" placeholder="<svg>...</svg>"></textarea>
          </div>
        </div>
      </div>

      <div class="mt-4 pt-3 border-top text-end">
        <button type="submit" class="btn btn-primary px-4 py-2 fw-medium">Lưu tính năng</button>
      </div>

    </form>
  </div>
</div>
@endsection
