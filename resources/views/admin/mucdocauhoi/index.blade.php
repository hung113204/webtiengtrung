@extends('admin.layouts.main')

@section('title', 'Quản lý Mức độ Câu hỏi — Hányǔ Admin')

@section('content')
<div class="page-header animate-fade-in delay-1">
  <div>
    <h1 class="fs-4 fw-bold mb-1">Quản lý Mức độ Câu hỏi</h1>
    <p class="text-muted mb-0 small">Thiết lập các mức độ khó dễ cho hệ thống ngân hàng câu hỏi.</p>
  </div>
  <button class="btn btn-primary d-flex align-items-center gap-2 shadow-sm" style="background: var(--admin-primary); border: none;" data-bs-toggle="modal" data-bs-target="#addLevelModal">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
    Thêm mức độ
  </button>
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
    <form action="{{ route('admin.mucdocauhoi.index') }}" method="GET" class="d-flex flex-wrap gap-3 w-100">
        <div class="input-group" style="max-width: 300px;">
          <span class="input-group-text bg-white border-end-0 text-muted">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
          </span>
          <input type="text" class="form-control border-start-0 ps-0" name="search" value="{{ request('search') }}" placeholder="Tìm tên mức độ...">
        </div>
    </form>
  </div>

  <div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead class="table-light text-muted small">
        <tr>
          <th class="fw-medium px-4 py-3" style="width: 80px;">Thứ tự</th>
          <th class="fw-medium py-3">Tên mức độ</th>
          <th class="fw-medium py-3">Slug (Đường dẫn)</th>
          <th class="fw-medium py-3 text-center">Số câu hỏi</th>
          <th class="fw-medium pe-4 py-3 text-end">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        @forelse($mucDos as $muc)
        <tr>
          <td class="px-4 py-3 fw-bold text-muted">{{ $muc->thu_tu }}</td>
          <td>
            <div class="fw-bold text-dark fs-6">{{ $muc->ten_muc_do }}</div>
          </td>
          <td>
            <span class="badge bg-light text-dark border">{{ $muc->slug }}</span>
          </td>
          <td class="text-center">
            <span class="badge bg-info bg-opacity-10 text-info border border-info-subtle">{{ $muc->cauHois->count() ?? 0 }} câu hỏi</span>
          </td>
          <td class="text-end pe-4">
            <div class="d-flex justify-content-end align-items-center gap-1">
              <button class="icon-btn" title="Chỉnh sửa" data-bs-toggle="modal" data-bs-target="#editLevelModal{{ $muc->id }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
              </button>
              <form action="{{ route('admin.mucdocauhoi.destroy', $muc->id) }}" method="POST" class="m-0 p-0 d-flex" onsubmit="return confirm('Bạn có chắc chắn muốn xóa mức độ này?');">
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
            <td colspan="5" class="text-center py-4 text-muted">Chưa có mức độ nào.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  
  @if(isset($mucDos) && $mucDos->hasPages())
  <div class="card-footer bg-white border-top p-3 d-flex justify-content-between align-items-center">
    <span class="text-muted small">Hiển thị từ {{ $mucDos->firstItem() }} đến {{ $mucDos->lastItem() }} trong tổng số {{ $mucDos->total() }} bản ghi</span>
    {{ $mucDos->links('pagination::bootstrap-5') }}
  </div>
  @endif
</div>

<!-- Add Modal -->
<div class="modal fade" id="addLevelModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow" style="background: var(--admin-card);">
      <div class="modal-header border-bottom border-light">
        <h5 class="modal-title fw-bold">Thêm Mức độ mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('admin.mucdocauhoi.store') }}" method="POST" id="addLevelForm">
          @csrf
          <div class="mb-3">
            <label class="form-label fw-medium">Tên mức độ</label>
            <input type="text" class="form-control" name="ten_muc_do" placeholder="VD: Rất khó" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-medium">Slug (Không bắt buộc)</label>
            <input type="text" class="form-control" name="slug" placeholder="VD: rat-kho">
            <div class="form-text">Nếu để trống, hệ thống sẽ tự động tạo từ Tên mức độ.</div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-medium">Thứ tự hiển thị (Tùy chọn)</label>
            <input type="number" class="form-control" name="thu_tu" placeholder="Nhỏ xếp trước">
          </div>
        </form>
      </div>
      <div class="modal-footer border-top border-light">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy bỏ</button>
        <button type="button" class="btn btn-primary" onclick="document.getElementById('addLevelForm').submit()" style="background: var(--admin-primary); border: none;">Lưu dữ liệu</button>
      </div>
    </div>
  </div>
</div>

<!-- Edit Modals -->
@foreach($mucDos as $muc)
<div class="modal fade" id="editLevelModal{{ $muc->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow" style="background: var(--admin-card);">
      <div class="modal-header border-bottom border-light">
        <h5 class="modal-title fw-bold">Chỉnh sửa Mức độ</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('admin.mucdocauhoi.update', $muc->id) }}" method="POST" id="editLevelForm{{ $muc->id }}">
          @csrf
          @method('PUT')
          <div class="mb-3">
            <label class="form-label fw-medium">Tên mức độ</label>
            <input type="text" class="form-control" name="ten_muc_do" value="{{ $muc->ten_muc_do }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-medium">Slug (Đường dẫn tĩnh)</label>
            <input type="text" class="form-control" name="slug" value="{{ $muc->slug }}">
            <div class="form-text">Sẽ được tự động cập nhật nếu bạn để trống.</div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-medium">Thứ tự hiển thị</label>
            <input type="number" class="form-control" name="thu_tu" value="{{ $muc->thu_tu }}">
          </div>
        </form>
      </div>
      <div class="modal-footer border-top border-light">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy bỏ</button>
        <button type="button" class="btn btn-primary" onclick="document.getElementById('editLevelForm{{ $muc->id }}').submit()" style="background: var(--admin-primary); border: none;">Lưu thay đổi</button>
      </div>
    </div>
  </div>
</div>
@endforeach
@endsection
