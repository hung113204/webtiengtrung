@extends('admin.layouts.main')

@section('title', 'Quản lý khóa học — Hányǔ Admin')

@section('content')
      <div class="page-header animate-fade-in">
        <div>
          <h1 class="fs-4 fw-bold mb-1">Quản lý khóa học</h1>
          <p class="text-muted mb-0 small">Thêm, sửa và theo dõi toàn bộ khóa học trên hệ thống.</p>
        </div>
        <a href="{{ route('admin.khoahoc.create') }}" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm text-decoration-none" style="background: var(--admin-primary); border: none;">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
          Thêm khóa học mới
        </a>
      </div>

      <!-- Course Data Table -->
      <div class="table-card animate-fade-in delay-1">
        <!-- Filter & Search Toolbar -->
        <form action="{{ route('admin.khoahoc.index') }}" method="GET" class="table-header d-flex flex-wrap gap-3">
          <div class="input-group" style="max-width: 300px;">
            <span class="input-group-text bg-white border-end-0 text-muted">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </span>
            <input type="text" name="search" value="{{ request('search') }}" class="form-control border-start-0 ps-0" placeholder="Tìm tên khóa học...">
          </div>
          
          <div class="d-flex gap-2 ms-auto">
            <select class="form-select form-select-sm" name="danh_muc_id" style="width: auto;" onchange="this.form.submit()">
              <option value="">Tất cả danh mục</option>
              @foreach($danhMucs as $dm)
              <option value="{{ $dm->id }}" {{ request('danh_muc_id') == $dm->id ? 'selected' : '' }}>{{ $dm->ten_danh_muc }}</option>
              @endforeach
            </select>
            <select class="form-select form-select-sm" name="cap_do_id" style="width: auto;" onchange="this.form.submit()">
              <option value="">Tất cả cấp độ HSK</option>
              @foreach($capDos as $cd)
              <option value="{{ $cd->id }}" {{ request('cap_do_id') == $cd->id ? 'selected' : '' }}>{{ $cd->ten_cap_do }}</option>
              @endforeach
            </select>
            <button type="submit" class="d-none"></button>
          </div>
        </form>

        <!-- Table -->
        <div class="table-responsive">
          <table class="table table-hover mb-0 align-middle">
            <thead class="table-light text-muted small">
              <tr>
                <th class="fw-medium px-4 py-3" style="width: 35%;">Khóa học</th>
                <th class="fw-medium py-3">Danh mục</th>
                <th class="fw-medium py-3">Giá tiền</th>
                <th class="fw-medium py-3">Cấp độ</th>
                <th class="fw-medium py-3">Trạng thái</th>
                <th class="fw-medium pe-4 py-3 text-end">Thao tác</th>
              </tr>
            </thead>
            <tbody>
              @forelse($danhsach as $item)
              <tr>
                <td class="px-4 py-3">
                  <div class="d-flex align-items-center gap-3">
                    @if($item->anh_bia)
                    <div class="course-thumb" style="background-image: url('{{ Storage::url($item->anh_bia) }}'); background-size: cover; background-position: center;"></div>
                    @else
                    <div class="course-thumb" style="background: linear-gradient(135deg, #f59e0b, #dc2626);">
                      {{ mb_substr($item->ten_khoa_hoc, 0, 1) }}
                    </div>
                    @endif
                    <div>
                      <div class="fw-semibold text-dark">{{ $item->ten_khoa_hoc }}</div>
                      <div class="small text-muted">{{ max($item->tong_bai_hoc, $item->bai_hocs_count) }} bài học • {{ max($item->tong_thoi_gian, round(($item->bai_hocs_sum_thoi_luong_giay ?? 0) / 60)) }} phút</div>
                    </div>
                  </div>
                </td>
                <td><span class="badge bg-light text-dark border">{{ $item->danhMucKhoaHoc ? $item->danhMucKhoaHoc->ten_danh_muc : 'Không' }}</span></td>
                <td>
                  @if($item->gia_giam > 0)
                    <div class="fw-medium text-danger">{{ number_format($item->gia_giam, 0, ',', '.') }}đ</div>
                    <del class="small text-muted">{{ number_format($item->gia, 0, ',', '.') }}đ</del>
                  @else
                    <div class="fw-medium">{{ number_format($item->gia, 0, ',', '.') }}đ</div>
                  @endif
                </td>
                <td><span class="badge bg-light text-dark border">{{ $item->capDoHSK ? $item->capDoHSK->ten_cap_do : 'Không' }}</span></td>
                <td>
                  @if($item->trang_thai)
                  <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle">Xuất bản</span>
                  @else
                  <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle">Bản nháp</span>
                  @endif
                </td>
                <td class="text-end pe-4">
                  <div class="d-flex justify-content-end gap-1">
                    <a href="{{ route('admin.khoahoc.show', $item->id) }}" class="icon-btn text-info" title="Xem chi tiết">
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </a>
                    <a href="{{ route('admin.khoahoc.edit', $item->id) }}" class="icon-btn text-primary" title="Chỉnh sửa">
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </a>
                    <form action="{{ route('admin.khoahoc.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa khóa học này không?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="icon-btn text-danger" title="Xóa" style="background:none; border:none;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path></svg>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="6" class="text-center py-4 text-muted">Chưa có khóa học nào.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        
        <!-- Pagination -->
        @if($danhsach->hasPages())
        <div class="table-header d-flex justify-content-end border-top border-bottom-0" style="background: white;">
            {{ $danhsach->links('pagination::bootstrap-5') }}
        </div>
        @endif
      </div>
@endsection

