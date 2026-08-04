@extends('admin.layouts.main')

@section('title', 'Quản lý Lợi ích khóa học — Hányǔ Admin')

@section('content')
<div class="page-header animate-fade-in delay-1">
  <div>
    <h1 class="fs-4 fw-bold mb-1">Quản lý Lợi ích khóa học</h1>
    <p class="text-muted mb-0 small">Thiết lập các lợi ích nổi bật cho từng khóa học.</p>
  </div>
  <div class="d-flex gap-2">
    <style>
      .btn-excel {
          background-color: #fff;
          color: var(--admin-primary, #0d6efd);
          border: 1px solid var(--admin-primary, #0d6efd);
          transition: all 0.3s ease;
      }
      .btn-excel:hover {
          background-color: var(--admin-primary, #0d6efd);
          color: #fff;
          transform: translateY(-2px);
          box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2) !important;
      }
      .btn-excel:hover svg {
          stroke: #fff;
      }
    </style>
    <button class="btn btn-excel d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#importModal">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
      Nhập từ Excel
    </button>
    <button class="btn btn-primary d-flex align-items-center gap-2 shadow-sm" style="background: var(--admin-primary); border: none;" data-bs-toggle="modal" data-bs-target="#addLoiIchModal">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
      Thêm lợi ích
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
    <form action="{{ route('admin.khoahocloiich.index') }}" method="GET" class="d-flex flex-wrap gap-3 w-100">
        <div class="input-group" style="max-width: 300px;">
          <span class="input-group-text bg-white border-end-0 text-muted">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
          </span>
          <input type="text" class="form-control border-start-0 ps-0" name="search" value="{{ request('search') }}" placeholder="Tìm nội dung lợi ích...">
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
          <th class="fw-medium py-3">Nội dung lợi ích</th>
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
              <button class="icon-btn" title="Chỉnh sửa" data-bs-toggle="modal" data-bs-target="#editLoiIchModal{{ $item->id }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
              </button>
              <form action="{{ route('admin.khoahocloiich.destroy', $item->id) }}" method="POST" class="m-0 p-0 d-flex" onsubmit="return confirm('Bạn có chắc chắn muốn xóa lợi ích này?');">
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
            <td colspan="4" class="text-center py-4 text-muted">Chưa có lợi ích nào.</td>
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
<div class="modal fade" id="addLoiIchModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow" style="background: var(--admin-card);">
      <div class="modal-header border-bottom border-light">
        <h5 class="modal-title fw-bold">Thêm lợi ích mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('admin.khoahocloiich.store') }}" method="POST" id="addLoiIchForm">
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
            <label class="form-label fw-medium">Nội dung lợi ích</label>
            <input type="text" class="form-control" name="noi_dung" placeholder="VD: Được cấp chứng chỉ sau khóa học" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium">Thứ tự hiển thị</label>
            <input type="number" class="form-control" name="thu_tu" placeholder="VD: 1" value="{{ old('thu_tu') ?? 1 }}">
          </div>
        </form>
      </div>
      <div class="modal-footer border-top border-light">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy bỏ</button>
        <button type="button" class="btn btn-primary" onclick="document.getElementById('addLoiIchForm').submit()" style="background: var(--admin-primary); border: none;">Lưu lợi ích</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal Import Excel -->
<div class="modal fade" id="importModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow" style="background: var(--admin-card);">
      <div class="modal-header border-bottom border-light">
        <h5 class="modal-title fw-bold">Nhập lợi ích từ Excel</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('admin.khoahocloiich.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label fw-medium">Chọn khóa học áp dụng <span class="text-danger">*</span></label>
            <select class="form-select" name="khoa_hoc_id" required>
              <option value="">-- Chọn khóa học --</option>
              @foreach($khoaHocs as $khoaHoc)
                <option value="{{ $khoaHoc->id }}">{{ $khoaHoc->ten_khoa_hoc }}</option>
              @endforeach
            </select>
            <div class="form-text text-muted small">
              Tất cả lợi ích trong file sẽ được gán cho khóa học này.
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium">Chọn file Excel <span class="text-danger">*</span></label>
            <input type="file" class="form-control" name="file" accept=".xlsx,.xls,.csv" required>
            <div class="form-text text-muted small">
              <strong>Định dạng cột:</strong> <code>noi_dung</code> (bắt buộc), <code>thu_tu</code> (không bắt buộc).
              <br>Nếu không có cột <code>thu_tu</code>, hệ thống sẽ tự động đánh số thứ tự.
            </div>
          </div>

          <div class="alert alert-info small" role="alert">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
            Các bản ghi trùng lặp (cùng khóa học và nội dung) sẽ tự động bỏ qua.
          </div>
        </div>
        <div class="modal-footer border-top border-light">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy bỏ</button>
          <button type="submit" class="btn btn-primary" style="background: var(--admin-primary); border: none;">Tải lên & Nhập</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Modal Sửa (cho từng bản ghi) -->
@foreach($danhsach as $item)
<div class="modal fade" id="editLoiIchModal{{ $item->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow" style="background: var(--admin-card);">
      <div class="modal-header border-bottom border-light">
        <h5 class="modal-title fw-bold">Chỉnh sửa lợi ích</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('admin.khoahocloiich.update', $item->id) }}" method="POST" id="editLoiIchForm{{ $item->id }}">
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
            <label class="form-label fw-medium">Nội dung lợi ích</label>
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
        <button type="button" class="btn btn-primary" onclick="document.getElementById('editLoiIchForm{{ $item->id }}').submit()" style="background: var(--admin-primary); border: none;">Lưu thay đổi</button>
      </div>
    </div>
  </div>
</div>
@endforeach

@endsection