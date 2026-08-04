@extends('admin.layouts.main')

@section('content')
<div class="container-fluid px-4 py-4">
  <div class="d-flex align-items-center justify-content-between mb-4">
    <div>
      <h1 class="h3 mb-0 text-gray-800 fw-bold">Chi tiết khóa học</h1>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 mt-2">
          <li class="breadcrumb-item"><a href="{{ route('admin.home') }}" class="text-decoration-none">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.khoahoc.index') }}" class="text-decoration-none">Khóa học</a></li>
          <li class="breadcrumb-item active" aria-current="page">{{ $khoahoc->ten_khoa_hoc }}</li>
        </ol>
      </nav>
    </div>
    <div class="d-flex gap-2">
      <a href="{{ route('admin.khoahoc.index') }}" class="btn btn-light border shadow-sm">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        Quay lại
      </a>
      <a href="{{ route('admin.khoahoc.edit', $khoahoc->id) }}" class="btn btn-primary shadow-sm">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
        Chỉnh sửa
      </a>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-xl-4 col-lg-5">
      <!-- Ảnh bìa và trạng thái -->
      <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-0">
          @if($khoahoc->anh_bia)
            <img src="{{ Storage::url($khoahoc->anh_bia) }}" alt="Ảnh bìa" class="w-100 rounded-top-4" style="height: 240px; object-fit: cover;">
          @else
            <div class="w-100 rounded-top-4 d-flex align-items-center justify-content-center text-white" style="height: 240px; background: linear-gradient(135deg, #f59e0b, #dc2626); font-size: 4rem; font-weight: bold;">
              {{ mb_substr($khoahoc->ten_khoa_hoc, 0, 1) }}
            </div>
          @endif
          
          <div class="p-4 text-center">
            <h4 class="fw-bold mb-3">{{ $khoahoc->ten_khoa_hoc }}</h4>
            <div class="d-flex justify-content-center gap-2 mb-3">
              @if($khoahoc->trang_thai)
                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 border border-success-subtle">Đang xuất bản</span>
              @else
                <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 border border-secondary-subtle">Bản nháp</span>
              @endif
              
              @if($khoahoc->noi_bat)
                <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 border border-warning-subtle">Nổi bật</span>
              @endif
            </div>
            
            <div class="d-flex align-items-center justify-content-center gap-3 mt-4 pt-3 border-top">
              <div class="text-center">
                <div class="text-muted small mb-1">Tổng bài học</div>
                <div class="fw-bold fs-5">{{ $khoahoc->tong_bai_hoc }}</div>
              </div>
              <div class="vr"></div>
              <div class="text-center">
                <div class="text-muted small mb-1">Thời lượng</div>
                <div class="fw-bold fs-5">{{ $khoahoc->tong_thoi_gian }}'</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Thông tin giá và phân loại -->
      <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
          <h5 class="card-title fw-bold mb-4 fs-6 text-uppercase text-muted">Thông tin phân loại</h5>
          
          <ul class="list-group list-group-flush">
            <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
              <span class="text-muted">Danh mục:</span>
              <span class="fw-medium">{{ $khoahoc->danhMucKhoaHoc ? $khoahoc->danhMucKhoaHoc->ten_danh_muc : 'Chưa phân loại' }}</span>
            </li>
            <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
              <span class="text-muted">Cấp độ:</span>
              <span class="fw-medium">{{ $khoahoc->capDoHSK ? $khoahoc->capDoHSK->ten_cap_do : 'Chưa thiết lập' }}</span>
            </li>
            <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
              <span class="text-muted">Giá bán:</span>
              @if($khoahoc->gia_giam > 0)
                <div class="text-end">
                  <span class="text-danger fw-bold fs-5">{{ number_format($khoahoc->gia_giam, 0, ',', '.') }}đ</span>
                  <br>
                  <del class="text-muted small">{{ number_format($khoahoc->gia, 0, ',', '.') }}đ</del>
                </div>
              @else
                <span class="fw-bold fs-5 text-dark">{{ number_format($khoahoc->gia, 0, ',', '.') }}đ</span>
              @endif
            </li>
            <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
              <span class="text-muted">Ngày tạo:</span>
              <span class="fw-medium">{{ $khoahoc->created_at->format('d/m/Y H:i') }}</span>
            </li>
            <li class="list-group-item px-0 pb-0 d-flex justify-content-between align-items-center">
              <span class="text-muted">Cập nhật lần cuối:</span>
              <span class="fw-medium">{{ $khoahoc->updated_at->format('d/m/Y H:i') }}</span>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <div class="col-xl-8 col-lg-7">
      <div class="card border-0 shadow-sm rounded-4 h-100">
        <div class="card-body p-4 p-xl-5">
          <h5 class="card-title fw-bold mb-4 fs-6 text-uppercase text-muted border-bottom pb-3">Chi tiết nội dung</h5>
          
          <div class="mb-4">
            <h6 class="fw-bold text-dark mb-2">Đường dẫn (Slug)</h6>
            <div class="bg-light p-3 rounded-3 text-muted font-monospace small">
              {{ url('/khoa-hoc/' . $khoahoc->slug) }}
            </div>
          </div>

          <div class="mb-4">
            <h6 class="fw-bold text-dark mb-2">Mô tả ngắn gọn</h6>
            @if($khoahoc->mo_ta_ngan)
              <div class="p-3 bg-light rounded-3 text-dark">
                {{ $khoahoc->mo_ta_ngan }}
              </div>
            @else
              <span class="text-muted fst-italic">Không có mô tả ngắn.</span>
            @endif
          </div>

          <div>
            <h6 class="fw-bold text-dark mb-3">Mô tả chi tiết</h6>
            @if($khoahoc->mo_ta)
              <div class="content-preview text-dark p-4 bg-light rounded-3" style="line-height: 1.8;">
                {!! nl2br(e($khoahoc->mo_ta)) !!}
              </div>
            @else
              <span class="text-muted fst-italic">Không có nội dung mô tả chi tiết.</span>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
