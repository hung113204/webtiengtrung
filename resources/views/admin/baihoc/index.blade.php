@extends('admin.layouts.main')

@section('title', 'Quản lý Bài học — Hányǔ Admin')

@section('content')
<div class="page-header animate-fade-in delay-1">
  <div>
    <h1 class="fs-4 fw-bold mb-1">Quản lý Bài học</h1>
    <p class="text-muted mb-0 small">Thiết lập lộ trình học, video bài giảng và nội dung chi tiết cho từng bài học.</p>
  </div>
  <button class="btn btn-primary d-flex align-items-center gap-2 shadow-sm" style="background: var(--admin-primary); border: none;" data-bs-toggle="modal" data-bs-target="#addLessonModal">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
    Thêm bài học mới
  </button>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show animate-fade-in" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show animate-fade-in" role="alert">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Lessons Data Table -->
<div class="table-card animate-fade-in delay-2">
  <div class="table-header d-flex flex-wrap gap-3">
    <div class="input-group" style="max-width: 300px;">
      <span class="input-group-text bg-white border-end-0 text-muted">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
      </span>
      <input type="text" class="form-control border-start-0 ps-0" placeholder="Tìm tên bài học...">
    </div>
    
    <select class="form-select" style="max-width: 180px;">
      <option value="">Tất cả khóa học</option>
      @foreach($khoaHocs as $khoaHoc)
        <option value="{{ $khoaHoc->id }}">{{ $khoaHoc->ten_khoa_hoc }}</option>
      @endforeach
    </select>
  </div>

  <div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead class="table-light text-muted small">
        <tr>
          <th class="fw-medium px-4 py-3" style="width: 80px;">STT</th>
          <th class="fw-medium py-3">Tên bài học</th>
          <th class="fw-medium py-3">Khóa / Chương</th>
          <th class="fw-medium py-3">Nội dung đính kèm</th>
          <th class="fw-medium py-3">Trạng thái</th>
          <th class="fw-medium pe-4 py-3 text-end">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        @forelse($baiHocs as $index => $baiHoc)
        <tr>
          <td class="px-4 py-3 fw-bold text-muted">{{ $index + 1 }}</td>
          <td>
            <div class="d-flex align-items-center gap-3">
              <div style="width: 60px; height: 40px; background: #e0e7ff; border-radius: 6px; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative;">
                @if($baiHoc->anh_bia)
                    <img src="{{ asset('storage/' . $baiHoc->anh_bia) }}" alt="Thumbnail" class="img-fluid opacity-75" style="object-fit: cover; width: 100%; height: 100%;">
                @elseif($baiHoc->videoItem && $baiHoc->videoItem->thumbnail_path)
                    <img src="{{ asset('storage/' . $baiHoc->videoItem->thumbnail_path) }}" alt="Video Thumbnail" class="img-fluid opacity-75" style="object-fit: cover; width: 100%; height: 100%;">
                @else
                    <svg class="position-absolute text-primary" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                @endif
              </div>
              <div>
                <div class="fw-bold text-dark fs-6">{{ $baiHoc->ten_bai_hoc }}</div>
                <div class="small text-muted">{{ $baiHoc->mo_ta_ngan ?? 'Không có mô tả' }}</div>
              </div>
            </div>
          </td>
          <td>
            <span class="badge bg-light text-dark border">{{ $baiHoc->chuongHoc->khoaHoc->ten_khoa_hoc ?? 'N/A' }}</span><br>
            <small class="text-muted">{{ $baiHoc->chuongHoc->ten_chuong ?? 'N/A' }}</small>
          </td>
          <td class="small text-muted">
            <div class="d-flex flex-wrap gap-2">
              @if($baiHoc->video)
                <span class="badge bg-info bg-opacity-10 text-info border border-info-subtle" title="Có video bài giảng">Video</span>
              @endif
              @if(isset($baiHoc->metadata['tu_vung']) && $baiHoc->metadata['tu_vung'] > 0)
                <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle" title="Có {{ $baiHoc->metadata['tu_vung'] }} từ vựng">Từ vựng ({{ $baiHoc->metadata['tu_vung'] }})</span>
              @endif
              @if(isset($baiHoc->metadata['ngu_phap']) && $baiHoc->metadata['ngu_phap'] > 0)
                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning-subtle" title="Có {{ $baiHoc->metadata['ngu_phap'] }} cấu trúc ngữ pháp">Ngữ pháp ({{ $baiHoc->metadata['ngu_phap'] }})</span>
              @endif
            </div>
          </td>
          <td>
            @if($baiHoc->trang_thai == 'published')
                <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle">Hiển thị</span>
            @elseif($baiHoc->trang_thai == 'draft')
                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle">Bản nháp</span>
            @else
                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger-subtle">Đã lưu trữ</span>
            @endif
          </td>
          <td class="text-end pe-4">
            <div class="d-flex justify-content-end align-items-center gap-1">
              <a href="{{ route('admin.baihoc.show', $baiHoc->id) }}" class="icon-btn text-primary" title="Quản lý nội dung (Từ vựng, Ngữ pháp...)">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
              </a>
              <button class="icon-btn" title="Chỉnh sửa" data-bs-toggle="modal" data-bs-target="#editLessonModal{{ $baiHoc->id }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
              </button>
              <form action="{{ route('admin.baihoc.destroy', $baiHoc->id) }}" method="POST" class="m-0 p-0 d-flex" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài học này?');">
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
            <td colspan="6" class="text-center py-4 text-muted">Chưa có bài học nào. Hãy thêm bài học đầu tiên!</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<!-- Modal Thêm Bài Học -->
<div class="modal fade" id="addLessonModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow" style="background: var(--admin-card);">
      <div class="modal-header border-bottom border-light">
        <h5 class="modal-title fw-bold">Thêm Bài học mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('admin.baihoc.store') }}" method="POST" enctype="multipart/form-data" id="addLessonForm">
          @csrf
          <div class="row">
            <div class="col-md-8 mb-3">
              <label class="form-label fw-medium">Tên bài học (Tiếng Việt)</label>
              <input type="text" class="form-control" name="ten_bai_hoc" placeholder="VD: Bài 1: Xin chào" required>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label fw-medium">Thứ tự hiển thị</label>
              <input type="number" class="form-control" name="thu_tu" placeholder="VD: 1" value="1">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium">Tên bài học (Tiếng Trung & Pinyin)</label>
            <input type="text" class="form-control" name="mo_ta_ngan" placeholder="VD: 第一课：你好 (Dì yī kè: Nǐ hǎo)">
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium">Cấp độ HSK (Tùy chọn)</label>
              <select class="form-select" name="id_cap_do_hsk">
                <option value="">-- Không phân loại --</option>
                @foreach($capDoHsks as $hsk)
                    <option value="{{ $hsk->id }}">{{ $hsk->ten_cap_do }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium">Thời lượng (giây)</label>
              <input type="number" class="form-control" name="thoi_luong_giay" value="0" min="0">
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium">Khóa học / Chương học trực thuộc</label>
              <select class="form-select" name="id_chuong" required>
                <option value="">-- Chọn chương học --</option>
                @foreach($khoaHocs as $khoaHoc)
                    @if($khoaHoc->chuongHocs->count() > 0)
                        <optgroup label="{{ $khoaHoc->ten_khoa_hoc }}">
                            @foreach($khoaHoc->chuongHocs as $chuong)
                                <option value="{{ $chuong->id }}">{{ $chuong->ten_chuong }}</option>
                            @endforeach
                        </optgroup>
                    @endif
                @endforeach
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium">Trạng thái</label>
              <select class="form-select" name="trang_thai" required>
                <option value="published">Hiển thị (Đang mở)</option>
                <option value="draft">Ẩn (Bản nháp)</option>
                <option value="archived">Lưu trữ</option>
              </select>
            </div>
          </div>

          <div class="mb-3">
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" role="switch" id="mien_phi_add" name="mien_phi" value="1">
              <label class="form-check-label" for="mien_phi_add">Cho phép học thử miễn phí</label>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium">Nội dung chi tiết (Tùy chọn)</label>
            <textarea class="form-control ckeditor" name="noi_dung" rows="3" placeholder="Ghi chú, lý thuyết ngắn gọn..."></textarea>
          </div>



          <div class="mb-3">
            <label class="form-label fw-medium">Ảnh Thumbnail / Cover</label>
            <input class="form-control" type="file" name="anh_bia" accept="image/*">
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium">File Audio luyện nghe (Tùy chọn)</label>
              <input class="form-control" type="file" name="audio" accept="audio/*">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium">Tài liệu đính kèm (Tùy chọn)</label>
              <input class="form-control" type="file" name="tai_lieu" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.rar">
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer border-top border-light">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy bỏ</button>
        <button type="button" class="btn btn-primary" onclick="document.getElementById('addLessonForm').submit()" style="background: var(--admin-primary); border: none;">Lưu bài học</button>
      </div>
    </div>
  </div>
</div>
@endsection

<!-- Modals Sửa Bài Học -->
@foreach($baiHocs as $baiHoc)
<div class="modal fade" id="editLessonModal{{ $baiHoc->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow" style="background: var(--admin-card);">
      <div class="modal-header border-bottom border-light">
        <h5 class="modal-title fw-bold">Chỉnh sửa Bài học</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-start">
        <form action="{{ route('admin.baihoc.update', $baiHoc->id) }}" method="POST" enctype="multipart/form-data" id="editLessonForm{{ $baiHoc->id }}">
          @csrf
          @method('PUT')
          <div class="row">
            <div class="col-md-8 mb-3">
              <label class="form-label fw-medium">Tên bài học (Tiếng Việt)</label>
              <input type="text" class="form-control" name="ten_bai_hoc" value="{{ $baiHoc->ten_bai_hoc }}" required>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label fw-medium">Thứ tự hiển thị</label>
              <input type="number" class="form-control" name="thu_tu" value="{{ $baiHoc->thu_tu }}">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium">Tên bài học (Tiếng Trung & Pinyin)</label>
            <input type="text" class="form-control" name="mo_ta_ngan" value="{{ $baiHoc->mo_ta_ngan }}">
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium">Cấp độ HSK (Tùy chọn)</label>
              <select class="form-select" name="id_cap_do_hsk">
                <option value="">-- Không phân loại --</option>
                @foreach($capDoHsks as $hsk)
                    <option value="{{ $hsk->id }}" {{ $baiHoc->id_cap_do_hsk == $hsk->id ? 'selected' : '' }}>{{ $hsk->ten_cap_do }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium">Thời lượng (giây)</label>
              <input type="number" class="form-control" name="thoi_luong_giay" value="{{ $baiHoc->thoi_luong_giay }}" min="0">
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium">Khóa học / Chương học trực thuộc</label>
              <select class="form-select" name="id_chuong" required>
                <option value="">-- Chọn chương học --</option>
                @foreach($khoaHocs as $khoaHoc)
                    @if($khoaHoc->chuongHocs->count() > 0)
                        <optgroup label="{{ $khoaHoc->ten_khoa_hoc }}">
                            @foreach($khoaHoc->chuongHocs as $chuong)
                                <option value="{{ $chuong->id }}" {{ $baiHoc->id_chuong == $chuong->id ? 'selected' : '' }}>{{ $chuong->ten_chuong }}</option>
                            @endforeach
                        </optgroup>
                    @endif
                @endforeach
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium">Trạng thái</label>
              <select class="form-select" name="trang_thai" required>
                <option value="published" {{ $baiHoc->trang_thai == 'published' ? 'selected' : '' }}>Hiển thị (Đang mở)</option>
                <option value="draft" {{ $baiHoc->trang_thai == 'draft' ? 'selected' : '' }}>Ẩn (Bản nháp)</option>
                <option value="archived" {{ $baiHoc->trang_thai == 'archived' ? 'selected' : '' }}>Lưu trữ</option>
              </select>
            </div>
          </div>

          <div class="mb-3">
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" role="switch" id="mien_phi_{{ $baiHoc->id }}" name="mien_phi" value="1" {{ $baiHoc->mien_phi ? 'checked' : '' }}>
              <label class="form-check-label" for="mien_phi_{{ $baiHoc->id }}">Cho phép học thử miễn phí</label>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium">Nội dung chi tiết (Tùy chọn)</label>
            <textarea class="form-control ckeditor" name="noi_dung" rows="3">{{ $baiHoc->noi_dung }}</textarea>
          </div>



          <div class="mb-3">
            <label class="form-label fw-medium">Ảnh Thumbnail / Cover</label>
            <input class="form-control" type="file" name="anh_bia" accept="image/*">
            @if($baiHoc->anh_bia)
                <div class="mt-2 text-muted small">Đã có ảnh bìa. Tải lên file mới để thay thế.</div>
            @endif
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium">File Audio luyện nghe</label>
              <input class="form-control" type="file" name="audio" accept="audio/*">
              @if($baiHoc->audio)
                  <div class="mt-2 text-muted small">Đã tải lên audio. Tải file mới để thay thế.</div>
              @endif
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium">Tài liệu đính kèm</label>
              <input class="form-control" type="file" name="tai_lieu" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.rar">
              @if($baiHoc->tai_lieu)
                  <div class="mt-2 text-muted small">Đã đính kèm tài liệu. Tải file mới để thay thế.</div>
              @endif
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer border-top border-light">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy bỏ</button>
        <button type="button" class="btn btn-primary" onclick="document.getElementById('editLessonForm{{ $baiHoc->id }}').submit()" style="background: var(--admin-primary); border: none;">Lưu thay đổi</button>
      </div>
    </div>
  </div>
</div>
@endforeach
