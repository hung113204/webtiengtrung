@extends('admin.layouts.main')

@section('title', 'Quản lý Ngữ pháp — Hányǔ Admin')

@section('content')
<div class="page-header animate-fade-in delay-1">
  <div>
    <h1 class="fs-4 fw-bold mb-1">Quản lý Ngữ pháp</h1>
    <p class="text-muted mb-0 small">Thiết lập cấu trúc ngữ pháp, ví dụ minh họa và giải thích chi tiết.</p>
  </div>
  <div class="d-flex gap-2">
    <button class="btn btn-outline-success d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#importExcelModal">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
      Nhập từ Excel
    </button>
    <button class="btn btn-primary d-flex align-items-center gap-2 shadow-sm" style="background: var(--admin-primary); border: none;" data-bs-toggle="modal" data-bs-target="#addNguPhapModal">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
      Thêm cấu trúc mới
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
      <input type="text" class="form-control border-start-0 ps-0" placeholder="Tìm theo tên cấu trúc ngữ pháp...">
    </div>
    
    <div class="d-flex gap-2 ms-auto">
      <select class="form-select form-select-sm" style="width: auto;">
        <option value="">Tất cả bài học</option>
        @foreach($baiHocs as $bh)
            <option value="{{ $bh->id }}">{{ $bh->ten_bai_hoc }}</option>
        @endforeach
      </select>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead class="table-light text-muted small">
        <tr>
          <th class="fw-medium px-4 py-3" style="width: 25%;">Cấu trúc</th>
          <th class="fw-medium py-3">Công thức mẫu</th>
          <th class="fw-medium py-3">Bài học liên kết</th>
          <th class="fw-medium pe-4 py-3 text-end">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        @forelse($nguPhaps as $nguPhap)
        <tr>
          <td class="px-4 py-3">
            <div class="d-flex flex-column">
              <span class="fs-5 fw-bold text-dark" style="font-family: 'Noto Sans SC', sans-serif;">{{ $nguPhap->tieu_de }}</span>
            </div>
          </td>
          <td><code class="text-dark bg-light px-2 py-1 rounded border">{{ $nguPhap->cau_truc }}</code></td>
          <td>
            <span class="badge bg-light text-dark border">{{ $nguPhap->baiHoc->ten_bai_hoc ?? 'N/A' }}</span>
          </td>
          <td class="text-end pe-4">
            <div class="d-flex justify-content-end gap-1">
              <button class="icon-btn" title="Xem chi tiết" data-bs-toggle="modal" data-bs-target="#viewNguPhapModal{{ $nguPhap->id }}">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
              </button>
              <button class="icon-btn" title="Sửa" data-bs-toggle="modal" data-bs-target="#editNguPhapModal{{ $nguPhap->id }}">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
              </button>
              <form action="{{ route('admin.nguphap.destroy', $nguPhap->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa ngữ pháp này không?');">
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
            <td colspan="4" class="text-center py-4 text-muted">Chưa có điểm ngữ pháp nào.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <!-- Pagination -->
  @if($nguPhaps->hasPages())
  <div class="card-footer bg-white border-top p-3 d-flex justify-content-end">
      {{ $nguPhaps->links('pagination::bootstrap-5') }}
  </div>
  @endif
</div>

<!-- Modal Thêm Ngữ Pháp -->
<div class="modal fade" id="addNguPhapModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-bottom border-light">
        <h5 class="modal-title fw-bold">Thêm Cấu Trúc Ngữ Pháp Mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <form id="addNguPhapForm" action="{{ route('admin.nguphap.store') }}" method="POST">
          @csrf
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium">Tiêu đề (Tên cấu trúc)</label>
              <input type="text" class="form-control @error('tieu_de') is-invalid @enderror" name="tieu_de" value="{{ old('tieu_de') }}" required placeholder="VD: Câu chữ 是 (shì)">
              @error('tieu_de')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
            <label class="form-label fw-medium">Cấu trúc mẫu</label>
            <input type="text" class="form-control @error('cau_truc') is-invalid @enderror" name="cau_truc" value="{{ old('cau_truc') }}" required placeholder="VD: A + 是 + B">
            @error('cau_truc')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium">Giải thích chi tiết</label>
            <textarea class="form-control @error('giai_thich') is-invalid @enderror" name="giai_thich" rows="4" required placeholder="Giải thích cách dùng...">{{ old('giai_thich') }}</textarea>
            @error('giai_thich')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium">Câu ví dụ (Tùy chọn)</label>
            <textarea class="form-control @error('vi_du') is-invalid @enderror" name="vi_du" rows="3" placeholder="VD: 我是学生。 (Tôi là học sinh.)">{{ old('vi_du') }}</textarea>
            @error('vi_du')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
        </form>
      </div>
      <div class="modal-footer border-top border-light">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy bỏ</button>
        <button type="submit" form="addNguPhapForm" class="btn btn-primary" style="background: var(--admin-primary); border: none;">Lưu ngữ pháp</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Nhập Excel -->
<div class="modal fade" id="importExcelModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-bottom border-light">
        <h5 class="modal-title fw-bold">Nhập Ngữ Pháp Từ Excel</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <div class="alert alert-info small">
          <strong>Lưu ý:</strong> File Excel (.xlsx, .csv) cần có dòng tiêu đề (header) bao gồm các cột sau (viết thường, không dấu):
          <code>tieu_de</code>, <code>cau_truc</code>, <code>giai_thich</code>, <code>vi_du</code> (hoặc cau_vi_du).
        </div>
        <form id="importExcelForm" action="{{ route('admin.nguphap.import') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="mb-3">
            <label class="form-label fw-medium">Chọn bài học để lưu (Tùy chọn)</label>
            <select class="form-select @error('excel_file') is-invalid @enderror" name="id_bai_hoc">
              <option value="">-- Tự động nhận diện từ cột "Bài học" trong Excel --</option>
              @foreach($baiHocs as $baiHoc)
                  <option value="{{ $baiHoc->id }}">{{ $baiHoc->ten_bai_hoc }}</option>
              @endforeach
            </select>
            <div class="form-text small text-muted mt-1">Nếu chọn, toàn bộ file sẽ được lưu vào bài học này (bỏ qua cột Bài học trong Excel).</div>
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

<!-- Modal Xem/Sửa Ngữ Pháp -->
@foreach($nguPhaps as $nguPhap)
<!-- Modal Xem -->
<div class="modal fade" id="viewNguPhapModal{{ $nguPhap->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-bottom border-light">
        <h5 class="modal-title fw-bold">Chi Tiết Ngữ Pháp</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <h4 class="mb-3" style="font-family: 'Noto Sans SC', sans-serif;">{{ $nguPhap->tieu_de }}</h4>
        <div class="mb-3">
            <strong>Cấu trúc:</strong> <code class="text-dark bg-light px-2 py-1 rounded border">{{ $nguPhap->cau_truc }}</code>
        </div>
        <div class="mb-3">
            <strong>Bài học:</strong> <span class="badge bg-light text-dark border">{{ $nguPhap->baiHoc->ten_bai_hoc ?? 'N/A' }}</span>
        </div>
        <div class="mb-3">
            <strong>Giải thích:</strong>
            <p class="mt-2 text-muted">{{ $nguPhap->giai_thich }}</p>
        </div>
        @if($nguPhap->vi_du)
        <div class="mb-3">
            <strong>Ví dụ:</strong>
            <p class="mt-2 text-muted">{{ $nguPhap->vi_du }}</p>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Modal Sửa -->
<div class="modal fade" id="editNguPhapModal{{ $nguPhap->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-bottom border-light">
        <h5 class="modal-title fw-bold">Sửa Cấu Trúc Ngữ Pháp</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <form id="editNguPhapForm{{ $nguPhap->id }}" action="{{ route('admin.nguphap.update', $nguPhap->id) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium">Tiêu đề</label>
              <input type="text" class="form-control @error('tieu_de') is-invalid @enderror" name="tieu_de" value="{{ old('tieu_de', $nguPhap->tieu_de) }}" required>
              @error('tieu_de')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium">Bài học trực thuộc</label>
              <select class="form-select @error('id_bai_hoc') is-invalid @enderror" name="id_bai_hoc" required>
                <option value="">-- Chọn bài học --</option>
                @foreach($baiHocs as $baiHoc)
                    <option value="{{ $baiHoc->id }}" {{ old('id_bai_hoc', $nguPhap->id_bai_hoc) == $baiHoc->id ? 'selected' : '' }}>{{ $baiHoc->ten_bai_hoc }}</option>
                @endforeach
              </select>
              @error('id_bai_hoc')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium">Cấu trúc mẫu</label>
            <input type="text" class="form-control @error('cau_truc') is-invalid @enderror" name="cau_truc" value="{{ old('cau_truc', $nguPhap->cau_truc) }}" required>
            @error('cau_truc')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium">Giải thích chi tiết</label>
            <textarea class="form-control @error('giai_thich') is-invalid @enderror" name="giai_thich" rows="4" required>{{ old('giai_thich', $nguPhap->giai_thich) }}</textarea>
            @error('giai_thich')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium">Câu ví dụ (Tùy chọn)</label>
            <textarea class="form-control @error('vi_du') is-invalid @enderror" name="vi_du" rows="3">{{ old('vi_du', $nguPhap->vi_du) }}</textarea>
            @error('vi_du')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
        </form>
      </div>
      <div class="modal-footer border-top border-light">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy bỏ</button>
        <button type="submit" form="editNguPhapForm{{ $nguPhap->id }}" class="btn btn-primary" style="background: var(--admin-primary); border: none;">Lưu thay đổi</button>
      </div>
    </div>
  </div>
</div>
@endforeach

@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var myModal = new bootstrap.Modal(document.getElementById('addNguPhapModal'));
        myModal.show();
    });
</script>
@endif

@endsection
