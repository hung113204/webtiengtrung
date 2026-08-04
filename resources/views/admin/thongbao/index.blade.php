@extends('admin.layouts.main')

@section('title', 'Quản lý Thông báo — Hányǔ Admin')

@section('content')
<div class="page-header animate-fade-in delay-1 mb-4 d-flex justify-content-between align-items-center">
  <div>
    <h1 class="fs-4 fw-bold mb-1" style="letter-spacing: -0.01em;">Quản lý Thông báo</h1>
    <p class="text-muted mb-0 small">Soạn và gửi thông báo hệ thống đến toàn bộ học viên hoặc các cá nhân chỉ định.</p>
  </div>
  <a href="{{ route('admin.thongbao.create') }}" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm" style="background: var(--admin-primary); border: none;">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
    Tạo thông báo mới
  </a>
</div>

<!-- Alert notifications -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show animate-fade-in mb-4" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Stats Row -->
<div class="row mb-4 animate-fade-in delay-2">
  <div class="col-md-3 mb-3 mb-md-0">
    <div class="card border-0 shadow-sm h-100" style="background: var(--admin-card); border-radius: 16px;">
      <div class="card-body d-flex align-items-center gap-3 p-4">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(59, 130, 246, 0.1); color: #3b82f6; display: flex; align-items: center; justify-content: center;">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
        </div>
        <div>
          <div class="text-muted small fw-medium">Tổng thông báo gửi</div>
          <div class="fs-5 fw-bold text-dark">{{ number_format($totalNotifications) }}</div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3 mb-3 mb-md-0">
    <div class="card border-0 shadow-sm h-100" style="background: var(--admin-card); border-radius: 16px;">
      <div class="card-body d-flex align-items-center gap-3 p-4">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(16, 185, 129, 0.1); color: #10b981; display: flex; align-items: center; justify-content: center;">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
        </div>
        <div>
          <div class="text-muted small fw-medium">Lượt người nhận</div>
          <div class="fs-5 fw-bold text-dark">{{ number_format($totalRecipients) }}</div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3 mb-3 mb-md-0">
    <div class="card border-0 shadow-sm h-100" style="background: var(--admin-card); border-radius: 16px;">
      <div class="card-body d-flex align-items-center gap-3 p-4">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(245, 158, 11, 0.1); color: #f59e0b; display: flex; align-items: center; justify-content: center;">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
        </div>
        <div>
          <div class="text-muted small fw-medium">Lượt đã đọc</div>
          <div class="fs-5 fw-bold text-dark">{{ number_format($totalRead) }}</div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card border-0 shadow-sm h-100" style="background: var(--admin-card); border-radius: 16px;">
      <div class="card-body d-flex align-items-center gap-3 p-4">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(139, 92, 246, 0.1); color: #8b5cf6; display: flex; align-items: center; justify-content: center;">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
        </div>
        <div>
          <div class="text-muted small fw-medium">Tỷ lệ đọc</div>
          <div class="fs-5 fw-bold text-dark">{{ $readRate }}%</div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Search and List -->
<div class="card border-0 shadow-sm animate-fade-in delay-3 mb-5" style="background: var(--admin-card); border-radius: 16px;">
  <div class="card-header border-0 bg-transparent p-4 pb-0">
    <form method="GET" action="{{ route('admin.thongbao.index') }}" class="row g-3">
      <div class="col-md-4">
        <div class="input-group">
          <span class="input-group-text bg-light border-0 text-muted rounded-start-3 px-3">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
          </span>
          <input type="text" name="search" value="{{ request('search') }}" class="form-control bg-light border-0 ps-0 rounded-end-3" placeholder="Tìm kiếm tiêu đề, nội dung...">
        </div>
      </div>
      @if(request('search'))
        <div class="col-md-2">
          <a href="{{ route('admin.thongbao.index') }}" class="btn btn-outline-secondary rounded-3 d-flex align-items-center justify-content-center gap-2 h-100">
            Xóa lọc
          </a>
        </div>
      @endif
    </form>
  </div>

  <div class="card-body p-4">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th class="border-0 rounded-start-3" style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: #6b7280; padding: 12px 16px;">Tiêu Đề Thông Báo</th>
            <th class="border-0" style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: #6b7280; padding: 12px 16px;">Người gửi</th>
            <th class="border-0" style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: #6b7280; padding: 12px 16px; text-align: center;">Số người nhận</th>
            <th class="border-0" style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: #6b7280; padding: 12px 16px;">Ngày gửi</th>
            <th class="border-0 rounded-end-3 text-end" style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: #6b7280; padding: 12px 16px; width: 150px;">Thao tác</th>
          </tr>
        </thead>
        <tbody>
          @forelse($notifications as $notification)
            <tr class="align-middle">
              <td class="px-3 py-3">
                <span class="fw-bold text-dark fs-6 d-block">{{ $notification->tieu_de }}</span>
                <span class="text-muted small text-truncate d-block" style="max-width: 400px; font-size: 0.8rem;">{!! strip_tags($notification->noi_dung) !!}</span>
              </td>
              <td class="px-3">
                <span class="fw-medium text-dark">{{ $notification->nguoiGui->ho_ten ?? 'Hệ thống' }}</span>
              </td>
              <td class="px-3 text-center">
                <span class="badge bg-info bg-opacity-10 text-info border border-info-subtle px-2.5 py-1.5 rounded" style="font-size: 0.8rem;">
                  {{ $notification->nguoi_dungs_count }} học viên
                </span>
              </td>
              <td class="px-3 text-muted small">
                {{ $notification->created_at ? $notification->created_at->format('H:i d/m/Y') : '' }}
              </td>
              <td class="text-end pe-4">
                <div class="d-flex justify-content-end align-items-center gap-1">
                  <!-- View details button -->
                  <a href="{{ route('admin.thongbao.show', $notification->id) }}" class="icon-btn" title="Xem chi tiết">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                  </a>

                  <!-- Delete button -->
                  <form action="{{ route('admin.thongbao.destroy', $notification->id) }}" method="POST" class="m-0 p-0 d-flex" onsubmit="return confirm('Bạn có chắc chắn muốn xóa thông báo này? Người nhận sẽ không nhìn thấy thông báo này nữa.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="icon-btn text-danger" title="Xóa">
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center py-5 text-muted">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mb-2 text-muted"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                <p class="mb-0 small">Chưa có thông báo nào được gửi.</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
      {{ $notifications->links() }}
    </div>
  </div>
</div>
@endsection
