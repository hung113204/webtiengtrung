@extends('admin.layouts.main')

@section('title', 'Quản lý Chương học — Hányǔ Admin')

@section('content')
<div class="page-header animate-fade-in delay-1">
  <div>
    <h1 class="fs-4 fw-bold mb-1">Quản lý Chương học</h1>
    <p class="text-muted mb-0 small">Thiết lập cấu trúc chương cho từng khóa học.</p>
  </div>
  <div class="d-flex gap-2">
      <button class="btn btn-success d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#importExcelModal">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
        Nhập Excel
      </button>
      <button class="btn btn-primary d-flex align-items-center gap-2 shadow-sm" style="background: var(--admin-primary); border: none;" data-bs-toggle="modal" data-bs-target="#addChapterModal">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
        Thêm chương học
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
    <form action="{{ route('admin.chuonghoc.index') }}" method="GET" class="d-flex flex-wrap gap-3 w-100">
        <div class="input-group" style="max-width: 300px;">
          <span class="input-group-text bg-white border-end-0 text-muted">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
          </span>
          <input type="text" class="form-control border-start-0 ps-0" name="search" value="{{ request('search') }}" placeholder="Tìm tên chương học...">
        </div>
        
        <select class="form-select" style="max-width: 250px;" name="id_khoa_hoc" onchange="this.form.submit()">
          <option value="">Tất cả khóa học</option>
          @foreach($khoaHocs as $khoaHoc)
            <option value="{{ $khoaHoc->id }}" {{ request('id_khoa_hoc') == $khoaHoc->id ? 'selected' : '' }}>{{ $khoaHoc->ten_khoa_hoc }}</option>
          @endforeach
        </select>
    </form>
  </div>

  <div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead class="table-light text-muted small">
        <tr>
          <th class="fw-medium px-4 py-3" style="width: 80px;">Thứ tự</th>
          <th class="fw-medium py-3">Tên chương</th>
          <th class="fw-medium py-3">Khóa học trực thuộc</th>
          <th class="fw-medium py-3 text-center">Số bài học</th>
          <th class="fw-medium py-3">Trạng thái</th>
          <th class="fw-medium pe-4 py-3 text-end">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        @forelse($chuongHocs as $chuong)
        <tr>
          <td class="px-4 py-3 fw-bold text-muted">{{ $chuong->thu_tu }}</td>
          <td>
            <div class="fw-bold text-dark fs-6">{{ $chuong->ten_chuong }}</div>
            @if($chuong->mo_ta)
              <div class="small text-muted text-truncate" style="max-width: 250px;">{{ $chuong->mo_ta }}</div>
            @endif
          </td>
          <td>
            <span class="badge bg-light text-dark border">{{ $chuong->khoaHoc->ten_khoa_hoc ?? 'N/A' }}</span>
          </td>
          <td class="text-center">
            <span class="badge bg-info bg-opacity-10 text-info border border-info-subtle">{{ $chuong->bai_hocs_count ?? 0 }} bài học</span>
          </td>
          <td>
            @if($chuong->trang_thai)
                <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle">Đang hiển thị</span>
            @else
                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle">Đang ẩn</span>
            @endif
          </td>
          <td class="text-end pe-4">
            <div class="d-flex justify-content-end align-items-center gap-1">
              <button class="icon-btn" title="Chỉnh sửa" data-bs-toggle="modal" data-bs-target="#editChapterModal{{ $chuong->id }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
              </button>
              <form action="{{ route('admin.chuonghoc.destroy', $chuong->id) }}" method="POST" class="m-0 p-0 d-flex" onsubmit="return confirm('Xóa chương này có thể xóa cả bài học bên trong. Bạn có chắc chắn?');">
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
            <td colspan="6" class="text-center py-4 text-muted">Chưa có chương học nào.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addChapterModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow" style="background: var(--admin-card);">
      <div class="modal-header border-bottom border-light">
        <h5 class="modal-title fw-bold">Thêm Chương học mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('admin.chuonghoc.store') }}" method="POST" id="addChapterForm">
          @csrf
          
          <div class="mb-3">
            <label class="form-label fw-medium">Khóa học trực thuộc</label>
            <select class="form-select" name="id_khoa_hoc" required>
              <option value="">-- Chọn khóa học --</option>
              @foreach($khoaHocs as $khoaHoc)
                <option value="{{ $khoaHoc->id }}" {{ request('id_khoa_hoc') == $khoaHoc->id ? 'selected' : '' }}>{{ $khoaHoc->ten_khoa_hoc }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium">Tên chương học</label>
            <input type="text" class="form-control" name="ten_chuong" placeholder="VD: Chương 1: Xin chào" required>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium">Thứ tự hiển thị</label>
              <input type="number" class="form-control" name="thu_tu" placeholder="VD: 1" value="1">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium">Trạng thái</label>
              <select class="form-select" name="trang_thai" required>
                <option value="1">Hiển thị</option>
                <option value="0">Ẩn</option>
              </select>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium">Mô tả (Không bắt buộc)</label>
            <textarea class="form-control" name="mo_ta" rows="2" placeholder="Nội dung ngắn gọn mô tả chương học này..."></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer border-top border-light">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy bỏ</button>
        <button type="button" class="btn btn-primary" onclick="document.getElementById('addChapterForm').submit()" style="background: var(--admin-primary); border: none;">Lưu chương học</button>
      </div>
    </div>
  </div>
</div>

<!-- Import Excel Modal -->
<div class="modal fade" id="importExcelModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow" style="background: var(--admin-card);">
      <div class="modal-header border-bottom border-light">
        <h5 class="modal-title fw-bold">Nhập Chương học từ Excel</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('admin.chuonghoc.import') }}" method="POST" enctype="multipart/form-data" id="importExcelForm">
          @csrf
          
          <div class="mb-3">
            <label class="form-label fw-medium">Khóa học trực thuộc <span class="text-danger">*</span></label>
            <select class="form-select" name="id_khoa_hoc" required>
              <option value="">-- Chọn khóa học --</option>
              @foreach($khoaHocs as $khoaHoc)
                <option value="{{ $khoaHoc->id }}">{{ $khoaHoc->ten_khoa_hoc }}</option>
              @endforeach
            </select>
            <small class="text-muted mt-1 d-block">Tất cả các chương trong file Excel sẽ được thêm vào khóa học này.</small>
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium">File Excel (.xlsx, .xls) <span class="text-danger">*</span></label>
            <input type="file" class="form-control" name="file" accept=".xlsx, .xls, .csv" required>
          </div>

          <div class="alert alert-info py-2 small mb-0">
            <strong>Cấu trúc file Excel yêu cầu (Bỏ qua dòng tiêu đề):</strong>
            <ul class="mb-0 mt-1 ps-3">
              <li><strong>Cột A:</strong> Tên chương (Bắt buộc)</li>
              <li><strong>Cột B:</strong> Thứ tự hiển thị (Số - Bỏ trống sẽ tự tăng)</li>
              <li><strong>Cột C:</strong> Trạng thái (1: Hiển thị, 0: Ẩn - Mặc định là 1)</li>
              <li><strong>Cột D:</strong> Mô tả (Không bắt buộc)</li>
            </ul>
          </div>
        </form>
      </div>
      <div class="modal-footer border-top border-light">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy bỏ</button>
        <button type="button" class="btn btn-success" onclick="document.getElementById('importExcelForm').submit()">Nhập dữ liệu</button>
      </div>
    </div>
  </div>
</div>
@endsection

<!-- Modals Sửa Chương Học -->
@foreach($chuongHocs as $chuong)
<div class="modal fade" id="editChapterModal{{ $chuong->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow" style="background: var(--admin-card);">
      <div class="modal-header border-bottom border-light">
        <h5 class="modal-title fw-bold">Chỉnh sửa Chương học</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('admin.chuonghoc.update', $chuong->id) }}" method="POST" id="editChapterForm{{ $chuong->id }}">
          @csrf
          @method('PUT')
          
          <div class="mb-3">
            <label class="form-label fw-medium">Khóa học trực thuộc</label>
            <select class="form-select" name="id_khoa_hoc" required>
              <option value="">-- Chọn khóa học --</option>
              @foreach($khoaHocs as $khoaHoc)
                <option value="{{ $khoaHoc->id }}" {{ $chuong->id_khoa_hoc == $khoaHoc->id ? 'selected' : '' }}>{{ $khoaHoc->ten_khoa_hoc }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium">Tên chương học</label>
            <input type="text" class="form-control" name="ten_chuong" value="{{ $chuong->ten_chuong }}" required>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium">Thứ tự hiển thị</label>
              <input type="number" class="form-control" name="thu_tu" value="{{ $chuong->thu_tu }}">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium">Trạng thái</label>
              <select class="form-select" name="trang_thai" required>
                <option value="1" {{ $chuong->trang_thai ? 'selected' : '' }}>Hiển thị</option>
                <option value="0" {{ !$chuong->trang_thai ? 'selected' : '' }}>Ẩn</option>
              </select>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium">Mô tả (Không bắt buộc)</label>
            <textarea class="form-control" name="mo_ta" rows="2">{{ $chuong->mo_ta }}</textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer border-top border-light">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy bỏ</button>
        <button type="button" class="btn btn-primary" onclick="document.getElementById('editChapterForm{{ $chuong->id }}').submit()" style="background: var(--admin-primary); border: none;">Lưu thay đổi</button>
      </div>
    </div>
  </div>
</div>
@endforeach
