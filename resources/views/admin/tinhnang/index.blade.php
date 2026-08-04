@extends('admin.layouts.main')

@section('title', 'Quản lý Tính năng — Hányǔ Admin')

@section('content')
<div class="page-header animate-fade-in delay-1">
  <div>
    <h1 class="fs-4 fw-bold mb-1">Quản lý Tính năng</h1>
    <p class="text-muted mb-0 small">Danh sách các khối tính năng hiển thị trên trang chủ và trang Tính năng.</p>
  </div>
  <a href="{{ route('admin.tinhnang.create') }}" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm" style="background: var(--admin-primary); border: none;">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Thêm Tính năng
  </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show animate-fade-in delay-2" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card bg-white shadow-sm border-0 rounded-3 mb-4 animate-fade-in delay-3">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th class="ps-4">Thứ tự</th>
          <th>Hình ảnh</th>
          <th>Tiêu đề</th>
          <th>Huy hiệu (Badge)</th>
          <th>Trạng thái</th>
          <th class="text-end pe-4">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        @forelse($tinhNangs as $item)
        <tr>
          <td class="ps-4 text-muted">{{ $item->thu_tu }}</td>
          <td>
            @if($item->image_url)
              <img src="{{ $item->image_url }}" alt="img" class="rounded" style="width:60px; height:40px; object-fit:cover;">
            @else
              <span class="text-muted small">Không có</span>
            @endif
          </td>
          <td class="fw-medium text-dark">{{ $item->tieu_de }}</td>
          <td><span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $item->badge_text }}</span></td>
          <td>
            @if($item->trang_thai)
              <span class="badge bg-success bg-opacity-10 text-success">Hiển thị</span>
            @else
              <span class="badge bg-danger bg-opacity-10 text-danger">Đang ẩn</span>
            @endif
          </td>
          <td class="text-end pe-4">
            <div class="d-flex justify-content-end gap-2">
              <a href="{{ route('admin.tinhnang.edit', $item->id) }}" class="btn btn-sm btn-light text-primary" title="Sửa">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              </a>
              <form action="{{ route('admin.tinhnang.destroy', $item->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tính năng này?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-light text-danger" title="Xóa">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" class="text-center py-4 text-muted">Chưa có dữ liệu tính năng.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
