@extends('admin.layouts.main')

@section('title', 'Quản lý Yêu cầu khóa học — Hányǔ Admin')

@section('content')
<div class="page-header animate-fade-in delay-1">
  <div>
    <h1 class="fs-4 fw-bold mb-1">Quản lý Yêu cầu khóa học</h1>
    <p class="text-muted mb-0 small">Thiết lập các yêu cầu đầu vào cho từng khóa học.</p>
  </div>
  <div class="d-flex gap-2">
    <button class="btn btn-outline-secondary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#importExcelModal">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
      Nhập Excel
    </button>
    <button class="btn btn-primary d-flex align-items-center gap-2 shadow-sm" style="background: var(--admin-primary); border: none;" data-bs-toggle="modal" data-bs-target="#addYeuCauModal">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
      Thêm yêu cầu
    </button>
  </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show animate-fade-in" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

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

<div class="table-card animate-fade-in delay-2">
  <div class="table-header d-flex flex-wrap gap-3">
    <form action="{{ route('admin.khoahocyeucau.index') }}" method="GET" class="d-flex flex-wrap gap-3 w-100">
        <div class="input-group" style="max-width: 300px;">
          <span class="input-group-text bg-white border-end-0 text-muted">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
          </span>
          <input type="text" class="form-control border-start-0 ps-0" name="search" value="{{ request('search') }}" placeholder="Tìm nội dung yêu cầu...">
        </div>
        
        <select class="form-select" style="max-width: 250px;" name="khoa_hoc_id" onchange="this.form.submit()">
          <option value="">Tất cả khóa học</option>
          @foreach($khoaHocs as $khoaHoc)
            <option value="{{ $khoaHoc->id }}" {{ request('khoa_hoc_id') == $khoaHoc->id ? 'selected' : '' }}>{{ $khoaHoc->ten_khoa_hoc }}</option>
          @endforeach
        </select>
    </form>
  </div>

  <div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead class="table-light text-muted small">
        <tr>
          <th class="fw-medium px-4 py-3" style="width: 80px;">Thứ tự</th>
          <th class="fw-medium py-3">Nội dung yêu cầu</th>
          <th class="fw-medium py-3">Khóa học áp dụng</th>
          <th class="fw-medium pe-4 py-3 text-end">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        @forelse($danhsach as $item)
        <tr>
          <td class="px-4 py-3 fw-bold text-muted">{{ $item->thu_tu }}</td>
          <td>
            <div class="fw-bold text-dark fs-6">{{ $item->noi_dung }}</div>
          </td>
          <td>
            <span class="badge bg-light text-dark border">{{ $item->khoaHoc->ten_khoa_hoc ?? 'N/A' }}</span>
          </td>
          <td class="text-end pe-4">
            <div class="d-flex justify-content-end align-items-center gap-1">
              <button class="icon-btn" title="Chỉnh sửa" data-bs-toggle="modal" data-bs-target="#editYeuCauModal{{ $item->id }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
              </button>
              <form action="{{ route('admin.khoahocyeucau.destroy', $item->id) }}" method="POST" class="m-0 p-0 d-flex" onsubmit="return confirm('Bạn có chắc chắn muốn xóa yêu cầu này?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="icon-btn text-danger" title="Xóa">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path></svg>
                  </button>
              </form>
            </div>
          </td>
        </tr>

        @empty
        <tr>
            <td colspan="4" class="text-center py-4 text-muted">Chưa có yêu cầu nào.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <!-- Phân trang -->
  <div class="table-footer d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
    <span class="text-muted small">Hiển thị {{ $danhsach->firstItem() ?? 0 }} – {{ $danhsach->lastItem() ?? 0 }} trên tổng {{ $danhsach->total() }} bản ghi</span>
    {{ $danhsach->appends(request()->query())->links() }}
  </div>
</div>

<!-- Modal Thêm mới -->
<div class="modal fade" id="addYeuCauModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow" style="background: var(--admin-card);">
      <div class="modal-header border-bottom border-light">
        <h5 class="modal-title fw-bold">Thêm yêu cầu mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('admin.khoahocyeucau.store') }}" method="POST" id="addYeuCauForm">
          @csrf
          
          <div class="mb-3">
            <label class="form-label fw-medium">Khóa học áp dụng</label>
            <select class="form-select" name="khoa_hoc_id" required>
              <option value="">-- Chọn khóa học --</option>
              @foreach($khoaHocs as $khoaHoc)
                <option value="{{ $khoaHoc->id }}" {{ request('khoa_hoc_id') == $khoaHoc->id ? 'selected' : '' }}>{{ $khoaHoc->ten_khoa_hoc }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium">Nội dung yêu cầu</label>
            <input type="text" class="form-control" name="noi_dung" placeholder="VD: Có trình độ HSK 3 trở lên" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium">Thứ tự hiển thị</label>
            <input type="number" class="form-control" name="thu_tu" placeholder="VD: 1" value="{{ old('thu_tu') ?? 1 }}">
          </div>
        </form>
      </div>
      <div class="modal-footer border-top border-light">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy bỏ</button>
        <button type="button" class="btn btn-primary" onclick="document.getElementById('addYeuCauForm').submit()" style="background: var(--admin-primary); border: none;">Lưu yêu cầu</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Sửa (cho từng bản ghi) -->
@foreach($danhsach as $item)
<div class="modal fade" id="editYeuCauModal{{ $item->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow" style="background: var(--admin-card);">
      <div class="modal-header border-bottom border-light">
        <h5 class="modal-title fw-bold">Chỉnh sửa yêu cầu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('admin.khoahocyeucau.update', $item->id) }}" method="POST" id="editYeuCauForm{{ $item->id }}">
          @csrf
          @method('PUT')
          
          <div class="mb-3">
            <label class="form-label fw-medium">Khóa học áp dụng</label>
            <select class="form-select" name="khoa_hoc_id" required>
              <option value="">-- Chọn khóa học --</option>
              @foreach($khoaHocs as $khoaHoc)
                <option value="{{ $khoaHoc->id }}" {{ $item->khoa_hoc_id == $khoaHoc->id ? 'selected' : '' }}>{{ $khoaHoc->ten_khoa_hoc }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium">Nội dung yêu cầu</label>
            <input type="text" class="form-control" name="noi_dung" value="{{ $item->noi_dung }}" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium">Thứ tự hiển thị</label>
            <input type="number" class="form-control" name="thu_tu" value="{{ $item->thu_tu }}">
          </div>
        </form>
      </div>
      <div class="modal-footer border-top border-light">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy bỏ</button>
        <button type="button" class="btn btn-primary" onclick="document.getElementById('editYeuCauForm{{ $item->id }}').submit()" style="background: var(--admin-primary); border: none;">Lưu thay đổi</button>
      </div>
    </div>
  </div>
</div>
@endforeach

<!-- Modal Import Excel -->
<div class="modal fade" id="importExcelModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow" style="background: var(--admin-card);">
      <div class="modal-header border-bottom border-light">
        <h5 class="modal-title fw-bold">Nhập yêu cầu từ Excel</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('admin.khoahocyeucau.import') }}" method="POST" enctype="multipart/form-data" id="importExcelForm">
          @csrf
          <div class="mb-3">
            <label class="form-label fw-medium">Chọn khóa học</label>
            <select class="form-select" name="khoa_hoc_id" required>
              <option value="">-- Chọn khóa học áp dụng --</option>
              @foreach($khoaHocs as $khoaHoc)
                <option value="{{ $khoaHoc->id }}" {{ request('khoa_hoc_id') == $khoaHoc->id ? 'selected' : '' }}>{{ $khoaHoc->ten_khoa_hoc }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-medium">File Excel (.xlsx, .xls, .csv)</label>
            <input type="file" class="form-control" name="file" accept=".xlsx, .xls, .csv" required>
            <small class="text-muted mt-1 d-block">Lưu ý: File Excel phải có cột tiêu đề là <b>noi_dung</b> (bắt buộc) và <b>thu_tu</b> (tùy chọn).</small>
          </div>
        </form>
      </div>
      <div class="modal-footer border-top border-light">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
        <button type="button" class="btn btn-success" onclick="document.getElementById('importExcelForm').submit()">Tiến hành Nhập</button>
      </div>
    </div>
  </div>
</div>

@endsection