@extends('admin.layouts.main')

@section('title', 'Quản lý Từ vựng — Hányǔ Admin')

@section('content')
<div class="page-header animate-fade-in delay-1">
  <div>
    <h1 class="fs-4 fw-bold mb-1">Quản lý Từ vựng</h1>
    <p class="text-muted mb-0 small">Danh sách từ vựng, quản lý phiên âm và hệ thống phát âm.</p>
  </div>
  <div class="d-flex gap-2">
    <button class="btn btn-outline-success d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#importExcelModal">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
      Nhập từ Excel
    </button>
    <button class="btn btn-primary d-flex align-items-center gap-2 shadow-sm" style="background: var(--admin-primary); border: none;" data-bs-toggle="modal" data-bs-target="#addTuVungModal">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
      Thêm từ vựng mới
    </button>
  </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show animate-fade-in" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Table Card -->
<div class="table-card animate-fade-in delay-2">
  <div class="table-header d-flex flex-wrap gap-3">
    <div class="input-group" style="max-width: 300px;">
      <span class="input-group-text bg-white border-end-0 text-muted">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
      </span>
      <input type="text" class="form-control border-start-0 ps-0" placeholder="Tìm theo Hán tự hoặc Pinyin...">
    </div>
    
    <div class="d-flex gap-2 ms-auto">
      <select class="form-select form-select-sm" style="width: auto;">
        <option value="">Khóa học liên kết</option>
        @foreach($baiHocs as $baiHoc)
            <option value="{{ $baiHoc->id }}">{{ $baiHoc->ten_bai_hoc }}</option>
        @endforeach
      </select>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead class="table-light text-muted small">
        <tr>
          <th class="fw-medium px-4 py-3" style="width: 25%;">Từ vựng (Hán tự & Pinyin)</th>
          <th class="fw-medium py-3">Bài học trực thuộc</th>
          <th class="fw-medium py-3">Nghĩa tiếng Việt</th>
          <th class="fw-medium py-3">Âm thanh</th>
          <th class="fw-medium pe-4 py-3 text-end">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        @forelse($tuVungs as $tuVung)
        <tr>
          <td class="px-4 py-3">
            <div class="d-flex align-items-center gap-3">
                @if($tuVung->hinh_anh)
                    <img src="{{ Storage::url($tuVung->hinh_anh) }}" alt="Thumbnail" class="rounded object-fit-cover" style="width: 48px; height: 48px;">
                @else
                    <div class="rounded d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: var(--bg-color);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-muted"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"></path></svg>
                    </div>
                @endif
              <div class="d-flex flex-column">
                <span class="fs-5 fw-bold text-dark" style="font-family: 'Noto Sans SC', sans-serif;">{{ $tuVung->tu_han }}</span>
                <span class="small text-danger fw-medium">{{ $tuVung->phien_am }}</span>
              </div>
            </div>
          </td>
          <td>
            <span class="badge bg-light text-dark border">{{ $tuVung->baiHoc->ten_bai_hoc ?? 'N/A' }}</span>
          </td>
          <td class="text-dark">{{ $tuVung->nghia_tieng_viet }}</td>
          <td>
            @if($tuVung->am_thanh)
            <button class="btn btn-sm btn-light border rounded-circle d-flex align-items-center justify-content-center p-1" style="width: 28px; height: 28px;" title="Nghe phát âm" onclick="new Audio('{{ Storage::url($tuVung->am_thanh) }}').play()">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon><path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path></svg>
            </button>
            @else
            <span class="text-muted small">N/A</span>
            @endif
          </td>
          <td class="text-end pe-4">
            <div class="d-flex justify-content-end gap-1">
              <button class="icon-btn d-flex align-items-center" title="Sửa" data-bs-toggle="modal" data-bs-target="#editTuVungModal{{ $tuVung->id }}">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
              </button>
              <form action="{{ route('admin.tuvung.destroy', $tuVung->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa từ vựng này không?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="icon-btn text-danger d-flex align-items-center" title="Xóa">
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path></svg>
                  </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center py-4 text-muted">Chưa có từ vựng nào.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <!-- Pagination -->
  @if($tuVungs->hasPages())
  <div class="card-footer bg-white border-top p-3 d-flex justify-content-end">
      {{ $tuVungs->links('pagination::bootstrap-5') }}
  </div>
  @endif
</div>

<!-- Modal Thêm Từ Vựng -->
<div class="modal fade" id="addTuVungModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-bottom border-light">
        <h5 class="modal-title fw-bold">Thêm Từ Vựng Mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <form id="addTuVungForm" action="{{ route('admin.tuvung.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium">Chữ Hán</label>
              <input type="text" class="form-control @error('tu_han') is-invalid @enderror" name="tu_han" value="{{ old('tu_han') }}" required placeholder="VD: 你好">
              @error('tu_han')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium">Phiên âm (Pinyin)</label>
              <input type="text" class="form-control @error('phien_am') is-invalid @enderror" name="phien_am" value="{{ old('phien_am') }}" required placeholder="VD: nǐ hǎo">
              @error('phien_am')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium">Nghĩa tiếng Việt</label>
              <input type="text" class="form-control @error('nghia_tieng_viet') is-invalid @enderror" name="nghia_tieng_viet" value="{{ old('nghia_tieng_viet') }}" required placeholder="VD: Xin chào">
              @error('nghia_tieng_viet')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium">Bài học trực thuộc</label>
              <select class="form-select @error('id_bai_hoc') is-invalid @enderror" name="id_bai_hoc" required>
                <option value="">-- Chọn bài học --</option>
                @foreach($baiHocs as $baiHoc)
                    <option value="{{ $baiHoc->id }}" {{ old('id_bai_hoc') == $baiHoc->id ? 'selected' : '' }}>{{ $baiHoc->ten_bai_hoc }}</option>
                @endforeach
              </select>
              @error('id_bai_hoc')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium">Câu ví dụ (Tùy chọn)</label>
            <textarea class="form-control @error('vi_du') is-invalid @enderror" name="vi_du" rows="2" placeholder="VD: 你好吗？- Nǐ hǎo ma? - Bạn khỏe không?">{{ old('vi_du') }}</textarea>
            @error('vi_du')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium">Ảnh minh họa (Tùy chọn)</label>
              <input class="form-control @error('hinh_anh') is-invalid @enderror" type="file" name="hinh_anh" accept="image/*">
              @error('hinh_anh')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium">Audio phát âm (Tùy chọn)</label>
              <input class="form-control @error('am_thanh') is-invalid @enderror" type="file" name="am_thanh" accept="audio/*">
              @error('am_thanh')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer border-top border-light">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy bỏ</button>
        <button type="submit" form="addTuVungForm" class="btn btn-primary" style="background: var(--admin-primary); border: none;">Lưu từ vựng</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Nhập Excel -->
<div class="modal fade" id="importExcelModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-bottom border-light">
        <h5 class="modal-title fw-bold">Nhập Từ Vựng Từ Excel</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <div class="alert alert-info small">
          <strong>Lưu ý:</strong> File Excel (.xlsx, .csv) cần có dòng tiêu đề (header) bao gồm các cột sau (viết thường, không dấu):
          <code>chu_han</code>, <code>pinyin</code>, <code>nghia</code>, <code>vi_du</code> (tùy chọn).
        </div>
        <form id="importExcelForm" action="{{ route('admin.tuvung.import') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="mb-3">
            <label class="form-label fw-medium">Chọn bài học để lưu (Tùy chọn)</label>
            <select class="form-select @error('excel_file') is-invalid @enderror" name="id_bai_hoc">
              <option value="">-- Tự động nhận diện từ cột "Bài học" trong Excel --</option>
              @foreach($baiHocs as $baiHoc)
                  <option value="{{ $baiHoc->id }}">{{ $baiHoc->ten_bai_hoc }}</option>
              @endforeach
            </select>
            <div class="form-text small text-muted mt-1">Nếu bạn chọn bài học ở đây, toàn bộ từ vựng trong file sẽ được lưu vào bài học này, bỏ qua cột "Bài học" trong file Excel.</div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-medium">Tải lên file Excel</label>
            <input class="form-control @error('excel_file') is-invalid @enderror" type="file" name="excel_file" accept=".xlsx, .xls, .csv" required>
            @error('excel_file')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
        </form>
      </div>
      <div class="modal-footer border-top border-light">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy bỏ</button>
        <button type="submit" form="importExcelForm" class="btn btn-success">Tiến hành Nhập</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Sửa Từ Vựng -->
@foreach($tuVungs as $tuVung)
<div class="modal fade" id="editTuVungModal{{ $tuVung->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-bottom border-light">
        <h5 class="modal-title fw-bold">Sửa Từ Vựng</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <form id="editTuVungForm{{ $tuVung->id }}" action="{{ route('admin.tuvung.update', $tuVung->id) }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium">Chữ Hán</label>
              <input type="text" class="form-control @error('tu_han') is-invalid @enderror" name="tu_han" value="{{ old('tu_han', $tuVung->tu_han) }}" required>
              @error('tu_han')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium">Phiên âm (Pinyin)</label>
              <input type="text" class="form-control @error('phien_am') is-invalid @enderror" name="phien_am" value="{{ old('phien_am', $tuVung->phien_am) }}" required>
              @error('phien_am')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium">Nghĩa tiếng Việt</label>
              <input type="text" class="form-control @error('nghia_tieng_viet') is-invalid @enderror" name="nghia_tieng_viet" value="{{ old('nghia_tieng_viet', $tuVung->nghia_tieng_viet) }}" required>
              @error('nghia_tieng_viet')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium">Bài học trực thuộc</label>
              <select class="form-select @error('id_bai_hoc') is-invalid @enderror" name="id_bai_hoc" required>
                <option value="">-- Chọn bài học --</option>
                @foreach($baiHocs as $baiHoc)
                    <option value="{{ $baiHoc->id }}" {{ (old('id_bai_hoc', $tuVung->id_bai_hoc) == $baiHoc->id) ? 'selected' : '' }}>{{ $baiHoc->ten_bai_hoc }}</option>
                @endforeach
              </select>
              @error('id_bai_hoc')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium">Câu ví dụ (Tùy chọn)</label>
            <textarea class="form-control @error('vi_du') is-invalid @enderror" name="vi_du" rows="2">{{ old('vi_du', $tuVung->vi_du) }}</textarea>
            @error('vi_du')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium">Ảnh minh họa</label>
              <input class="form-control @error('hinh_anh') is-invalid @enderror" type="file" name="hinh_anh" accept="image/*">
              @if($tuVung->hinh_anh)
                  <div class="mt-2 text-muted small">Đã có ảnh minh họa. Tải lên file mới để thay thế.</div>
              @endif
              @error('hinh_anh')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium">Audio phát âm</label>
              <input class="form-control @error('am_thanh') is-invalid @enderror" type="file" name="am_thanh" accept="audio/*">
              @if($tuVung->am_thanh)
                  <div class="mt-2 text-muted small">Đã có audio phát âm. Tải lên file mới để thay thế.</div>
              @endif
              @error('am_thanh')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer border-top border-light">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy bỏ</button>
        <button type="submit" form="editTuVungForm{{ $tuVung->id }}" class="btn btn-primary" style="background: var(--admin-primary); border: none;">Lưu thay đổi</button>
      </div>
    </div>
  </div>
</div>
@endforeach

@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var myModal = new bootstrap.Modal(document.getElementById('addTuVungModal'));
        myModal.show();
    });
</script>
@endif

@endsection
