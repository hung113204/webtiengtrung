@extends('admin.layouts.main')

@section('title', 'Quản lý Quyền hạn — Hányǔ Admin')

@section('content')
<div class="page-header animate-fade-in delay-1">
  <div>
    <h1 class="fs-4 fw-bold mb-1">Quản lý Quyền hạn</h1>
    <p class="text-muted mb-0 small">Thiết lập danh sách các quyền hạn thao tác trong hệ thống.</p>
  </div>
  <button class="btn btn-primary d-flex align-items-center gap-2 shadow-sm" style="background: var(--admin-primary); border: none;" data-bs-toggle="modal" data-bs-target="#createModal">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
    Thêm Quyền mới
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
  <div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead class="table-light text-muted small">
        <tr>
          <th class="fw-medium px-4 py-3">Tên Quyền</th>
          <th class="fw-medium py-3">Mã Slug</th>
          <th class="fw-medium py-3 text-center">Nhóm Quyền</th>
          <th class="fw-medium py-3 text-center">Ngày tạo</th>
          <th class="fw-medium pe-4 py-3 text-end">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        @forelse($quyens as $item)
        <tr>
          <td class="px-4 py-3 fw-bold text-dark fs-6">{{ $item->ten_quyen }}</td>
          <td>
            <span class="badge bg-light text-dark border">{{ $item->slug }}</span>
          </td>
          <td class="text-center">
            <span class="badge bg-info bg-opacity-10 text-info border border-info-subtle">{{ $item->nhom_quyen ?? 'Khác' }}</span>
          </td>
          <td class="text-center small text-muted">
            {{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') : 'N/A' }}
          </td>
          <td class="text-end pe-4">
            <div class="d-flex justify-content-end align-items-center gap-1">
              <button class="icon-btn" title="Chỉnh sửa" data-bs-toggle="modal" data-bs-target="#editModal_{{ $item->id }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
              </button>
              <form action="{{ route('admin.quyen.destroy', $item->id) }}" method="POST" class="m-0 p-0 d-flex" onsubmit="return confirm('Bạn có chắc chắn muốn xóa quyền này?');">
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
            <td colspan="5" class="text-center py-4 text-muted">Chưa có quyền hạn nào trong hệ thống.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection

<!-- Modal Thêm Quyền -->
<div class="modal fade" id="createModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow" style="background: var(--admin-card);">
      <div class="modal-header border-bottom border-light">
        <h5 class="modal-title fw-bold">Thêm Quyền mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('admin.quyen.store') }}" method="POST" id="createForm">
          @csrf
          
          <div class="mb-3">
            <label class="form-label fw-medium">Tên quyền <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="ten_quyen" placeholder="VD: Quản lý bài học" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium">Mã Slug <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="slug" placeholder="VD: manage_lessons" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium">Nhóm Quyền</label>
            <input type="text" class="form-control" name="nhom_quyen" placeholder="VD: Khóa học, Hệ thống...">
          </div>
        </form>
      </div>
      <div class="modal-footer border-top border-light">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy bỏ</button>
        <button type="button" class="btn btn-primary" onclick="document.getElementById('createForm').submit()" style="background: var(--admin-primary); border: none;">Lưu thông tin</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Sửa Quyền -->
@foreach($quyens as $item)
<div class="modal fade" id="editModal_{{ $item->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow" style="background: var(--admin-card);">
      <div class="modal-header border-bottom border-light">
        <h5 class="modal-title fw-bold">Chỉnh sửa Quyền</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('admin.quyen.update', $item->id) }}" method="POST" id="editForm{{ $item->id }}">
          @csrf
          @method('PUT')
          
          <div class="mb-3">
            <label class="form-label fw-medium">Tên quyền <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="ten_quyen" value="{{ $item->ten_quyen }}" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium">Mã Slug <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="slug" value="{{ $item->slug }}" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium">Nhóm Quyền</label>
            <input type="text" class="form-control" name="nhom_quyen" value="{{ $item->nhom_quyen }}">
          </div>
        </form>
      </div>
      <div class="modal-footer border-top border-light">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy bỏ</button>
        <button type="button" class="btn btn-primary" onclick="document.getElementById('editForm{{ $item->id }}').submit()" style="background: var(--admin-primary); border: none;">Lưu thay đổi</button>
      </div>
    </div>
  </div>
</div>
@endforeach
