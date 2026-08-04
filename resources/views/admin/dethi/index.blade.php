@extends('admin.layouts.main')

@section('title', 'Quản lý Đề thi — Hányǔ Admin')

@section('content')
<div class="page-header animate-fade-in delay-1 mb-4 d-flex justify-content-between align-items-center">
  <div>
    <h1 class="fs-4 fw-bold mb-1" style="letter-spacing: -0.01em;">Quản lý Đề thi / Luyện thi</h1>
    <p class="text-muted mb-0 small">Thiết lập đề thi thử HSK, đề kiểm tra đánh giá năng lực với giới hạn thời gian.</p>
  </div>
  <button class="btn btn-primary d-flex align-items-center gap-2 shadow-sm rounded-3 px-3 py-2" style="background: var(--admin-primary); border: none;" data-bs-toggle="modal" data-bs-target="#addExamModal">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
    <span class="fw-medium">Tạo Đề thi mới</span>
  </button>
</div>

<!-- Alert notifications -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show animate-fade-in mb-4" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show animate-fade-in mb-4" role="alert">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Filters & Stats -->
<div class="row g-4 mb-4 animate-fade-in delay-2">
  <div class="col-md-4">
    <div class="card border-0 shadow-sm h-100 rounded-4" style="background: var(--admin-card); transition: transform 0.2s ease;">
      <div class="card-body p-4 d-flex align-items-center gap-4">
        <div style="width: 56px; height: 56px; border-radius: 14px; background: rgba(220, 38, 38, 0.08); color: var(--admin-primary); display: flex; align-items: center; justify-content: center;">
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
        </div>
        <div>
          <div class="text-muted small fw-medium mb-1">Tổng số Đề thi</div>
          <div class="fs-3 fw-bold" style="letter-spacing: -0.02em;">{{ $totalExams }}</div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card border-0 shadow-sm h-100 rounded-4" style="background: var(--admin-card); transition: transform 0.2s ease;">
      <div class="card-body p-4 d-flex align-items-center gap-4">
        <div style="width: 56px; height: 56px; border-radius: 14px; background: rgba(16, 185, 129, 0.08); color: #10b981; display: flex; align-items: center; justify-content: center;">
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        </div>
        <div>
          <div class="text-muted small fw-medium mb-1">Lượt thi thành công</div>
          <div class="fs-3 fw-bold" style="letter-spacing: -0.02em;">{{ number_format($completedAttempts) }}</div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card border-0 shadow-sm h-100 rounded-4" style="background: var(--admin-card); transition: transform 0.2s ease;">
      <div class="card-body p-4 d-flex align-items-center gap-4">
        <div style="width: 56px; height: 56px; border-radius: 14px; background: rgba(245, 158, 11, 0.08); color: #f59e0b; display: flex; align-items: center; justify-content: center;">
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
        </div>
        <div>
          <div class="text-muted small fw-medium mb-1">Thời gian thi TB</div>
          <div class="fs-3 fw-bold" style="letter-spacing: -0.02em;">{{ $avgTime }} <span class="fs-6 text-muted fw-normal">phút</span></div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Exam Data Table -->
<div class="card bg-white border-0 shadow-sm rounded-4 animate-fade-in delay-3 mb-4">
  <form action="{{ route('admin.dethi.index') }}" method="GET" class="card-header bg-white border-bottom-0 pt-4 pb-3 d-flex flex-wrap gap-3 align-items-center">
    <div class="input-group" style="max-width: 320px;">
      <span class="input-group-text bg-light border-0 text-muted px-3" style="border-radius: 8px 0 0 8px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
      </span>
      <input type="text" name="search" value="{{ request('search') }}" class="form-control bg-light border-0 ps-1 py-2" placeholder="Tìm tên đề thi..." style="border-radius: 0 8px 8px 0; box-shadow: none;">
    </div>
    
    <div class="d-flex gap-2 ms-auto">
      <select class="form-select bg-light border-0 py-2" name="cap_do_hsk" onchange="this.form.submit()" style="width: 160px; border-radius: 8px; box-shadow: none;">
        <option value="">Cấp độ HSK</option>
        @foreach($capDoHskList as $capDo)
          <option value="{{ $capDo->id }}" {{ request('cap_do_hsk') == $capDo->id ? 'selected' : '' }}>{{ $capDo->ten_cap_do }}</option>
        @endforeach
      </select>

      <select class="form-select bg-light border-0 py-2" name="trang_thai" onchange="this.form.submit()" style="width: 150px; border-radius: 8px; box-shadow: none;">
        <option value="">Trạng thái</option>
        <option value="1" {{ request('trang_thai') === '1' ? 'selected' : '' }}>Đang mở</option>
        <option value="0" {{ request('trang_thai') === '0' ? 'selected' : '' }}>Đã đóng</option>
      </select>

      @if(request()->filled('search') || request()->filled('cap_do_hsk') || request()->filled('trang_thai'))
        <a href="{{ route('admin.dethi.index') }}" class="btn btn-light rounded-3 py-2 px-3 d-flex align-items-center justify-content-center text-muted" title="Xóa bộ lọc">
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
          <th class="fw-semibold py-3 text-uppercase" style="letter-spacing: 0.05em; font-size: 0.75rem;">Thông tin đề thi</th>
          <th class="fw-semibold py-3 text-uppercase" style="letter-spacing: 0.05em; font-size: 0.75rem;">Phân loại / Bài học</th>
          <th class="fw-semibold py-3 text-uppercase" style="letter-spacing: 0.05em; font-size: 0.75rem;">Cấu trúc</th>
          <th class="fw-semibold py-3 text-uppercase" style="letter-spacing: 0.05em; font-size: 0.75rem;">Mức độ / Loại</th>
          <th class="fw-semibold py-3 text-uppercase" style="letter-spacing: 0.05em; font-size: 0.75rem;">Trạng thái</th>
          <th class="fw-semibold pe-4 py-3 text-end text-uppercase" style="letter-spacing: 0.05em; font-size: 0.75rem;">Thao tác</th>
        </tr>
      </thead>
      <tbody class="border-top-0">
        @forelse($deThis as $item)
        <tr style="transition: background-color 0.2s ease;">
          <td class="px-4 py-4 fw-bold text-muted font-monospace small">DT{{ sprintf('%02d', $item->id) }}</td>
          <td class="py-4">
            <div class="fw-bold text-dark fs-6 mb-1">{{ $item->ten_de_thi }}</div>
            <div class="d-flex align-items-center small text-muted gap-2">
              <span class="d-flex align-items-center gap-1">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                {{ $item->thoi_gian_lam }} phút
              </span>
              @if($item->diem_dat > 0)
              <span>• Điểm đạt: {{ $item->diem_dat }}đ</span>
              @endif
            </div>
          </td>
          <td class="py-4">
            @if($item->baiHoc)
              <span class="badge rounded-pill px-3 py-2 fw-medium" style="background: rgba(220, 38, 38, 0.08); color: #dc2626;">
                {{ $item->baiHoc->capDoHsk->ten_cap_do ?? 'N/A' }}
              </span>
              <div class="small text-muted mt-1" style="font-size: 0.8rem;">Bài: {{ $item->baiHoc->ten_bai_hoc }}</div>
            @else
              <span class="badge rounded-pill px-3 py-2 fw-medium bg-light text-secondary">Tự do</span>
            @endif
          </td>
          <td class="py-4">
            <div class="small fw-semibold text-dark mb-1">{{ $item->so_cau }} câu hỏi</div>
            <div class="small text-muted" style="font-size: 0.8rem;">
                Nghe ({{ $item->cauHois->filter(fn($q) => $q->getPart() === 'listening')->count() }}), 
                Đọc ({{ $item->cauHois->filter(fn($q) => $q->getPart() === 'reading')->count() }}), 
                Viết ({{ $item->cauHois->filter(fn($q) => $q->getPart() === 'writing')->count() }})
            </div>
          </td>
          <td class="py-4">
            <div class="small fw-semibold text-dark">{{ $item->mucDo->ten_muc_do ?? 'N/A' }}</div>
            <div class="small text-muted" style="font-size: 0.8rem;">{{ $item->loai_de }}</div>
          </td>
          <td class="py-4">
            @if($item->trang_thai)
              <span class="badge rounded-pill px-3 py-2 fw-medium" style="background: rgba(16, 185, 129, 0.08); color: #10b981;">Đang mở</span>
            @else
              <span class="badge rounded-pill px-3 py-2 fw-medium" style="background: rgba(100, 116, 139, 0.08); color: #64748b;">Đã đóng</span>
            @endif
          </td>
          <td class="py-4 text-end pe-4">
            <div class="d-flex justify-content-end gap-2">
              <button class="btn btn-sm btn-light text-primary d-flex align-items-center justify-content-center p-2 rounded-3 btn-manage-questions" 
                      title="Ghép câu hỏi vào đề" 
                      data-bs-toggle="modal" 
                      data-bs-target="#manageQuestionsModal" 
                      data-id="{{ $item->id }}"
                      data-title="{{ $item->ten_de_thi }}"
                      style="width: 36px; height: 36px; transition: all 0.2s;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
              </button>
              <button class="btn btn-sm btn-light text-secondary d-flex align-items-center justify-content-center p-2 rounded-3 btn-edit-exam" 
                      title="Chỉnh sửa thông tin" 
                      data-bs-toggle="modal" 
                      data-bs-target="#editExamModal"
                      data-id="{{ $item->id }}"
                      data-ten_de_thi="{{ $item->ten_de_thi }}"
                      data-id_bai_hoc="{{ $item->id_bai_hoc }}"
                      data-thoi_gian_lam="{{ $item->thoi_gian_lam }}"
                      data-diem_dat="{{ $item->diem_dat }}"
                      data-id_muc_do="{{ $item->id_muc_do }}"
                      data-loai_de="{{ $item->loai_de }}"
                      data-trang_thai="{{ $item->trang_thai ? '1' : '0' }}"
                      data-mo_ta="{{ $item->mo_ta }}"
                      style="width: 36px; height: 36px; transition: all 0.2s;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
              </button>
              <form action="{{ route('admin.dethi.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đề thi này? Tất cả câu hỏi ghép sẽ bị gỡ bỏ.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-light text-danger d-flex align-items-center justify-content-center p-2 rounded-3" title="Xóa" style="width: 36px; height: 36px; transition: all 0.2s;">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path></svg>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center py-5 text-muted">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mb-3 text-secondary"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                <div>Chưa có đề thi nào phù hợp với bộ lọc.</div>
            </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($deThis->hasPages())
  <div class="card-footer bg-white border-top p-3 d-flex justify-content-between align-items-center">
    <span class="text-muted small">Hiển thị từ {{ $deThis->firstItem() }} đến {{ $deThis->lastItem() }} trong tổng số {{ $deThis->total() }} đề thi</span>
    {{ $deThis->appends(request()->query())->links('pagination::bootstrap-5') }}
  </div>
  @endif
</div>

<!-- Modal Tạo Đề thi -->
<div class="modal fade" id="addExamModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg rounded-4" style="background: var(--admin-card); overflow: hidden;">
      <div class="modal-header border-bottom border-light px-4 py-3 bg-light" style="background-color: #f8fafc !important;">
        <h5 class="modal-title fw-bold" style="letter-spacing: -0.01em;">Tạo Đề thi / Luyện thi mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('admin.dethi.store') }}" method="POST">
        @csrf
        <div class="modal-body p-4">
          <div class="row g-4">
            <div class="col-md-8">
              <label class="form-label fw-semibold text-dark small mb-2">Tên đề thi</label>
              <input type="text" name="ten_de_thi" required class="form-control bg-light border-0 px-3 py-2 rounded-3" placeholder="VD: Đề thi thử HSK 4 - Đề 01 (Năm 2024)" style="box-shadow: none;">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold text-dark small mb-2">Thuộc Bài học (Không bắt buộc)</label>
              <select name="id_bai_hoc" class="form-select bg-light border-0 px-3 py-2 rounded-3" style="box-shadow: none;">
                <option value="">-- Chọn bài học --</option>
                @foreach($baiHocs as $baiHoc)
                  <option value="{{ $baiHoc->id }}">{{ $baiHoc->ten_bai_hoc }} ({{ $baiHoc->capDoHsk->ten_cap_do ?? 'N/A' }})</option>
                @endforeach
              </select>
            </div>
            
            <div class="col-md-4">
              <label class="form-label fw-semibold text-dark small mb-2">Thời gian làm bài (Phút)</label>
              <input type="number" name="thoi_gian_lam" required min="0" class="form-control bg-light border-0 px-3 py-2 rounded-3" placeholder="VD: 105" style="box-shadow: none;">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold text-dark small mb-2">Điểm đạt tối thiểu (Nêu có)</label>
              <input type="number" name="diem_dat" min="0" class="form-control bg-light border-0 px-3 py-2 rounded-3" placeholder="VD: 60" style="box-shadow: none;">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold text-dark small mb-2">Trạng thái ban đầu</label>
              <select name="trang_thai" class="form-select bg-light border-0 px-3 py-2 rounded-3" style="box-shadow: none;">
                <option value="1">Đang mở (Publish)</option>
                <option value="0">Ẩn (Bản nháp)</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold text-dark small mb-2">Mức độ đề thi</label>
              <select name="id_muc_do" class="form-select bg-light border-0 px-3 py-2 rounded-3" style="box-shadow: none;">
                <option value="">-- Chọn mức độ --</option>
                @foreach($mucDos as $mucDo)
                  <option value="{{ $mucDo->id }}">{{ $mucDo->ten_muc_do }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold text-dark small mb-2">Loại đề</label>
              <select name="loai_de" class="form-select bg-light border-0 px-3 py-2 rounded-3" style="box-shadow: none;">
                <option value="Luyện tập" selected>Luyện tập</option>
                <option value="Thi thử">Thi thử</option>
                <option value="Kiểm tra">Kiểm tra</option>
              </select>
            </div>
            
            <div class="col-12">
              <label class="form-label fw-semibold text-dark small mb-2">Mô tả ngắn gọn hoặc Quy chế thi</label>
              <textarea name="mo_ta" class="form-control bg-light border-0 px-3 py-2 rounded-3" rows="3" placeholder="Ghi chú các quy định làm bài cho học viên (nếu có)..." style="box-shadow: none; resize: none;"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer border-top border-light px-4 py-3 bg-light d-flex justify-content-between align-items-center" style="background-color: #f8fafc !important;">
          <div class="text-muted small d-flex align-items-center gap-1">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            Sau khi lưu, bạn mới có thể ghép câu hỏi.
          </div>
          <div class="d-flex gap-2">
            <button type="button" class="btn btn-light fw-medium px-4 py-2 rounded-3" data-bs-dismiss="modal">Hủy bỏ</button>
            <button type="submit" class="btn btn-primary fw-medium px-4 py-2 rounded-3 shadow-sm" style="background: var(--admin-primary); border: none;">Lưu đề thi</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Chỉnh sửa Đề thi -->
<div class="modal fade" id="editExamModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg rounded-4" style="background: var(--admin-card); overflow: hidden;">
      <div class="modal-header border-bottom border-light px-4 py-3 bg-light" style="background-color: #f8fafc !important;">
        <h5 class="modal-title fw-bold" style="letter-spacing: -0.01em;">Chỉnh sửa Đề thi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editExamForm" action="" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body p-4">
          <div class="row g-4">
            <div class="col-md-8">
              <label class="form-label fw-semibold text-dark small mb-2">Tên đề thi</label>
              <input type="text" name="ten_de_thi" id="edit_ten_de_thi" required class="form-control bg-light border-0 px-3 py-2 rounded-3" style="box-shadow: none;">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold text-dark small mb-2">Thuộc Bài học (Không bắt buộc)</label>
              <select name="id_bai_hoc" id="edit_id_bai_hoc" class="form-select bg-light border-0 px-3 py-2 rounded-3" style="box-shadow: none;">
                <option value="">-- Chọn bài học --</option>
                @foreach($baiHocs as $baiHoc)
                  <option value="{{ $baiHoc->id }}">{{ $baiHoc->ten_bai_hoc }} ({{ $baiHoc->capDoHsk->ten_cap_do ?? 'N/A' }})</option>
                @endforeach
              </select>
            </div>
            
            <div class="col-md-4">
              <label class="form-label fw-semibold text-dark small mb-2">Thời gian làm bài (Phút)</label>
              <input type="number" name="thoi_gian_lam" id="edit_thoi_gian_lam" required min="0" class="form-control bg-light border-0 px-3 py-2 rounded-3" style="box-shadow: none;">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold text-dark small mb-2">Điểm đạt tối thiểu</label>
              <input type="number" name="diem_dat" id="edit_diem_dat" min="0" class="form-control bg-light border-0 px-3 py-2 rounded-3" style="box-shadow: none;">
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold text-dark small mb-2">Trạng thái</label>
              <select name="trang_thai" id="edit_trang_thai" class="form-select bg-light border-0 px-3 py-2 rounded-3" style="box-shadow: none;">
                <option value="1">Đang mở (Publish)</option>
                <option value="0">Ẩn (Bản nháp)</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold text-dark small mb-2">Mức độ đề thi</label>
              <select name="id_muc_do" id="edit_id_muc_do" class="form-select bg-light border-0 px-3 py-2 rounded-3" style="box-shadow: none;">
                <option value="">-- Chọn mức độ --</option>
                @foreach($mucDos as $mucDo)
                  <option value="{{ $mucDo->id }}">{{ $mucDo->ten_muc_do }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold text-dark small mb-2">Loại đề</label>
              <select name="loai_de" id="edit_loai_de" class="form-select bg-light border-0 px-3 py-2 rounded-3" style="box-shadow: none;">
                <option value="Luyện tập">Luyện tập</option>
                <option value="Thi thử">Thi thử</option>
                <option value="Kiểm tra">Kiểm tra</option>
              </select>
            </div>
            
            <div class="col-12">
              <label class="form-label fw-semibold text-dark small mb-2">Mô tả ngắn gọn hoặc Quy chế thi</label>
              <textarea name="mo_ta" id="edit_mo_ta" class="form-control bg-light border-0 px-3 py-2 rounded-3" rows="3" style="box-shadow: none; resize: none;"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer border-top border-light px-4 py-3 bg-light d-flex justify-content-end gap-2" style="background-color: #f8fafc !important;">
          <button type="button" class="btn btn-light fw-medium px-4 py-2 rounded-3" data-bs-dismiss="modal">Hủy bỏ</button>
          <button type="submit" class="btn btn-primary fw-medium px-4 py-2 rounded-3 shadow-sm" style="background: var(--admin-primary); border: none;">Lưu thay đổi</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Quản lý Cấu trúc Đề thi -->
<div class="modal fade" id="manageQuestionsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content border-0 bg-light">
      <div class="modal-header border-bottom border-light px-4 py-3 bg-white shadow-sm z-1">
        <div class="d-flex align-items-center gap-3">
          <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(220, 38, 38, 0.1); color: var(--admin-primary); display: flex; align-items: center; justify-content: center;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path></svg>
          </div>
          <div>
            <h5 class="modal-title fw-bold mb-1 fs-5" id="qmodal-exam-title" style="letter-spacing: -0.01em;">Ghép Câu Hỏi - Đề thi</h5>
            <div class="small text-muted fw-medium d-flex align-items-center gap-2">
              <span id="qmodal-total-questions">Tổng cộng: 0 câu hỏi</span>
              <span class="text-light-50">•</span>
              <span id="qmodal-duration">Thời gian: 0 phút</span>
            </div>
          </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0 d-flex flex-column bg-light">
        <!-- Nav Tabs -->
        <ul class="nav nav-tabs admin-tabs px-4 pt-3 border-light bg-white" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active fw-semibold" data-bs-toggle="tab" data-bs-target="#tab-listening" type="button" role="tab">
              <span class="d-flex align-items-center gap-2">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon><path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path></svg>
                Phần Nghe (<span id="count-attached-listening">0</span> câu)
              </span>
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link fw-semibold" data-bs-toggle="tab" data-bs-target="#tab-reading" type="button" role="tab">
              <span class="d-flex align-items-center gap-2">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                Phần Đọc (<span id="count-attached-reading">0</span> câu)
              </span>
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link fw-semibold" data-bs-toggle="tab" data-bs-target="#tab-writing" type="button" role="tab">
              <span class="d-flex align-items-center gap-2">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19l7-7 3 3-7 7-3-3z"></path><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"></path></svg>
                Phần Viết (<span id="count-attached-writing">0</span> câu)
              </span>
            </button>
          </li>
        </ul>

        <div class="tab-content flex-grow-1 h-100">
          <!-- Common Tab Container for All Parts -->
          @foreach(['listening' => 'Nghe', 'reading' => 'Đọc', 'writing' => 'Viết'] as $partKey => $partName)
          <div class="tab-pane fade @if($loop->first) show active @endif h-100 p-4" id="tab-{{ $partKey }}" role="tabpanel">
            <div class="row h-100 g-4">
              <!-- Cột bên trái: Ngân hàng câu hỏi -->
              <div class="col-md-6 d-flex flex-column h-100">
                <div class="card border-0 shadow-sm h-100 rounded-4 d-flex flex-column bg-white">
                  <div class="card-header bg-transparent border-light pt-4 pb-3 px-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                      <h6 class="fw-bold mb-0">Ngân hàng câu hỏi ({{ $partName }})</h6>
                      <span class="badge rounded-pill bg-light text-dark border px-3 py-1">Có sẵn: <span id="count-available-{{ $partKey }}">0</span></span>
                    </div>
                    <div class="input-group">
                      <input type="text" class="form-control bg-light border-0 ps-3 py-2 rounded-3 search-available" data-part="{{ $partKey }}" placeholder="Tìm kiếm nội dung câu hỏi..." style="box-shadow: none;">
                    </div>
                  </div>
                  <!-- Danh sách câu hỏi có sẵn -->
                  <div class="card-body p-4 flex-grow-1 overflow-auto custom-scrollbar" id="list-available-{{ $partKey }}">
                    <!-- Loaded dynamically -->
                  </div>
                </div>
              </div>

              <!-- Cột bên phải: Câu hỏi đã chọn -->
              <div class="col-md-6 d-flex flex-column h-100">
                <div class="card border-0 shadow-sm h-100 rounded-4 d-flex flex-column" style="background: rgba(220, 38, 38, 0.02); border: 1px dashed rgba(220, 38, 38, 0.2) !important;">
                  <div class="card-header bg-transparent border-0 pt-4 pb-2 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                      <h6 class="fw-bold mb-0 text-dark">Câu hỏi trong Đề thi</h6>
                      <span class="badge rounded-pill px-3 py-1 fw-medium" style="background: rgba(220, 38, 38, 0.1); color: var(--admin-primary);">Đã chọn: <span id="count-selected-{{ $partKey }}">0</span></span>
                    </div>
                  </div>
                  <!-- Danh sách câu hỏi đã ghép -->
                  <div class="card-body p-4 flex-grow-1 overflow-auto custom-scrollbar list-attached-container" id="list-attached-{{ $partKey }}" data-part="{{ $partKey }}">
                    <!-- Loaded dynamically -->
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
      <div class="modal-footer border-top border-light px-4 py-3 bg-white shadow-sm z-1">
        <button type="button" class="btn btn-primary px-4 py-2 fw-medium rounded-3 shadow-sm" data-bs-dismiss="modal" style="background: var(--admin-primary); border: none;">Đóng & Lưu thay đổi</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ----------------------------------------------------
    // POPULATE EDIT EXAM MODAL
    // ----------------------------------------------------
    const editExamButtons = document.querySelectorAll('.btn-edit-exam');
    const editForm = document.getElementById('editExamForm');
    
    editExamButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const tenDeThi = this.getAttribute('data-ten_de_thi');
            const idBaiHoc = this.getAttribute('data-id_bai_hoc');
            const thoiGianLam = this.getAttribute('data-thoi_gian_lam');
            const diemDat = this.getAttribute('data-diem_dat');
            const idMucDo = this.getAttribute('data-id_muc_do');
            const loaiDe = this.getAttribute('data-loai_de');
            const trangThai = this.getAttribute('data-trang_thai');
            const moTa = this.getAttribute('data-mo_ta');
            
            // Set action URL
            editForm.action = `/admin/dethi/${id}`;
            
            // Set form values
            document.getElementById('edit_ten_de_thi').value = tenDeThi;
            document.getElementById('edit_id_bai_hoc').value = idBaiHoc || '';
            document.getElementById('edit_thoi_gian_lam').value = thoiGianLam;
            document.getElementById('edit_diem_dat').value = diemDat;
            document.getElementById('edit_id_muc_do').value = idMucDo || '';
            document.getElementById('edit_loai_de').value = loaiDe;
            document.getElementById('edit_trang_thai').value = trangThai;
            document.getElementById('edit_mo_ta').value = moTa || '';
        });
    });

    // ----------------------------------------------------
    // QUESTION MAPPING LOGIC (AJAX)
    // ----------------------------------------------------
    let currentExamId = null;
    let questionsData = {
        attached: [],
        available: []
    };

    const manageQuestionsModal = document.getElementById('manageQuestionsModal');
    
    // Track edit/manage button click
    const manageButtons = document.querySelectorAll('.btn-manage-questions');
    manageButtons.forEach(button => {
        button.addEventListener('click', function() {
            currentExamId = this.getAttribute('data-id');
            const title = this.getAttribute('data-title');
            
            document.getElementById('qmodal-exam-title').innerText = `Ghép Câu Hỏi - ${title}`;
            loadQuestions();
        });
    });

    // Load questions function
    function loadQuestions() {
        if (!currentExamId) return;

        // Show loading state
        ['listening', 'reading', 'writing'].forEach(part => {
            document.getElementById(`list-available-${part}`).innerHTML = '<div class="text-center py-4 text-muted"><div class="spinner-border spinner-border-sm text-secondary me-2"></div>Đang tải câu hỏi...</div>';
            document.getElementById(`list-attached-${part}`).innerHTML = '<div class="text-center py-4 text-muted"><div class="spinner-border spinner-border-sm text-secondary me-2"></div>Đang tải câu hỏi...</div>';
        });

        fetch(`/admin/dethi/${currentExamId}/questions`)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    questionsData.attached = data.attached;
                    questionsData.available = data.available;
                    
                    // Update header
                    document.getElementById('qmodal-total-questions').innerText = `Tổng cộng: ${data.dethi.so_cau} câu hỏi`;
                    document.getElementById('qmodal-duration').innerText = `Thời gian: ${data.dethi.thoi_gian_lam} phút`;
                    
                    // Render everything
                    renderQuestions();
                }
            })
            .catch(err => {
                console.error(err);
                alert('Có lỗi xảy ra khi tải danh sách câu hỏi.');
            });
    }

    // Filter available questions by search inputs
    const searchInputs = document.querySelectorAll('.search-available');
    searchInputs.forEach(input => {
        input.addEventListener('input', function() {
            const part = this.getAttribute('data-part');
            const query = this.value.toLowerCase().trim();
            renderAvailablePart(part, query);
        });
    });

    // Render both attached and available lists
    function renderQuestions() {
        ['listening', 'reading', 'writing'].forEach(part => {
            renderAvailablePart(part);
            renderAttachedPart(part);
        });
    }

    // Render single available list
    function renderAvailablePart(part, filterQuery = '') {
        const container = document.getElementById(`list-available-${part}`);
        const countSpan = document.getElementById(`count-available-${part}`);
        
        // Filter by part and query
        let list = questionsData.available.filter(q => q.part === part);
        if (filterQuery) {
            list = list.filter(q => 
                (q.noi_dung && q.noi_dung.toLowerCase().includes(filterQuery)) || 
                (q.pinyin && q.pinyin.toLowerCase().includes(filterQuery)) ||
                (q.dich_nghia && q.dich_nghia.toLowerCase().includes(filterQuery)) ||
                (q.loai && q.loai.toLowerCase().includes(filterQuery))
            );
        }

        countSpan.innerText = list.length;

        if (list.length === 0) {
            container.innerHTML = '<div class="text-center py-4 text-muted small">Không tìm thấy câu hỏi phù hợp.</div>';
            return;
        }

        let html = '';
        list.forEach(q => {
            html += `
                <div class="card border-light shadow-sm mb-3 rounded-3" style="transition: all 0.2s; border-color: #f1f5f9 !important;">
                  <div class="card-body p-3 d-flex justify-content-between align-items-center">
                    <div style="max-width: 80%;">
                      <div class="fw-semibold text-dark mb-1" style="font-size: 0.9rem; font-family: 'Noto Sans SC', sans-serif;">${q.noi_dung}</div>
                      ${q.pinyin ? `<div class="small text-muted mb-1" style="font-size: 0.8rem;">${q.pinyin}</div>` : ''}
                      ${q.dich_nghia ? `<div class="small text-muted fst-italic" style="font-size: 0.8rem;">${q.dich_nghia}</div>` : ''}
                      <div class="d-flex align-items-center gap-2 mt-2">
                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-1" style="font-size: 0.7rem;">${q.loai}</span>
                        <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-1" style="font-size: 0.7rem;">${q.muc_do}</span>
                        <span class="small text-muted" style="font-size: 0.75rem;">Mã: Q-${q.id}</span>
                      </div>
                    </div>
                    <button class="btn btn-sm btn-light text-primary rounded-circle p-2 d-flex align-items-center justify-content-center btn-attach-question" 
                            data-id="${q.id}" 
                            style="width: 36px; height: 36px; transition: all 0.2s;" title="Thêm vào đề">
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    </button>
                  </div>
                </div>
            `;
        });
        container.innerHTML = html;

        // Attach listeners
        container.querySelectorAll('.btn-attach-question').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                attachQuestion(id);
            });
        });
    }

    // Render single attached list
    function renderAttachedPart(part) {
        const container = document.getElementById(`list-attached-${part}`);
        const countAttachedHeader = document.getElementById(`count-attached-${part}`);
        const countSelectedHeader = document.getElementById(`count-selected-${part}`);
        
        const list = questionsData.attached.filter(q => q.part === part);
        
        countAttachedHeader.innerText = list.length;
        countSelectedHeader.innerText = `${list.length}`;

        if (list.length === 0) {
            container.innerHTML = '<div class="text-center py-5 text-muted small"><p class="mb-0">Kéo thả hoặc nhấn nút (+) để thêm câu hỏi vào phần này</p></div>';
            return;
        }

        let html = '';
        list.forEach((q, index) => {
            html += `
                <div class="card shadow-sm mb-3 rounded-3 position-relative attached-question-item" 
                     draggable="true" 
                     data-id="${q.id}"
                     style="border: 1px solid rgba(220, 38, 38, 0.15); transition: all 0.2s;">
                  <div class="card-body p-3 d-flex justify-content-between align-items-center bg-white rounded-3">
                    <div class="d-flex align-items-center gap-3" style="max-width: 80%;">
                      <div class="drag-handle text-muted d-flex align-items-center justify-content-center" style="cursor: grab; width: 24px; height: 24px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="5" r="1"></circle><circle cx="9" cy="12" r="1"></circle><circle cx="9" cy="19" r="1"></circle><circle cx="15" cy="5" r="1"></circle><circle cx="15" cy="12" r="1"></circle><circle cx="15" cy="19" r="1"></circle></svg>
                      </div>
                      <div>
                        <div class="fw-bold text-dark mb-1" style="font-size: 0.9rem; font-family: 'Noto Sans SC', sans-serif;">
                          <span class="text-danger me-1">${index + 1}.</span> ${q.noi_dung}
                        </div>
                        ${q.pinyin ? `<div class="small text-muted mb-1" style="font-size: 0.8rem;">${q.pinyin}</div>` : ''}
                        <div class="d-flex align-items-center gap-2 mt-1">
                          <span class="badge bg-danger bg-opacity-10 text-danger rounded-1" style="font-size: 0.65rem;">${q.loai}</span>
                          <span class="small text-muted" style="font-size: 0.75rem;">Mã: Q-${q.id}</span>
                        </div>
                      </div>
                    </div>
                    <button class="btn btn-sm btn-light text-danger rounded-circle p-2 d-flex align-items-center justify-content-center btn-detach-question" 
                            data-id="${q.id}" 
                            style="width: 36px; height: 36px; transition: all 0.2s;" title="Xóa khỏi đề">
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    </button>
                  </div>
                </div>
            `;
        });
        container.innerHTML = html;

        // Attach detach listeners
        container.querySelectorAll('.btn-detach-question').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                detachQuestion(id);
            });
        });

        // Initialize drag-drop sorting for attached elements
        initDragAndDrop(container, part);
    }

    // Attach question AJAX
    function attachQuestion(questionId) {
        fetch(`/admin/dethi/${currentExamId}/questions/attach`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ id_cau_hoi: questionId })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                // Move item from available to attached local data array
                const qIndex = questionsData.available.findIndex(q => q.id == questionId);
                if (qIndex > -1) {
                    const question = questionsData.available[qIndex];
                    question.thu_tu = questionsData.attached.filter(x => x.part === question.part).length + 1;
                    questionsData.attached.push(question);
                    questionsData.available.splice(qIndex, 1);
                    
                    // Update count
                    document.getElementById('qmodal-total-questions').innerText = `Tổng cộng: ${data.so_cau} câu hỏi`;
                    
                    renderQuestions();
                }
            }
        })
        .catch(err => {
            console.error(err);
            alert('Lỗi khi thêm câu hỏi.');
        });
    }

    // Detach question AJAX
    function detachQuestion(questionId) {
        fetch(`/admin/dethi/${currentExamId}/questions/detach`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ id_cau_hoi: questionId })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                // Move item from attached to available local data array
                const qIndex = questionsData.attached.findIndex(q => q.id == questionId);
                if (qIndex > -1) {
                    const question = questionsData.attached[qIndex];
                    delete question.thu_tu;
                    questionsData.available.push(question);
                    questionsData.attached.splice(qIndex, 1);
                    
                    // Update count
                    document.getElementById('qmodal-total-questions').innerText = `Tổng cộng: ${data.so_cau} câu hỏi`;
                    
                    renderQuestions();
                }
            }
        })
        .catch(err => {
            console.error(err);
            alert('Lỗi khi gỡ câu hỏi.');
        });
    }

    // Drag and Drop ordering helper
    function initDragAndDrop(container, part) {
        const items = container.querySelectorAll('.attached-question-item');
        
        items.forEach(item => {
            item.addEventListener('dragstart', () => {
                setTimeout(() => item.classList.add('dragging'), 0);
            });
            
            item.addEventListener('dragend', () => {
                item.classList.remove('dragging');
                
                // Get new sequence of IDs
                const reorderedIds = [];
                const currentContainerItems = container.querySelectorAll('.attached-question-item');
                currentContainerItems.forEach(el => {
                    reorderedIds.push(el.getAttribute('data-id'));
                });
                
                // Save order via AJAX
                saveOrder(reorderedIds);
            });
        });
        
        container.addEventListener('dragover', e => {
            e.preventDefault();
            const draggingItem = container.querySelector('.dragging');
            if (!draggingItem) return;
            
            const siblings = [...container.querySelectorAll('.attached-question-item:not(.dragging)')];
            
            const nextSibling = siblings.find(sibling => {
                const box = sibling.getBoundingClientRect();
                const offset = e.clientY - box.top - box.height / 2;
                return offset < 0;
            });
            
            container.insertBefore(draggingItem, nextSibling);
        });
    }

    // Save ordering AJAX
    function saveOrder(ids) {
        fetch(`/admin/dethi/${currentExamId}/questions/reorder`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ question_ids: ids })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                // Update local attached array ordering index
                const partAttached = questionsData.attached.filter(q => ids.includes(q.id.toString()));
                
                // Sort the local array based on the new ids order
                questionsData.attached = questionsData.attached.map(q => {
                    const newIndex = ids.indexOf(q.id.toString());
                    if (newIndex > -1) {
                        q.thu_tu = newIndex + 1;
                    }
                    return q;
                });
                
                // Re-sort questionsData.attached locally to match new order
                questionsData.attached.sort((a, b) => {
                    if (a.part !== b.part) return 0;
                    return a.thu_tu - b.thu_tu;
                });
                
                // Rerender just to update question sequence numbers (1, 2, 3...)
                renderQuestions();
            }
        })
        .catch(err => {
            console.error(err);
        });
    }
    
    // Reload main window when modal is closed to refresh question counts in main table
    manageQuestionsModal.addEventListener('hidden.bs.modal', function () {
        window.location.reload();
    });
});
</script>
@endsection
