@extends('admin.layouts.main')

@section('title', 'Quản lý Lộ trình — Hányǔ Admin')

@section('content')
      <div class="page-header animate-fade-in delay-1">
        <div>
          <h1 class="fs-4 fw-bold mb-1">Quản lý Lộ trình Học (Roadmaps)</h1>
          <p class="text-muted mb-0 small">Thiết kế lộ trình học tập, nhóm các khóa học lại để định hướng học viên từ sơ cấp đến nâng cao.</p>
        </div>
        <button class="btn btn-primary d-flex align-items-center gap-2 shadow-sm" style="background: var(--admin-primary); border: none;" data-bs-toggle="modal" data-bs-target="#addRoadmapModal">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
          Thêm lộ trình mới
        </button>
      </div>

      @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show animate-fade-in my-3" role="alert">
        <div class="d-flex align-items-center gap-2">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
          {{ session('success') }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      @endif

      @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show animate-fade-in my-3" role="alert">
        <div class="d-flex align-items-center gap-2">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
          {{ session('error') }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      @endif

      @if($errors->any())
      <div class="alert alert-danger alert-dismissible fade show animate-fade-in my-3" role="alert">
        <div class="d-flex flex-column gap-1">
          @foreach($errors->all() as $error)
            <div class="d-flex align-items-center gap-2">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
              {{ $error }}
            </div>
          @endforeach
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      @endif

      <!-- Roadmap Data Table -->
      <div class="table-card animate-fade-in delay-2">
        <div class="table-header d-flex flex-wrap gap-3">
          <div class="input-group" style="max-width: 300px;">
            <span class="input-group-text bg-white border-end-0 text-muted">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </span>
            <input type="text" class="form-control border-start-0 ps-0" placeholder="Tìm tên lộ trình...">
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-hover mb-0 align-middle">
            <thead class="table-light text-muted small">
              <tr>
                <th class="fw-medium px-4 py-3" style="width: 80px;">Thứ tự</th>
                <th class="fw-medium py-3">Tên lộ trình</th>
                <th class="fw-medium py-3">Số lượng khóa học</th>
                <th class="fw-medium py-3">Học viên tham gia</th>
                <th class="fw-medium py-3">Trạng thái</th>
                <th class="fw-medium pe-4 py-3 text-end">Thao tác</th>
              </tr>
            </thead>
            <tbody>
              @forelse($loTrinhs as $loTrinh)
              <tr>
                <td class="px-4 py-3 text-muted fw-bold">{{ $loTrinh->thu_tu }}</td>
                <td>
                  <div class="d-flex align-items-center gap-3">
                    <div style="width: 80px; height: 50px; background: #fee2e2; border-radius: 6px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                      <img src="{{ $loTrinh->anh_bia ? Storage::url($loTrinh->anh_bia) : 'https://images.unsplash.com/photo-1546422904-90eab23c3d7e?auto=format&fit=crop&w=80&q=80' }}" alt="Thumbnail" class="img-fluid opacity-75" style="object-fit: cover; width: 100%; height: 100%;">
                    </div>
                    <div>
                      <div class="fw-bold text-dark fs-6">{{ $loTrinh->ten_lo_trinh }}</div>
                      <div class="small text-muted">Mục tiêu: {{ $loTrinh->mo_ta_ngan }}</div>
                    </div>
                  </div>
                </td>
                <td class="fw-medium text-dark">
                  <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle">Gồm {{ $loTrinh->giaiDoans->sum(fn($gd) => $gd->khoaHocs->count()) }} khóa học</span>
                </td>
                <td class="text-muted">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg> 0
                </td>
                <td>
                  @if($loTrinh->trang_thai)
                  <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle">Đang mở</span>
                  @else
                  <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle">Bản nháp</span>
                  @endif
                </td>
                <td class="text-end pe-4">
                  <div class="d-flex justify-content-end gap-1">
                    <a href="{{ route('admin.lotrinh.show', $loTrinh->id) }}" class="icon-btn text-primary text-decoration-none" title="Thiết lập danh sách khóa học">
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                    </a>
                    <button class="icon-btn" title="Chỉnh sửa" data-bs-toggle="modal" data-bs-target="#editRoadmapModal{{ $loTrinh->id }}">
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </button>
                    <form action="{{ route('admin.lotrinh.destroy', $loTrinh->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa lộ trình này?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="icon-btn text-danger" title="Xóa" style="border:none; background:none;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path></svg>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="6" class="text-center py-4 text-muted">Chưa có lộ trình nào được tạo.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

    <!-- Modal Thêm Lộ Trình -->
    <div class="modal fade" id="addRoadmapModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow" style="background: var(--admin-card);">
          <div class="modal-header border-bottom border-light">
            <h5 class="modal-title fw-bold">Thêm Lộ trình Học mới</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="{{ route('admin.lotrinh.store') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label fw-medium">Tên lộ trình</label>
                  <input type="text" name="ten_lo_trinh" class="form-control" placeholder="VD: Lộ trình Chinh phục HSK 4" required>
                </div>
                <div class="col-md-3 mb-3">
                  <label class="form-label fw-medium">Thứ tự hiển thị</label>
                  <input type="number" name="thu_tu" class="form-control" value="0">
                </div>
                <div class="col-md-3 mb-3">
                  <label class="form-label fw-medium">Trạng thái</label>
                  <select name="trang_thai" class="form-select">
                    <option value="1">Hiển thị</option>
                    <option value="0">Ẩn</option>
                  </select>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label fw-medium">Mô tả ngắn gọn (Mục tiêu)</label>
                <input type="text" name="mo_ta_ngan" class="form-control" placeholder="VD: Đạt HSK 4 trong 6 tháng dành cho người mất gốc">
              </div>
              
              <div class="mb-3">
                <label class="form-label fw-medium">Mô tả chi tiết</label>
                <textarea name="mo_ta" class="form-control" rows="3" placeholder="Chi tiết các lợi ích và đầu ra của lộ trình này..."></textarea>
              </div>

              <div class="mb-3">
                <label class="form-label fw-medium">Ảnh Cover Lộ trình</label>
                <input class="form-control" type="file" name="anh_bia" accept="image/*">
                <div class="form-text">Tỉ lệ ảnh khuyến nghị: 16:9 (Ví dụ 1280x720px).</div>
              </div>

              <div class="modal-footer border-top border-light d-flex justify-content-between px-0 pb-0 mt-4">
                <div class="text-muted small"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>Sau khi tạo lộ trình, bạn có thể thêm các khóa học vào trong lộ trình này.</div>
                <div>
                  <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy bỏ</button>
                  <button type="submit" class="btn btn-primary" style="background: var(--admin-primary); border: none;">Lưu lộ trình</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Edit Roadmap Modals -->
    @foreach($loTrinhs as $loTrinh)
    <div class="modal fade" id="editRoadmapModal{{ $loTrinh->id }}" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow" style="background: var(--admin-card);">
          <div class="modal-header border-bottom border-light">
            <h5 class="modal-title fw-bold">Chỉnh sửa Lộ trình</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="{{ route('admin.lotrinh.update', $loTrinh->id) }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label fw-medium">Tên lộ trình</label>
                  <input type="text" name="ten_lo_trinh" class="form-control" value="{{ $loTrinh->ten_lo_trinh }}" required>
                </div>
                <div class="col-md-3 mb-3">
                  <label class="form-label fw-medium">Thứ tự hiển thị</label>
                  <input type="number" name="thu_tu" class="form-control" value="{{ $loTrinh->thu_tu }}">
                </div>
                <div class="col-md-3 mb-3">
                  <label class="form-label fw-medium">Trạng thái</label>
                  <select name="trang_thai" class="form-select">
                    <option value="1" {{ $loTrinh->trang_thai == 1 ? 'selected' : '' }}>Hiển thị</option>
                    <option value="0" {{ $loTrinh->trang_thai == 0 ? 'selected' : '' }}>Ẩn</option>
                  </select>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label fw-medium">Mô tả ngắn gọn (Mục tiêu)</label>
                <input type="text" name="mo_ta_ngan" class="form-control" value="{{ $loTrinh->mo_ta_ngan }}">
              </div>
              
              <div class="mb-3">
                <label class="form-label fw-medium">Mô tả chi tiết</label>
                <textarea name="mo_ta" class="form-control" rows="3">{{ $loTrinh->mo_ta }}</textarea>
              </div>

              <div class="mb-3">
                <label class="form-label fw-medium">Ảnh Cover Lộ trình</label>
                <input class="form-control mb-2" type="file" name="anh_bia" accept="image/*">
                @if($loTrinh->anh_bia)
                  <img src="{{ Storage::url($loTrinh->anh_bia) }}" alt="Current Cover" style="height: 60px; border-radius: 4px; object-fit: cover;">
                @endif
                <div class="form-text">Tỉ lệ ảnh khuyến nghị: 16:9 (Ví dụ 1280x720px). Tải lên ảnh mới nếu muốn thay đổi ảnh hiện tại.</div>
              </div>

              <div class="modal-footer border-top border-light d-flex justify-content-between px-0 pb-0 mt-4">
                <div class="text-muted small">Thông tin sẽ được cập nhật lập tức trên hệ thống.</div>
                <div>
                  <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy bỏ</button>
                  <button type="submit" class="btn btn-primary" style="background: var(--admin-primary); border: none;">Cập nhật lộ trình</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    @endforeach
@endsection
