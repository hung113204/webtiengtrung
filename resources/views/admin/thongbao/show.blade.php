@extends('admin.layouts.main')

@section('title', 'Chi tiết Thông Báo — Hányǔ Admin')

@section('content')
<div class="page-header animate-fade-in delay-1 mb-4 d-flex justify-content-between align-items-center">
  <div>
    <h1 class="fs-4 fw-bold mb-1" style="letter-spacing: -0.01em;">Chi tiết Thông Báo</h1>
    <p class="text-muted mb-0 small">Theo dõi nội dung và tỷ lệ đọc của học viên.</p>
  </div>
  <div class="d-flex gap-2">
    <a href="{{ route('admin.thongbao.index') }}" class="btn btn-light border d-flex align-items-center gap-2 rounded-3">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
      Quay lại
    </a>
  </div>
</div>

<div class="row">
  <!-- Left Column: Content details -->
  <div class="col-lg-8 mb-4">
    <div class="card border-0 shadow-sm animate-fade-in delay-2" style="background: var(--admin-card); border-radius: 16px;">
      <div class="card-body p-4 p-md-5">
        <div class="d-flex align-items-center gap-3 mb-4">
          <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(59, 130, 246, 0.1); color: #3b82f6; display: flex; align-items: center; justify-content: center;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
          </div>
          <div>
            <h2 class="fs-5 fw-bold text-dark mb-0.5">{{ $notification->tieu_de }}</h2>
            <div class="text-muted small">
              Người gửi: <span class="fw-semibold text-dark">{{ $notification->nguoiGui->ho_ten ?? 'Hệ thống' }}</span> &bull; 
              Thời gian: <span class="fw-semibold text-dark">{{ $notification->created_at ? $notification->created_at->format('H:i d/m/Y') : '' }}</span>
            </div>
          </div>
        </div>

        <hr class="border-light mb-4">

        <h6 class="fw-bold text-secondary small mb-3">Nội dung thông báo</h6>
        <div class="text-dark p-4 rounded-4 bg-light" style="line-height: 1.6; font-size: 0.95rem; white-space: pre-wrap;">{{ $notification->noi_dung }}</div>
      </div>
    </div>
  </div>

  <!-- Right Column: Recipient details and read ratios -->
  <div class="col-lg-4 mb-4">
    <!-- Stat card -->
    <div class="card border-0 shadow-sm animate-fade-in delay-2 mb-4" style="background: var(--admin-card); border-radius: 16px;">
      <div class="card-body p-4">
        <h5 class="fw-bold text-dark mb-4 fs-6">Trạng thái tiếp nhận</h5>

        <div class="row text-center mb-4">
          <div class="col-4 border-end">
            <div class="text-muted small mb-1">Học viên nhận</div>
            <div class="fs-5 fw-bold text-dark">{{ $recipientsCount }}</div>
          </div>
          <div class="col-4 border-end">
            <div class="text-success small mb-1">Đã đọc</div>
            <div class="fs-5 fw-bold text-success">{{ $readCount }}</div>
          </div>
          <div class="col-4">
            <div class="text-muted small mb-1">Chưa đọc</div>
            <div class="fs-5 fw-bold text-secondary">{{ $unreadCount }}</div>
          </div>
        </div>

        <!-- Progress bar -->
        @php
          $rate = $recipientsCount > 0 ? round(($readCount / $recipientsCount) * 100) : 0;
        @endphp
        <div class="mb-2 d-flex justify-content-between align-items-center">
          <span class="text-muted small fw-bold">Tỷ lệ đọc</span>
          <span class="text-success small fw-bold">{{ $rate }}%</span>
        </div>
        <div class="progress rounded-pill mb-1" style="height: 8px;">
          <div class="progress-bar bg-success rounded-pill" role="progressbar" style="width: {{ $rate }}%" aria-valuenow="{{ $rate }}" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
      </div>
    </div>

    <!-- Recipient table list -->
    <div class="card border-0 shadow-sm animate-fade-in delay-3" style="background: var(--admin-card); border-radius: 16px;">
      <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="fw-bold text-dark mb-0 fs-6">Danh sách nhận</h5>
          <input type="text" id="recipient-search" class="form-control form-control-sm border-0 bg-light rounded-3" placeholder="Lọc theo tên..." style="max-width: 150px;">
        </div>

        <!-- Scrollable list of recipients -->
        <div style="max-height: 380px; overflow-y: auto;" class="pe-1">
          <div class="list-group list-group-flush" id="recipient-list">
            @forelse($notification->nguoiDungs as $user)
              <div class="list-group-item px-0 py-3 border-light d-flex justify-content-between align-items-center recipient-row">
                <div style="max-width: 70%;">
                  <div class="fw-semibold text-dark small recipient-name">{{ $user->ho_ten }}</div>
                  <div class="text-muted text-truncate" style="font-size: 0.75rem;">{{ $user->email }}</div>
                </div>
                <div>
                  @if($user->pivot->da_doc)
                    <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle px-2 py-1 rounded-pill" style="font-size: 0.7rem;">Đã đọc</span>
                  @else
                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle px-2 py-1 rounded-pill" style="font-size: 0.7rem;">Chưa đọc</span>
                  @endif
                </div>
              </div>
            @empty
              <div class="text-center py-4 text-muted small">Không có học viên nhận.</div>
            @endforelse
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('recipient-search');
    const rows = document.querySelectorAll('.recipient-row');

    searchInput.addEventListener('input', function() {
      const query = this.value.toLowerCase().trim();
      
      rows.forEach(row => {
        const name = row.querySelector('.recipient-name').textContent.toLowerCase();
        if (name.includes(query)) {
          row.style.setProperty('display', 'flex', 'important');
        } else {
          row.style.setProperty('display', 'none', 'important');
        }
      });
    });
  });
</script>
@endsection
