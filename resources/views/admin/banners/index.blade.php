@extends('admin.layouts.main')

@section('title', 'Quản lý Banner Trang Chủ')

@section('content')
<div class="page-header animate-fade-in delay-1">
    <div>
        <h1 class="fs-4 fw-bold mb-1">Quản lý Banner Trang Chủ</h1>
        <p class="text-muted mb-0 small">Thêm, sửa, xóa các banner hiển thị ở trang chủ.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.banners.create') }}" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm" style="background: var(--admin-primary); border: none;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Thêm Banner mới
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show animate-fade-in delay-1" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="table-card animate-fade-in delay-2">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light text-muted small">
                <tr>
                    <th class="fw-medium py-3" style="width: 80px;">ID</th>
                    <th class="fw-medium py-3">Tiêu đề (Prefix)</th>
                    <th class="fw-medium py-3">Tiêu đề Highlight</th>
                    <th class="fw-medium py-3 text-center">Thứ tự</th>
                    <th class="fw-medium py-3 text-center">Trạng thái</th>
                    <th class="fw-medium pe-4 py-3 text-end" style="width: 120px;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($banners as $item)
                <tr>
                    <td class="py-3 text-muted">{{ $item->id }}</td>
                    <td class="py-3 fw-medium">{{ $item->title_prefix }}</td>
                    <td class="py-3 text-primary">{{ $item->title_highlight }}</td>
                    <td class="text-center fw-medium">{{ $item->thu_tu }}</td>
                    <td class="text-center">
                        @if($item->is_active)
                            <span class="badge bg-success">Đang hiển thị</span>
                        @else
                            <span class="badge bg-secondary">Đang ẩn</span>
                        @endif
                    </td>
                    <td class="text-end pe-4">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ route('admin.banners.edit', $item->id) }}" class="icon-btn text-primary" title="Chỉnh sửa">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            </a>
                            <form action="{{ route('admin.banners.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa banner này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="icon-btn text-danger" title="Xóa" style="border: none; background: transparent;">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">Chưa có banner nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
