@extends('admin.layouts.main')

@section('title', 'Chi Tiết Hội Thoại - Hanyu Admin')

@section('content')
<div class="page-header animate-fade-in delay-1 mb-4">
  <div class="d-flex align-items-center">
      <a href="{{ route('admin.hoithoai.index') }}" class="btn btn-light btn-sm me-3 text-muted">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
      </a>
      <div>
        <h1 class="fs-4 fw-bold mb-1">Quản lý Câu Thoại</h1>
        <p class="text-muted mb-0 small">Nhóm: <span class="fw-medium text-dark">{{ $hoiThoai->tieu_de ?: 'Chưa có tiêu đề' }}</span></p>
      </div>
  </div>
  <div class="d-flex gap-2">
    <button class="btn btn-success d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#importExcelModal">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
        Nhập từ Excel
    </button>
    <button class="btn btn-primary d-flex align-items-center gap-2 shadow-sm" style="background: var(--admin-primary); border: none;" data-bs-toggle="modal" data-bs-target="#addChiTietModal">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
      Thêm câu thoại
    </button>
  </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show animate-fade-in" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show animate-fade-in">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card bg-white border-0 shadow-sm rounded-3 animate-fade-in delay-2">
  <div class="table-responsive p-3">
    <table class="table table-hover mb-0 align-middle">
      <thead class="table-light text-muted small">
        <tr>
          <th width="5%" class="text-center">TT</th>
          <th width="15%">Nhân vật</th>
          <th width="40%">Nội dung (Trung / Pinyin / Việt)</th>
          <th width="20%">Audio</th>
          <th width="20%" class="text-end">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        @forelse($hoiThoai->chiTietHoiThoais->sortBy('thu_tu') as $chitiet)
        <tr>
            <td class="text-center">{{ $chitiet->thu_tu }}</td>
            <td class="fw-medium text-dark">{{ $chitiet->nhan_vat ?: '-' }}</td>
            <td>
                <div class="mb-1 text-dark fs-5">{{ $chitiet->noi_dung_tieng_trung }}</div>
                @if($chitiet->pinyin)<div class="small text-muted mb-1">{{ $chitiet->pinyin }}</div>@endif
                @if($chitiet->nghia_tieng_viet)<div class="small text-secondary">{{ $chitiet->nghia_tieng_viet }}</div>@endif
            </td>
            <td>
                @if($chitiet->am_thanh)
                    <audio controls style="height: 30px; width: 150px;">
                        <source src="{{ asset('storage/' . $chitiet->am_thanh) }}" type="audio/mpeg">
                    </audio>
                @else
                    <div class="d-flex flex-column align-items-start gap-1">
                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle" style="font-size: 0.65rem;">API Tự động (Google)</span>
                        <audio controls style="height: 30px; width: 150px;">
                            <source src="https://translate.google.com/translate_tts?ie=UTF-8&client=tw-ob&tl=zh-CN&q={{ urlencode($chitiet->noi_dung_tieng_trung) }}" type="audio/mpeg">
                        </audio>
                    </div>
                @endif
            </td>
            <td class="text-end pe-4">
                <div class="d-flex justify-content-end align-items-center gap-1">
                    <button type="button" class="icon-btn" title="Chỉnh sửa" data-bs-toggle="modal" data-bs-target="#editChiTietModal{{ $chitiet->id }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </button>
                    <form action="{{ route('admin.chitiethoithoai.destroy', $chitiet->id) }}" method="POST" class="m-0 p-0 d-flex" onsubmit="return confirm('Bạn có chắc chắn muốn xóa câu thoại này?');">
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
            <td colspan="5" class="text-center py-5 text-muted">
                Chưa có câu thoại nào trong nhóm này. Bấm "Thêm câu thoại" để bắt đầu.
            </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<!-- Edit Modals -->
@foreach($hoiThoai->chiTietHoiThoais as $chitiet)
<div class="modal fade" id="editChiTietModal{{ $chitiet->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg text-start">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.chitiethoithoai.update', $chitiet->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="id_hoi_thoai" value="{{ $hoiThoai->id }}">
                
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Sửa câu thoại</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Nhân vật</label>
                            <input type="text" name="nhan_vat" class="form-control" value="{{ $chitiet->nhan_vat }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Thứ tự</label>
                            <input type="number" name="thu_tu" class="form-control" value="{{ $chitiet->thu_tu }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Tiếng Trung <span class="text-danger">*</span></label>
                        <textarea name="noi_dung_tieng_trung" class="form-control" rows="2" required>{{ $chitiet->noi_dung_tieng_trung }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Pinyin</label>
                        <input type="text" name="pinyin" class="form-control" value="{{ $chitiet->pinyin }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Tiếng Việt</label>
                        <textarea name="nghia_tieng_viet" class="form-control" rows="2">{{ $chitiet->nghia_tieng_viet }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">File Audio (Tuỳ chọn)</label>
                        <input type="file" name="am_thanh" class="form-control" accept="audio/*">
                        @if($chitiet->am_thanh)
                            <div class="mt-2 small text-muted d-flex align-items-center gap-2">
                                <span>Audio hiện tại:</span>
                                <audio controls style="height: 25px; width: 120px;">
                                    <source src="{{ asset('storage/' . $chitiet->am_thanh) }}" type="audio/mpeg">
                                </audio>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary" style="background: var(--admin-primary); border: none;">Lưu Thay Đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<!-- Add Modal -->
<div class="modal fade" id="addChiTietModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg text-start">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.chitiethoithoai.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id_hoi_thoai" value="{{ $hoiThoai->id }}">
                
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Thêm câu thoại mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Nhân vật</label>
                            <input type="text" name="nhan_vat" class="form-control" placeholder="VD: A, B, Lão sư...">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Thứ tự</label>
                            <!-- Tự động tăng số thứ tự lên 1 dựa vào tổng số câu hiện tại -->
                            <input type="number" name="thu_tu" class="form-control" value="{{ $hoiThoai->chiTietHoiThoais->count() + 1 }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Tiếng Trung <span class="text-danger">*</span></label>
                        <textarea name="noi_dung_tieng_trung" class="form-control" rows="2" required placeholder="Nhập Hán tự..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Pinyin</label>
                        <input type="text" name="pinyin" class="form-control" placeholder="VD: nǐ hǎo">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Tiếng Việt</label>
                        <textarea name="nghia_tieng_viet" class="form-control" rows="2" placeholder="Nghĩa tiếng Việt..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">File Audio (Tuỳ chọn)</label>
                        <input type="file" name="am_thanh" class="form-control" accept="audio/*">
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary" style="background: var(--admin-primary); border: none;">Lưu Câu Thoại</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Import Excel Modal -->
<div class="modal fade" id="importExcelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.chitiethoithoai.import', $hoiThoai->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Nhập Câu Thoại từ Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info small mb-3">
                        <i class="fas fa-info-circle me-1"></i> File Excel cần có các cột tiêu đề (dòng 1) bao gồm ít nhất: <strong>tieng_trung</strong> hoặc <strong>noi_dung</strong>. Các cột khác: <strong>nhan_vat, pinyin, tieng_viet, thu_tu</strong>.
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Chọn file Excel (.xlsx, .xls, .csv) <span class="text-danger">*</span></label>
                        <input type="file" name="file_excel" class="form-control" accept=".xlsx,.xls,.csv" required>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success d-flex align-items-center gap-2">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
                        Nhập dữ liệu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
