@extends('admin.layouts.main')

@section('title', 'Ngân hàng câu hỏi — Hányǔ Admin')

@section('content')
<div class="page-header animate-fade-in delay-1">
  <div>
    <h1 class="fs-4 fw-bold mb-1">Ngân hàng câu hỏi</h1>
    <p class="text-muted mb-0 small">Quản lý kho dữ liệu câu hỏi trắc nghiệm, điền khuyết cho các bài thi.</p>
  </div>
  <div class="d-flex align-items-center gap-2">
    <button type="button" class="btn btn-outline-success d-flex align-items-center gap-2 shadow-sm rounded-3" data-bs-toggle="modal" data-bs-target="#importExcelModal">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
      Import Excel
    </button>
    <a href="{{ route('admin.cauhoi.create') ?? '#' }}" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm text-decoration-none" style="background: var(--admin-primary); border: none;">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
      Thêm câu hỏi mới
    </a>
  </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show animate-fade-in" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Question Data Table -->
<div class="card bg-white border-0 shadow-sm rounded-3 animate-fade-in delay-2 mb-4">
  <!-- Filter & Search Toolbar -->
  <div class="card-header bg-white border-bottom-0 pt-4 pb-2 d-flex flex-wrap gap-3">
    <form action="{{ route('admin.cauhoi.index') ?? '#' }}" method="GET" class="d-flex w-100 flex-wrap gap-3">
        <div class="input-group" style="max-width: 350px;">
        <span class="input-group-text bg-white border-end-0 text-muted">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        </span>
        <input type="text" name="search" value="{{ request('search') }}" class="form-control border-start-0 ps-0" placeholder="Tìm kiếm nội dung (Hán tự, pinyin, tiếng Việt)...">
        </div>
        
        <div class="d-flex gap-2 ms-auto">
        <select class="form-select" name="loai_cau_hoi" onchange="this.form.submit()" style="width: auto;">
            <option value="">Tất cả loại câu hỏi</option>
            @if(isset($loaiCauHois))
                @foreach($loaiCauHois as $loai)
                    <option value="{{ $loai->id }}" {{ request('loai_cau_hoi') == $loai->id ? 'selected' : '' }}>{{ $loai->ten_loai }}</option>
                @endforeach
            @endif
        </select>
        <select class="form-select" name="id_muc_do" onchange="this.form.submit()" style="width: auto;">
            <option value="">Tất cả độ khó</option>
            @if(isset($mucDos))
                @foreach($mucDos as $muc)
                    <option value="{{ $muc->id }}" {{ request('id_muc_do') == $muc->id ? 'selected' : '' }}>{{ $muc->ten_muc_do }}</option>
                @endforeach
            @endif
        </select>
        </div>
    </form>
  </div>

  <!-- Table -->
  <div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead class="table-light text-muted small">
        <tr>
          <th class="fw-medium px-4 py-3" style="width: 50%;">Nội dung câu hỏi</th>
          <th class="fw-medium py-3">Loại câu hỏi</th>
          <th class="fw-medium py-3">Độ khó</th>
          <th class="fw-medium py-3">Thuộc bài / Đề</th>
          <th class="fw-medium pe-4 py-3 text-end">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        @forelse($cauHois ?? [] as $item)
        <tr>
          <td class="px-4 py-3">
            <div class="fw-semibold text-dark fs-6" style="font-family: 'Noto Sans SC', sans-serif;">{{ $item->noi_dung }}</div>
            @if($item->pinyin)
            <div class="small text-muted mb-1">{{ $item->pinyin }}</div>
            @endif
            @if($item->dich_nghia)
            <div class="small text-muted fst-italic">{{ $item->dich_nghia }}</div>
            @endif
            @if($item->am_thanh)
            <div class="mt-2 mb-2">
              <audio controls style="height: 35px; max-width: 250px;">
                <source src="{{ asset('storage/' . $item->am_thanh) }}" type="audio/mpeg">
                Trình duyệt không hỗ trợ audio.
              </audio>
            </div>
            @endif

            <div class="mt-2 text-success small fw-medium">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><polyline points="20 6 9 17 4 12"></polyline></svg>
              Đáp án đúng: 
              @php
                  $correctAnswers = $item->dapAns->where('dung', true)->pluck('noi_dung')->join(', ');
              @endphp
              {{ $correctAnswers ?: 'Chưa cập nhật' }}
            </div>
          </td>
          <td>
            <span class="badge bg-light text-dark border">
                {{ $item->loaiCauHoi->ten_loai ?? 'N/A' }}
            </span>
          </td>
          <td>
            @php
                $mucDoName = $item->mucDo->ten_muc_do ?? 'N/A';
            @endphp
            @if($mucDoName == 'Dễ')
                <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle">{{ $mucDoName }}</span>
            @elseif($mucDoName == 'Trung bình')
                <span class="badge bg-warning bg-opacity-10 text-warning-emphasis border border-warning-subtle">{{ $mucDoName }}</span>
            @elseif($mucDoName == 'Khó')
                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger-subtle">{{ $mucDoName }}</span>
            @else
                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle">{{ $mucDoName }}</span>
            @endif
          </td>
          <td class="small text-muted">{{ $item->baiHoc->ten_bai_hoc ?? 'N/A' }}</td>
          <td class="text-end pe-4">
            <div class="d-flex justify-content-end gap-1">
              <a href="{{ route('admin.cauhoi.edit', $item->id) ?? '#' }}" class="icon-btn" title="Chỉnh sửa">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
              </a>
              <form action="{{ route('admin.cauhoi.destroy', $item->id) ?? '#' }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa không?');">
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
            <td colspan="5" class="text-center py-4 text-muted">Chưa có câu hỏi nào.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  
  <!-- Pagination -->
  @if(isset($cauHois) && $cauHois->hasPages())
  <div class="card-footer bg-white border-top p-3 d-flex justify-content-between align-items-center">
    <span class="text-muted small">Hiển thị từ {{ $cauHois->firstItem() }} đến {{ $cauHois->lastItem() }} trong tổng số {{ $cauHois->total() }} câu hỏi</span>
    {{ $cauHois->links('pagination::bootstrap-5') }}
  </div>
  @endif
</div>

<!-- Modal Import Excel -->
<div class="modal fade" id="importExcelModal" tabindex="-1" aria-labelledby="importExcelModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('admin.cauhoi.import') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="modal-content">
        <div class="modal-header border-0 pb-0">
          <h5 class="modal-title fw-bold" id="importExcelModalLabel">Import Câu hỏi từ Excel</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4">
          <div class="alert alert-info small mb-4">
            <strong>Hướng dẫn:</strong> 
            Vui lòng chuẩn bị file Excel theo cấu trúc chuẩn. Các cột: <code>bai_hoc</code>, <code>loai_cau_hoi</code>, <code>muc_do</code>, <code>noi_dung</code>, <code>pinyin</code>, <code>dap_an_a</code>, <code>pinyin_a</code>, <code>dap_an_b</code>, <code>pinyin_b</code>, <code>dap_an_c</code>, <code>pinyin_c</code>, <code>dap_an_d</code>, <code>pinyin_d</code>, <code>dap_an_dung</code> (A/B/C/D).
          </div>
          
          <div class="mb-3">
            <label class="form-label fw-medium">Chọn Bài học mặc định (Tuỳ chọn)</label>
            <select name="id_bai_hoc" class="form-select">
              <option value="">-- Dùng cấu hình trong file Excel --</option>
              @foreach(\App\Models\BaiHoc::all() as $bh)
                <option value="{{ $bh->id }}">{{ $bh->ten_bai_hoc }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium">Chọn Loại Câu hỏi mặc định</label>
            <select name="id_loai_cau_hoi" class="form-select">
              <option value="">-- Dùng cấu hình trong file Excel --</option>
              @foreach($loaiCauHois as $lch)
                <option value="{{ $lch->id }}">{{ $lch->ten_loai }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-4">
            <label class="form-label fw-medium">Chọn Mức độ mặc định</label>
            <select name="id_muc_do" class="form-select">
              <option value="">-- Dùng cấu hình trong file Excel --</option>
              @foreach($mucDos as $md)
                <option value="{{ $md->id }}">{{ $md->ten_muc_do }}</option>
              @endforeach
            </select>
          </div>

          <div>
            <label class="form-label fw-medium">File Excel (.xlsx, .csv)</label>
            <input type="file" name="excel_file" class="form-control" accept=".xlsx, .xls, .csv" required>
          </div>
        </div>
        <div class="modal-footer border-0 pt-0 pb-4 px-4">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy bỏ</button>
          <button type="submit" class="btn btn-success px-4">Import Dữ liệu</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
