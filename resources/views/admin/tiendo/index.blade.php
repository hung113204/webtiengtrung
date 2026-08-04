@extends('admin.layouts.main')

@section('title', 'Quản lý Tiến độ Học tập — Hányǔ Admin')

@section('content')
<div class="page-header animate-fade-in delay-1 mb-4 d-flex justify-content-between align-items-center">
  <div>
    <h1 class="fs-4 fw-bold mb-1" style="letter-spacing: -0.01em;">Quản lý Tiến độ Học tập</h1>
    <p class="text-muted mb-0 small">Theo dõi quá trình học tập, tỷ lệ hoàn thành khóa học của học viên.</p>
  </div>
  <button class="btn btn-primary d-flex align-items-center gap-2 shadow-sm rounded-3 px-3 py-2" style="background: var(--admin-primary); border: none;" onclick="window.print()">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
    <span class="fw-medium">Xuất báo cáo</span>
  </button>
</div>

<!-- Alert notifications -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show animate-fade-in mb-4" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Filters & Stats -->
<div class="row mb-4 animate-fade-in delay-2">
  <div class="col-md-4 mb-3 mb-md-0">
    <div class="card border-0 shadow-sm h-100" style="background: var(--admin-card); border-radius: 16px;">
      <div class="card-body d-flex align-items-center gap-3 p-4">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(59, 130, 246, 0.1); color: #3b82f6; display: flex; align-items: center; justify-content: center;">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
        </div>
        <div>
          <div class="text-muted small fw-medium">Học viên tham gia</div>
          <div class="fs-4 fw-bold text-dark">{{ number_format($totalStudents) }}</div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4 mb-3 mb-md-0">
    <div class="card border-0 shadow-sm h-100" style="background: var(--admin-card); border-radius: 16px;">
      <div class="card-body d-flex align-items-center gap-3 p-4">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(220, 38, 38, 0.1); color: var(--admin-primary); display: flex; align-items: center; justify-content: center;">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        </div>
        <div>
          <div class="text-muted small fw-medium">Tỷ lệ hoàn thành TB</div>
          <div class="fs-4 fw-bold text-dark">{{ $avgProgress }}%</div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card border-0 shadow-sm h-100" style="background: var(--admin-card); border-radius: 16px;">
      <div class="card-body d-flex align-items-center gap-3 p-4">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(16, 185, 129, 0.1); color: #10b981; display: flex; align-items: center; justify-content: center;">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 22h14"></path><path d="M5 2h14"></path><path d="M17 22v-4.172a2 2 0 0 0-.586-1.414L12 12l-4.414 4.414A2 2 0 0 0 7 17.828V22"></path><path d="M7 2v4.172a2 2 0 0 0 .586 1.414L12 12l4.414-4.414A2 2 0 0 0 17 6.172V2"></path></svg>
        </div>
        <div>
          <div class="text-muted small fw-medium">Chứng chỉ đã cấp</div>
          <div class="fs-4 fw-bold text-dark">{{ number_format($totalCertificates) }}</div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Data Table Card -->
<div class="card border-0 shadow-sm animate-fade-in delay-3" style="background: var(--admin-card); border-radius: 16px; overflow: hidden;">
  <div class="card-header border-0 bg-transparent p-4 pb-0">
    <form method="GET" action="{{ route('admin.tiendo.index') }}" class="row g-3">
      <div class="col-md-4">
        <div class="input-group">
          <span class="input-group-text bg-light border-0 text-muted rounded-start-3 px-3">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
          </span>
          <input type="text" name="search" value="{{ request('search') }}" class="form-control bg-light border-0 ps-0 rounded-end-3" placeholder="Tìm kiếm tên, email học viên...">
        </div>
      </div>
      
      <div class="col-md-8 d-flex justify-content-md-end gap-2">
        <select name="id_khoa_hoc" class="form-select bg-light border-0 rounded-3" style="width: auto; min-width: 200px; box-shadow: none;" onchange="this.form.submit()">
          <option value="">Tất cả khóa học</option>
          @foreach($khoaHocs as $kh)
            <option value="{{ $kh->id }}" {{ request('id_khoa_hoc') == $kh->id ? 'selected' : '' }}>{{ $kh->ten_khoa_hoc }}</option>
          @endforeach
        </select>
        
        @if(request('search') || request('id_khoa_hoc'))
          <a href="{{ route('admin.tiendo.index') }}" class="btn btn-outline-secondary rounded-3 d-flex align-items-center gap-2">
            Xóa lọc
          </a>
        @endif
      </div>
    </form>
  </div>

  <div class="card-body p-0 mt-3">
    <div class="table-responsive">
      <table class="table table-hover mb-0 align-middle">
        <thead class="table-light text-muted small">
          <tr>
            <th class="fw-semibold px-4 py-3 border-0">Học viên</th>
            <th class="fw-semibold py-3 border-0" style="width: 250px;">Khóa học đang học</th>
            <th class="fw-semibold py-3 border-0" style="width: 200px;">Tiến độ</th>
            <th class="fw-semibold py-3 border-0">Hoạt động cuối</th>
            <th class="fw-semibold py-3 border-0">Trạng thái</th>
            <th class="fw-semibold pe-4 py-3 border-0 text-end">Thao tác</th>
          </tr>
        </thead>
        <tbody>
          @forelse($registrations as $reg)
            <tr>
              <td class="px-4 py-3 border-bottom border-light-subtle">
                <div class="d-flex align-items-center gap-3">
                  <img src="https://ui-avatars.com/api/?name={{ urlencode($reg->nguoiDung->ho_ten) }}&background=random" class="rounded-circle" width="36" height="36" alt="Avatar">
                  <div>
                    <div class="fw-semibold text-dark small">{{ $reg->nguoiDung->ho_ten }}</div>
                    <div class="text-muted" style="font-size: 0.75rem;">{{ $reg->nguoiDung->email }}</div>
                  </div>
                </div>
              </td>
              <td class="border-bottom border-light-subtle">
                <div class="small fw-semibold text-dark text-truncate" style="max-width: 220px;" title="{{ $reg->khoaHoc->ten_khoa_hoc }}">
                  {{ $reg->khoaHoc->ten_khoa_hoc }}
                </div>
                <div class="small text-muted mt-1">Bài {{ $reg->completed_lessons }}/{{ $reg->total_lessons }}</div>
              </td>
              <td class="border-bottom border-light-subtle">
                <div class="d-flex align-items-center gap-2">
                  <div class="progress flex-grow-1" style="height: 6px; border-radius: 3px; background: rgba(0,0,0,0.05);">
                    <div class="progress-bar {{ $reg->progress_percent == 100 ? 'bg-success' : ($reg->progress_percent >= 50 ? 'bg-primary' : 'bg-warning') }}" role="progressbar" style="width: {{ $reg->progress_percent }}%;" aria-valuenow="{{ $reg->progress_percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                  <span class="small fw-semibold text-dark">{{ $reg->progress_percent }}%</span>
                </div>
              </td>
              <td class="border-bottom border-light-subtle">
                <div class="small text-dark fw-medium">
                  {{ $reg->last_study_at ? $reg->last_study_at->diffForHumans() : 'Chưa bắt đầu học' }}
                </div>
                <div class="small text-muted" style="font-size: 0.75rem;">
                  {{ $reg->last_study_at ? $reg->last_study_at->format('H:i - d/m/Y') : '' }}
                </div>
              </td>
              <td class="border-bottom border-light-subtle">
                @if($reg->progress_percent == 100)
                  <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle px-2 py-1 rounded-2" style="font-weight: 500; font-size: 0.75rem;">Hoàn thành</span>
                @elseif($reg->progress_percent >= 50)
                  <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle px-2 py-1 rounded-2" style="font-weight: 500; font-size: 0.75rem;">Rất tích cực</span>
                @else
                  <span class="badge bg-warning bg-opacity-10 text-warning border border-warning-subtle px-2 py-1 rounded-2" style="font-weight: 500; font-size: 0.75rem;">Cần nhắc nhở</span>
                @endif
              </td>
              <td class="text-end pe-4 border-bottom border-light-subtle">
                <div class="d-flex justify-content-end gap-1">
                  <a href="{{ route('admin.tiendo.show', $reg->id) }}" class="btn btn-icon btn-light rounded-circle border-0 text-primary" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; padding: 0;" title="Xem chi tiết">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                  </a>
                  <a href="#" class="btn btn-icon btn-light rounded-circle border-0 text-muted" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; padding: 0;" title="Gửi thông báo nhắc nhở" onclick="alert('Đã gửi thông báo nhắc nhở tới học viên!')">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                  </a>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center py-5 text-muted">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mb-2 opacity-50"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                <p class="mb-0">Không tìm thấy tiến độ học tập nào.</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($registrations->hasPages())
      <div class="card-footer bg-transparent border-0 px-4 py-3">
        {{ $registrations->links() }}
      </div>
    @endif
  </div>
</div>
@endsection
