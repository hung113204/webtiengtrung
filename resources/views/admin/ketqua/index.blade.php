@extends('admin.layouts.main')

@section('title', 'Quản lý Kết quả Luyện thi — Hányǔ Admin')

@section('content')
<div class="page-header animate-fade-in delay-1 mb-4 d-flex justify-content-between align-items-center">
  <div>
    <h1 class="fs-4 fw-bold mb-1" style="letter-spacing: -0.01em;">Quản lý Kết quả Luyện thi</h1>
    <p class="text-muted mb-0 small">Theo dõi chi tiết điểm số, thời gian làm bài và kết quả thi của học viên.</p>
  </div>
</div>

<!-- Alert notifications -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show animate-fade-in mb-4" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Stats Row -->
<div class="row g-4 mb-4 animate-fade-in delay-2">
  <div class="col-md-4">
    <div class="card border-0 shadow-sm h-100 rounded-4 bg-white" style="transition: transform 0.2s ease;">
      <div class="card-body p-4 d-flex align-items-center gap-4">
        <div style="width: 56px; height: 56px; border-radius: 14px; background: rgba(59, 130, 246, 0.08); color: #3b82f6; display: flex; align-items: center; justify-content: center;">
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        </div>
        <div>
          <div class="text-muted small fw-medium mb-1">Tổng số lượt thi</div>
          <div class="fs-3 fw-bold" style="letter-spacing: -0.02em;">{{ number_format($stats['total']) }}</div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card border-0 shadow-sm h-100 rounded-4 bg-white" style="transition: transform 0.2s ease;">
      <div class="card-body p-4 d-flex align-items-center gap-4">
        <div style="width: 56px; height: 56px; border-radius: 14px; background: rgba(16, 185, 129, 0.08); color: #10b981; display: flex; align-items: center; justify-content: center;">
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"></path><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
        </div>
        <div>
          <div class="text-muted small fw-medium mb-1">Đã hoàn thành</div>
          <div class="fs-3 fw-bold text-success" style="letter-spacing: -0.02em;">{{ number_format($stats['completed']) }}</div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card border-0 shadow-sm h-100 rounded-4 bg-white" style="transition: transform 0.2s ease;">
      <div class="card-body p-4 d-flex align-items-center gap-4">
        <div style="width: 56px; height: 56px; border-radius: 14px; background: rgba(245, 158, 11, 0.08); color: #f59e0b; display: flex; align-items: center; justify-content: center;">
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
        </div>
        <div>
          <div class="text-muted small fw-medium mb-1">Đang thực hiện</div>
          <div class="fs-3 fw-bold text-warning" style="letter-spacing: -0.02em;">{{ number_format($stats['ongoing']) }}</div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Results Card -->
<div class="card bg-white border-0 shadow-sm rounded-4 animate-fade-in delay-3 mb-4">
  <form action="{{ route('admin.ketqua.index') }}" method="GET" class="card-header bg-white border-bottom-0 pt-4 pb-3 d-flex flex-wrap gap-3 align-items-center">
    <div class="input-group" style="max-width: 380px;">
      <span class="input-group-text bg-light border-0 text-muted px-3" style="border-radius: 8px 0 0 8px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
      </span>
      <input type="text" name="search" value="{{ request('search') }}" class="form-control bg-light border-0 ps-1 py-2" placeholder="Tìm tên học viên, email hoặc đề thi..." style="border-radius: 0 8px 8px 0; box-shadow: none;">
    </div>
    
    <div class="d-flex gap-2 ms-auto">
      <select class="form-select bg-light border-0 py-2" name="trang_thai" onchange="this.form.submit()" style="width: 180px; border-radius: 8px; box-shadow: none;">
        <option value="">Trạng thái</option>
        <option value="Đang làm" {{ request('trang_thai') === 'Đang làm' ? 'selected' : '' }}>Đang làm</option>
        <option value="Hoàn thành" {{ request('trang_thai') === 'Hoàn thành' ? 'selected' : '' }}>Hoàn thành</option>
        <option value="Hết thời gian" {{ request('trang_thai') === 'Hết thời gian' ? 'selected' : '' }}>Hết thời gian</option>
      </select>

      @if(request()->filled('search') || request()->filled('trang_thai'))
        <a href="{{ route('admin.ketqua.index') }}" class="btn btn-light rounded-3 py-2 px-3 d-flex align-items-center justify-content-center text-muted" title="Xóa bộ lọc">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </a>
      @endif
    </div>
  </form>

  <div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead class="table-light text-muted small" style="background-color: #f8fafc;">
        <tr>
          <th class="fw-semibold px-4 py-3 text-uppercase" style="width: 80px; letter-spacing: 0.05em; font-size: 0.75rem;">Mã</th>
          <th class="fw-semibold py-3 text-uppercase" style="letter-spacing: 0.05em; font-size: 0.75rem;">Học viên</th>
          <th class="fw-semibold py-3 text-uppercase" style="letter-spacing: 0.05em; font-size: 0.75rem;">Đề thi / Bài học</th>
          <th class="fw-semibold py-3 text-uppercase" style="letter-spacing: 0.05em; font-size: 0.75rem;">Thời gian thực hiện</th>
          <th class="fw-semibold py-3 text-uppercase" style="letter-spacing: 0.05em; font-size: 0.75rem;">Đúng / Sai</th>
          <th class="fw-semibold py-3 text-uppercase" style="letter-spacing: 0.05em; font-size: 0.75rem;">Tổng điểm</th>
          <th class="fw-semibold py-3 text-uppercase" style="letter-spacing: 0.05em; font-size: 0.75rem;">Trạng thái</th>
          <th class="fw-semibold pe-4 py-3 text-end text-uppercase" style="letter-spacing: 0.05em; font-size: 0.75rem;">Thao tác</th>
        </tr>
      </thead>
      <tbody class="border-top-0">
        @forelse($attempts as $item)
        <tr style="transition: background-color 0.2s ease;">
          <td class="px-4 py-4 fw-bold text-muted font-monospace small">LT{{ sprintf('%04d', $item->id) }}</td>
          <td class="py-4">
            <div class="fw-bold text-dark fs-6">{{ $item->nguoiDung->ho_ten ?? 'N/A' }}</div>
            <div class="small text-muted" style="font-size: 0.8rem;">{{ $item->nguoiDung->email ?? 'N/A' }}</div>
          </td>
          <td class="py-4">
            <div class="fw-semibold text-dark">{{ $item->deThi->ten_de_thi ?? 'N/A' }}</div>
            <div class="small text-muted" style="font-size: 0.8rem;">
              @if($item->deThi && $item->deThi->baiHoc)
                Bài học: {{ $item->deThi->baiHoc->ten_bai_hoc }} ({{ $item->deThi->baiHoc->capDoHsk->ten_cap_do ?? 'N/A' }})
              @else
                Tự do
              @endif
            </div>
          </td>
          <td class="py-4">
            <div class="small text-dark fw-medium">Bắt đầu: {{ $item->thoi_gian_bat_dau ? $item->thoi_gian_bat_dau->format('H:i d/m/Y') : 'N/A' }}</div>
            <div class="small text-muted mt-1" style="font-size: 0.8rem;">
              @if($item->thoi_gian_bat_dau && $item->thoi_gian_ket_thuc)
                Thời gian làm: {{ $item->thoi_gian_bat_dau->diffInMinutes($item->thoi_gian_ket_thuc) }} phút
              @else
                Chưa hoàn tất
              @endif
            </div>
          </td>
          <td class="py-4">
            @if($item->trang_thai !== 'Đang làm')
              <div class="d-flex align-items-center gap-2">
                <span class="badge bg-success bg-opacity-10 text-success rounded-1 px-2 py-1" style="font-size: 0.75rem;">Đúng: {{ $item->so_cau_dung }}</span>
                <span class="badge bg-danger bg-opacity-10 text-danger rounded-1 px-2 py-1" style="font-size: 0.75rem;">Sai: {{ $item->so_cau_sai }}</span>
              </div>
            @else
              <span class="text-muted small">Đang tính toán...</span>
            @endif
          </td>
          <td class="py-4">
            @if($item->trang_thai !== 'Đang làm')
              <span class="fs-6 fw-bold @if($item->deThi && $item->tong_diem >= $item->deThi->diem_dat) text-success @else text-danger @endif">
                {{ floatval($item->tong_diem) }}đ
              </span>
              @if($item->deThi && $item->deThi->diem_dat > 0)
                <div class="small text-muted" style="font-size: 0.75rem; margin-top: 2px;">Điểm đạt: {{ $item->deThi->diem_dat }}đ</div>
              @endif
            @else
              <span class="text-muted small">--</span>
            @endif
          </td>
          <td class="py-4">
            @if($item->trang_thai === 'Hoàn thành')
              <span class="badge rounded-pill px-3 py-2 fw-medium" style="background: rgba(16, 185, 129, 0.08); color: #10b981;">Hoàn thành</span>
            @elseif($item->trang_thai === 'Đang làm')
              <span class="badge rounded-pill px-3 py-2 fw-medium" style="background: rgba(245, 158, 11, 0.08); color: #f59e0b;">Đang làm</span>
            @else
              <span class="badge rounded-pill px-3 py-2 fw-medium" style="background: rgba(239, 68, 68, 0.08); color: #ef4444;">Hết thời gian</span>
            @endif
          </td>
          <td class="py-4 text-end pe-4">
            <div class="d-flex justify-content-end gap-2">
              <a href="{{ route('admin.ketqua.show', $item->id) }}" class="btn btn-sm btn-light text-primary d-flex align-items-center justify-content-center p-2 rounded-3" title="Chi tiết bài thi" style="width: 36px; height: 36px; transition: all 0.2s;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
              </a>
              <form action="{{ route('admin.ketqua.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa lịch sử làm bài này? Thao tác này không thể hoàn tác.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-light text-danger d-flex align-items-center justify-content-center p-2 rounded-3" title="Xóa lịch sử" style="width: 36px; height: 36px; transition: all 0.2s;">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 12-2h4a2 2 0 0 12 2v2"></path></svg>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="text-center py-5 text-muted">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mb-3 text-secondary"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                <div>Chưa có kết quả/lịch sử luyện thi nào được ghi nhận.</div>
            </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($attempts->hasPages())
  <div class="card-footer bg-white border-top p-3 d-flex justify-content-between align-items-center">
    <span class="text-muted small">Hiển thị từ {{ $attempts->firstItem() }} đến {{ $attempts->lastItem() }} trong tổng số {{ $attempts->total() }} lượt thi</span>
    {{ $attempts->appends(request()->query())->links('pagination::bootstrap-5') }}
  </div>
  @endif
</div>
@endsection
