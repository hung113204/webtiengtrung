@extends('admin.layouts.main')

@section('title', 'Quản lý Đăng ký Khóa học — Hányǔ Admin')

@section('content')
<div class="page-header animate-fade-in delay-1 mb-4 d-flex justify-content-between align-items-center">
  <div>
    <h1 class="fs-4 fw-bold mb-1" style="letter-spacing: -0.01em;">Quản lý Đăng ký Khóa học</h1>
    <p class="text-muted mb-0 small">Phê duyệt kích hoạt khóa học hoặc hủy các lượt đăng ký của học viên.</p>
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
@php
    $totalCount = \App\Models\DangKyKhoaHoc::count();
    $pendingCount = \App\Models\DangKyKhoaHoc::where('trang_thai', 'Chờ duyệt')->count();
    $approvedCount = \App\Models\DangKyKhoaHoc::where('trang_thai', 'Đã duyệt')->count();
@endphp
<div class="row mb-4 animate-fade-in delay-2">
  <div class="col-md-4 mb-3 mb-md-0">
    <div class="card border-0 shadow-sm h-100" style="background: var(--admin-card); border-radius: 16px;">
      <div class="card-body d-flex align-items-center gap-3 p-4">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(59, 130, 246, 0.1); color: #3b82f6; display: flex; align-items: center; justify-content: center;">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
        </div>
        <div>
          <div class="text-muted small fw-medium">Tổng lượt đăng ký</div>
          <div class="fs-4 fw-bold text-dark">{{ number_format($totalCount) }}</div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4 mb-3 mb-md-0">
    <div class="card border-0 shadow-sm h-100" style="background: var(--admin-card); border-radius: 16px;">
      <div class="card-body d-flex align-items-center gap-3 p-4">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(245, 158, 11, 0.1); color: #f59e0b; display: flex; align-items: center; justify-content: center;">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
        </div>
        <div>
          <div class="text-muted small fw-medium">Yêu cầu chờ duyệt</div>
          <div class="fs-4 fw-bold text-dark">{{ number_format($pendingCount) }}</div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card border-0 shadow-sm h-100" style="background: var(--admin-card); border-radius: 16px;">
      <div class="card-body d-flex align-items-center gap-3 p-4">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(16, 185, 129, 0.1); color: #10b981; display: flex; align-items: center; justify-content: center;">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        </div>
        <div>
          <div class="text-muted small fw-medium">Đã kích hoạt</div>
          <div class="fs-4 fw-bold text-dark">{{ number_format($approvedCount) }}</div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Filters and List -->
<div class="card border-0 shadow-sm animate-fade-in delay-3 mb-5" style="background: var(--admin-card); border-radius: 16px;">
  <div class="card-header border-0 bg-transparent p-4 pb-0">
    <form method="GET" action="{{ route('admin.dangkykhoahoc.index') }}" class="row g-3">
      <div class="col-md-4">
        <div class="input-group">
          <span class="input-group-text bg-light border-0 text-muted rounded-start-3 px-3">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
          </span>
          <input type="text" name="search" value="{{ request('search') }}" class="form-control bg-light border-0 ps-0 rounded-end-3" placeholder="Tìm kiếm theo học viên, khóa học...">
        </div>
      </div>
      
      <div class="col-md-8 d-flex justify-content-md-end gap-2 flex-wrap">
        <select name="trang_thai" class="form-select bg-light border-0 rounded-3" style="width: auto; min-width: 180px; box-shadow: none;" onchange="this.form.submit()">
          <option value="">Tất cả trạng thái</option>
          <option value="Chờ duyệt" {{ request('trang_thai') === 'Chờ duyệt' ? 'selected' : '' }}>Chờ duyệt</option>
          <option value="Đã duyệt" {{ request('trang_thai') === 'Đã duyệt' ? 'selected' : '' }}>Đã duyệt</option>
          <option value="Đã hủy" {{ request('trang_thai') === 'Đã hủy' ? 'selected' : '' }}>Đã hủy</option>
        </select>
        
        @if(request('search') || request('trang_thai'))
          <a href="{{ route('admin.dangkykhoahoc.index') }}" class="btn btn-outline-secondary rounded-3 d-flex align-items-center gap-2">
            Xóa lọc
          </a>
        @endif
      </div>
    </form>
  </div>

  <div class="card-body p-4">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th class="border-0 rounded-start-3" style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: #6b7280; padding: 12px 16px;">Học viên</th>
            <th class="border-0" style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: #6b7280; padding: 12px 16px;">Khóa học</th>
            <th class="border-0" style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: #6b7280; padding: 12px 16px;">Ngày đăng ký</th>
            <th class="border-0" style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: #6b7280; padding: 12px 16px;">Trạng thái</th>
            <th class="border-0 rounded-end-3 text-end" style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: #6b7280; padding: 12px 16px; width: 220px;">Hành động</th>
          </tr>
        </thead>
        <tbody>
          @forelse($registrations as $reg)
            <tr class="align-middle">
              <td class="px-3 py-3">
                <div class="d-flex align-items-center gap-3">
                  <div class="avatar-circle" style="width: 40px; height: 40px; border-radius: 50%; background: #f3f4f6; display: flex; align-items: center; justify-content: center; font-weight: bold; color: var(--admin-primary);">
                    {{ mb_substr($reg->nguoiDung->ho_ten ?? 'H', 0, 1) }}
                  </div>
                  <div>
                    <h6 class="mb-0 fw-semibold text-dark">{{ $reg->nguoiDung->ho_ten ?? 'Học viên ẩn' }}</h6>
                    <span class="text-muted small">{{ $reg->nguoiDung->email ?? '' }}</span>
                  </div>
                </div>
              </td>
              <td class="px-3">
                <div class="fw-semibold text-dark">{{ $reg->khoaHoc->ten_khoa_hoc ?? 'Khóa học đã xóa' }}</div>
                <div class="d-flex align-items-center gap-2 mt-1 flex-wrap">
                  <div class="text-muted small">
                    @if($reg->khoaHoc && $reg->khoaHoc->gia_giam)
                      <span class="text-danger fw-semibold">{{ number_format($reg->khoaHoc->gia_giam) }}đ</span>
                      <span class="text-decoration-line-through text-muted small ms-1">{{ number_format($reg->khoaHoc->gia) }}đ</span>
                    @elseif($reg->khoaHoc && $reg->khoaHoc->gia)
                      <span class="text-dark">{{ number_format($reg->khoaHoc->gia) }}đ</span>
                    @else
                      <span class="text-success fw-medium">Miễn phí</span>
                    @endif
                  </div>
                  @if($reg->hoaDon)
                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle px-2 py-0.5 rounded" style="font-size: 0.7rem;">
                      {{ $reg->hoaDon->ma_hoa_don }}
                    </span>
                    <span class="text-muted text-opacity-75" style="font-size: 0.7rem;">
                      • {{ $reg->hoaDon->phuong_thuc_thanh_toan }}
                    </span>
                  @endif
                </div>
              </td>
              <td class="px-3 text-muted small">
                {{ $reg->ngay_dang_ky ? $reg->ngay_dang_ky->format('H:i d/m/Y') : ($reg->created_at ? $reg->created_at->format('H:i d/m/Y') : '') }}
              </td>
              <td class="px-3">
                @if($reg->trang_thai === 'Chờ duyệt')
                  <span class="badge bg-warning bg-opacity-10 text-warning px-2.5 py-1.5 rounded-pill border border-warning-subtle small" style="font-size: 0.75rem;">Chờ duyệt</span>
                @elseif($reg->trang_thai === 'Đã duyệt')
                  <span class="badge bg-success bg-opacity-10 text-success px-2.5 py-1.5 rounded-pill border border-success-subtle small" style="font-size: 0.75rem;">Đã duyệt</span>
                @else
                  <span class="badge bg-danger bg-opacity-10 text-danger px-2.5 py-1.5 rounded-pill border border-danger-subtle small" style="font-size: 0.75rem;">Đã hủy</span>
                @endif
              </td>
              <td class="px-3 text-end">
                <div class="d-flex justify-content-end gap-2">
                  <!-- Approve button -->
                  @if($reg->trang_thai !== 'Đã duyệt')
                    <form action="{{ route('admin.dangkykhoahoc.status', $reg->id) }}" method="POST" class="d-inline">
                      @csrf
                      @method('PUT')
                      <input type="hidden" name="trang_thai" value="Đã duyệt">
                      <button type="submit" class="btn btn-sm btn-success rounded-3 d-inline-flex align-items-center gap-1.5 px-2.5 py-1.5">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        Duyệt
                      </button>
                    </form>
                  @endif

                  <!-- Cancel button -->
                  @if($reg->trang_thai !== 'Đã hủy')
                    <form action="{{ route('admin.dangkykhoahoc.status', $reg->id) }}" method="POST" class="d-inline">
                      @csrf
                      @method('PUT')
                      <input type="hidden" name="trang_thai" value="Đã hủy">
                      <button type="submit" class="btn btn-sm btn-outline-danger rounded-3 d-inline-flex align-items-center gap-1.5 px-2.5 py-1.5">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                        Hủy
                      </button>
                    </form>
                  @endif

                  <!-- Delete button -->
                  <form action="{{ route('admin.dangkykhoahoc.destroy', $reg->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa lượt đăng ký này?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-icon btn-outline-secondary border-0 rounded-3 p-1.5" title="Xóa">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center py-5 text-muted">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mb-2 text-muted"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                <p class="mb-0 small">Không tìm thấy lượt đăng ký khóa học nào.</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
      {{ $registrations->links() }}
    </div>
  </div>
</div>
@endsection
