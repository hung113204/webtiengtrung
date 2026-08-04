@extends('admin.layouts.main')

@section('title', 'Quản lý Hồ sơ học viên — Hányǔ Admin')

@section('content')
    <div class="page-header animate-fade-in delay-1">
        <div>
            <h1 class="fs-4 fw-bold mb-1">Hồ sơ học tập</h1>
            <p class="text-muted mb-0 small">Quản lý hồ sơ, mục tiêu và lộ trình học tập của học viên.</p>
        </div>
        <button class="btn btn-primary d-flex align-items-center gap-2 shadow-sm" style="background: var(--admin-primary); border: none;" data-bs-toggle="modal" data-bs-target="#createModal">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Tạo hồ sơ mới
        </button>
    </div>

    <!-- Data Table Card -->
    <div class="table-card animate-fade-in delay-2">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light text-muted small">
                    <tr>
                        <th class="fw-medium px-4 py-3">Học viên</th>
                        <th class="fw-medium py-3">Trình độ hiện tại</th>
                        <th class="fw-medium py-3">Mục tiêu học tập</th>
                        <th class="fw-medium py-3">Thời gian dự kiến</th>
                        <th class="fw-medium pe-4 py-3 text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hosos as $item)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ optional($item->nguoiDung)->anh_dai_dien ? Storage::url($item->nguoiDung->anh_dai_dien) : 'https://ui-avatars.com/api/?name='.urlencode(optional($item->nguoiDung)->ho_ten ?? 'Unknown').'&background=random' }}" class="rounded-circle border border-2 border-white shadow-sm" width="42" height="42" style="object-fit: cover;" alt="Avatar">
                                    <div>
                                        <div class="fw-semibold text-dark">{{ optional($item->nguoiDung)->ho_ten ?? 'Học viên đã xóa' }}</div>
                                        <div class="small text-muted">{{ optional($item->nguoiDung)->email ?? 'Không có email' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($item->trinh_do_hien_tai)
                                    <span class="badge bg-light text-primary border border-primary-subtle px-2 py-1">{{ $item->trinh_do_hien_tai }}</span>
                                @else
                                    <span class="text-muted small">Chưa rõ</span>
                                @endif
                            </td>
                            <td>
                                @if($item->muc_tieu_hoc_tap)
                                    <span class="badge bg-light text-danger border border-danger-subtle px-2 py-1">{{ $item->muc_tieu_hoc_tap }}</span>
                                @else
                                    <span class="text-muted small">Chưa thiết lập</span>
                                @endif
                            </td>
                            <td>
                                <div class="small text-dark">{{ $item->thoi_gian_hoc_du_kien ?? '---' }}</div>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-1">
                                    <button class="icon-btn text-primary" title="Chỉnh sửa" data-bs-toggle="modal" data-bs-target="#editModal_{{ $item->id }}">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </button>
                                    <form action="{{ route('admin.hosohocvien.destroy', $item->id) }}" method="POST" style="display:inline; margin:0; padding:0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="icon-btn text-danger btn-delete" title="Xóa hồ sơ" onclick="deleteDataAjax({{ $item->id }}, 'Hồ sơ của {{ addslashes(optional($item->nguoiDung)->ho_ten) }}', '{{ route('admin.hosohocvien.destroy', $item->id) }}')" style="background:none; border:none;">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6" /><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <div class="mb-3">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-muted opacity-50"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                                </div>
                                <h6 class="fw-medium text-dark mb-1">Chưa có hồ sơ học viên nào</h6>
                                <p class="small text-muted mb-3">Hồ sơ sẽ xuất hiện ở đây khi bạn tạo mới</p>
                                <button class="btn btn-sm btn-light border" data-bs-toggle="modal" data-bs-target="#createModal">Tạo hồ sơ ngay</button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modals -->
    @foreach($hosos as $item)
    <div class="modal fade" id="editModal_{{ $item->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <form action="{{ route('admin.hosohocvien.update', $item->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Chỉnh sửa Hồ sơ Học tập</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-start">
                        <div class="d-flex align-items-center gap-3 mb-4 p-3 bg-light rounded-3">
                            <img src="{{ optional($item->nguoiDung)->anh_dai_dien ? Storage::url($item->nguoiDung->anh_dai_dien) : 'https://ui-avatars.com/api/?name='.urlencode(optional($item->nguoiDung)->ho_ten ?? 'Unknown').'&background=random' }}" class="rounded-circle border shadow-sm" width="48" height="48" style="object-fit: cover;" alt="Avatar">
                            <div>
                                <h6 class="mb-0 fw-bold">{{ optional($item->nguoiDung)->ho_ten ?? 'Học viên đã xóa' }}</h6>
                                <div class="small text-muted">{{ optional($item->nguoiDung)->email }}</div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Trình độ hiện tại</label>
                                <select class="form-select" name="trinh_do_hien_tai">
                                    <option value="">-- Trống --</option>
                                    <option value="Mới bắt đầu" {{ $item->trinh_do_hien_tai == 'Mới bắt đầu' ? 'selected' : '' }}>Mới bắt đầu</option>
                                    <option value="Trung cấp" {{ $item->trinh_do_hien_tai == 'Trung cấp' ? 'selected' : '' }}>Trung cấp</option>
                                    <option value="Nâng cao" {{ $item->trinh_do_hien_tai == 'Nâng cao' ? 'selected' : '' }}>Nâng cao</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Mục tiêu học tập</label>
                                <select class="form-select" name="muc_tieu_hoc_tap">
                                    <option value="">-- Trống --</option>
                                    <option value="Luyện thi HSK" {{ $item->muc_tieu_hoc_tap == 'Luyện thi HSK' ? 'selected' : '' }}>Luyện thi HSK</option>
                                    <option value="Giao tiếp / Du lịch" {{ $item->muc_tieu_hoc_tap == 'Giao tiếp / Du lịch' ? 'selected' : '' }}>Giao tiếp / Du lịch</option>
                                    <option value="Công việc" {{ $item->muc_tieu_hoc_tap == 'Công việc' ? 'selected' : '' }}>Công việc</option>
                                    <option value="Du học" {{ $item->muc_tieu_hoc_tap == 'Du học' ? 'selected' : '' }}>Du học</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Thời gian dự kiến</label>
                                <input type="text" class="form-control" name="thoi_gian_hoc_du_kien" value="{{ $item->thoi_gian_hoc_du_kien }}" placeholder="VD: 6 tháng">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary px-4" style="background: var(--admin-primary); border: none;">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Modal Thêm mới -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <form action="{{ route('admin.hosohocvien.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Tạo Hồ sơ học tập</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-start">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-medium text-dark">Chọn Học viên <span class="text-danger">*</span></label>
                                <select class="form-select bg-light border-primary" name="id_nguoi_dung" required>
                                    <option value="">-- Chọn học viên chưa có hồ sơ --</option>
                                    @foreach($hocViens as $hv)
                                        <option value="{{ $hv->id }}">{{ $hv->ho_ten }} ({{ $hv->email }})</option>
                                    @endforeach
                                </select>
                                @if($hocViens->isEmpty())
                                    <div class="form-text text-danger mt-2"><i class="fas fa-exclamation-circle"></i> Tất cả học viên hiện tại đều đã có hồ sơ học tập.</div>
                                @endif
                            </div>
                            <div class="col-12 mt-4">
                                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Thông tin hồ sơ</h6>
                            </div>
                            <div class="col-12 mt-0">
                                <label class="form-label">Trình độ hiện tại</label>
                                <select class="form-select" name="trinh_do_hien_tai">
                                    <option value="">-- Trống --</option>
                                    <option value="Mới bắt đầu">Mới bắt đầu</option>
                                    <option value="Trung cấp">Trung cấp</option>
                                    <option value="Nâng cao">Nâng cao</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Mục tiêu học tập</label>
                                <select class="form-select" name="muc_tieu_hoc_tap">
                                    <option value="">-- Trống --</option>
                                    <option value="Luyện thi HSK">Luyện thi HSK</option>
                                    <option value="Giao tiếp / Du lịch">Giao tiếp / Du lịch</option>
                                    <option value="Công việc">Công việc</option>
                                    <option value="Du học">Du học</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Thời gian dự kiến</label>
                                <input type="text" class="form-control" name="thoi_gian_hoc_du_kien" placeholder="VD: 6 tháng">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary px-4" style="background: var(--admin-primary); border: none;" {{ $hocViens->isEmpty() ? 'disabled' : '' }}>Tạo mới</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
