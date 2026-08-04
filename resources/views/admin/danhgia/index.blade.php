@extends('admin.layouts.main')

@section('title', 'Quản lý Đánh giá — Hányǔ Admin')

@section('content')
<div class="page-header animate-fade-in delay-1">
    <div>
        <h1 class="fs-4 fw-bold mb-1">Quản lý Đánh giá</h1>
        <p class="text-muted mb-0 small">Thiết lập các đánh giá từ học viên cho khóa học.</p>
    </div>
    <button class="btn btn-primary d-flex align-items-center gap-2 shadow-sm" style="background: var(--admin-primary); border: none;" data-bs-toggle="modal" data-bs-target="#addDanhGiaModal">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        Thêm đánh giá mới
    </button>
</div>

<!-- Alert thông báo -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show animate-fade-in delay-1" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show animate-fade-in delay-1" role="alert">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Data Table -->
<div class="table-card animate-fade-in delay-2">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light text-muted small">
                <tr>
                    <th class="fw-medium px-4 py-3" style="width: 80px;">ID</th>
                    <th class="fw-medium py-3">Học viên</th>
                    <th class="fw-medium py-3">Khóa học</th>
                    <th class="fw-medium py-3 text-center">Số sao</th>
                    <th class="fw-medium py-3">Nội dung</th>
                    <th class="fw-medium py-3 text-center">Hiển thị</th>
                    <th class="fw-medium pe-4 py-3 text-end" style="width: 120px;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($danhsach as $item)
                <tr>
                    <td class="px-4 py-3 text-muted">{{ $item->id }}</td>
                    <td>
                        <span class="fw-bold">{{ optional($item->nguoiDung)->ho_ten ?? 'Unknown' }}</span>
                    </td>
                    <td>
                        <span class="badge bg-secondary">{{ optional($item->khoaHoc)->ten_khoa_hoc ?? 'Unknown' }}</span>
                    </td>
                    <td class="text-center fw-medium text-warning">{{ $item->so_sao }} <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg></td>
                    <td>
                        <div class="small text-muted" style="max-width:250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ $item->noi_dung }}
                        </div>
                    </td>
                    <td class="text-center">
                        @if($item->trang_thai)
                        <span class="badge bg-success">Hiện</span>
                        @else
                        <span class="badge bg-danger">Ẩn</span>
                        @endif
                    </td>
                    <td class="text-end pe-4">
                        <div class="d-flex justify-content-end gap-1">
                            <button type="button" class="icon-btn" title="Chỉnh sửa" data-bs-toggle="modal" data-bs-target="#editDanhGiaModal{{ $item->id }}">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            </button>
                            <form action="{{ route('admin.danhgia.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
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
                    <td colspan="7" class="text-center py-4 text-muted">Chưa có đánh giá nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($danhsach->hasPages())
    <div class="p-3 border-top border-light">
        {{ $danhsach->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

<!-- Modal Thêm -->
<div class="modal fade" id="addDanhGiaModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow" style="background: var(--admin-card);">
            <form action="{{ route('admin.danhgia.store') }}" method="POST">
                @csrf
                <div class="modal-header border-bottom border-light">
                    <h5 class="modal-title fw-bold">Thêm Đánh giá mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Học viên <span class="text-danger">*</span></label>
                            <select class="form-select" name="id_nguoi_dung" required>
                                <option value="">Chọn học viên</option>
                                @foreach($nguoiDungs as $nd)
                                <option value="{{ $nd->id }}">{{ $nd->ho_ten }} ({{ $nd->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Khóa học <span class="text-danger">*</span></label>
                            <select class="form-select" name="id_khoa_hoc" required>
                                <option value="">Chọn khóa học</option>
                                @foreach($khoaHocs as $kh)
                                <option value="{{ $kh->id }}">{{ $kh->ten_khoa_hoc }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-medium">Số sao (1-5) <span class="text-danger">*</span></label>
                            <input type="number" min="1" max="5" class="form-control" name="so_sao" value="5" required>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="form-label fw-medium">Tiêu đề</label>
                            <input type="text" class="form-control" name="tieu_de" placeholder="Nhập tiêu đề...">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Nội dung <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="noi_dung" rows="3" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Ưu điểm</label>
                            <textarea class="form-control" name="uu_diem" rows="2"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Nhược điểm</label>
                            <textarea class="form-control" name="nhuoc_diem" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" role="switch" name="trang_thai" id="trang_thai_new" checked value="1">
                        <label class="form-check-label fw-medium" for="trang_thai_new">Hiển thị trên trang chủ</label>
                    </div>
                </div>
                <div class="modal-footer border-top border-light">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy bỏ</button>
                    <button type="submit" class="btn btn-primary" style="background: var(--admin-primary); border: none;">Thêm mới</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Sửa -->
@foreach($danhsach as $item)
<div class="modal fade" id="editDanhGiaModal{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow" style="background: var(--admin-card);">
            <form action="{{ route('admin.danhgia.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header border-bottom border-light">
                    <h5 class="modal-title fw-bold">Chỉnh sửa Đánh giá</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Học viên <span class="text-danger">*</span></label>
                            <select class="form-select" name="id_nguoi_dung" required>
                                @foreach($nguoiDungs as $nd)
                                <option value="{{ $nd->id }}" {{ $item->id_nguoi_dung == $nd->id ? 'selected' : '' }}>{{ $nd->ho_ten }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Khóa học <span class="text-danger">*</span></label>
                            <select class="form-select" name="id_khoa_hoc" required>
                                @foreach($khoaHocs as $kh)
                                <option value="{{ $kh->id }}" {{ $item->id_khoa_hoc == $kh->id ? 'selected' : '' }}>{{ $kh->ten_khoa_hoc }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-medium">Số sao (1-5) <span class="text-danger">*</span></label>
                            <input type="number" min="1" max="5" class="form-control" name="so_sao" value="{{ $item->so_sao }}" required>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="form-label fw-medium">Tiêu đề</label>
                            <input type="text" class="form-control" name="tieu_de" value="{{ $item->tieu_de }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Nội dung <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="noi_dung" rows="3" required>{{ $item->noi_dung }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Ưu điểm</label>
                            <textarea class="form-control" name="uu_diem" rows="2">{{ $item->uu_diem }}</textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Nhược điểm</label>
                            <textarea class="form-control" name="nhuoc_diem" rows="2">{{ $item->nhuoc_diem }}</textarea>
                        </div>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" role="switch" name="trang_thai" id="trang_thai_{{ $item->id }}" value="1" {{ $item->trang_thai ? 'checked' : '' }}>
                        <label class="form-check-label fw-medium" for="trang_thai_{{ $item->id }}">Hiển thị trên trang chủ</label>
                    </div>
                </div>
                <div class="modal-footer border-top border-light">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy bỏ</button>
                    <button type="submit" class="btn btn-primary" style="background: var(--admin-primary); border: none;">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection
