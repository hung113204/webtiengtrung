@extends('admin.layouts.main')

@section('title', 'Quản lý Người dùng — Hányǔ Admin')

@section('content')
      <div class="page-header animate-fade-in delay-1">
        <div>
          <h1 class="fs-4 fw-bold mb-1">Quản lý người dùng</h1>
          <p class="text-muted mb-0 small">Quản lý danh sách học viên, giáo viên và quyền truy cập hệ thống.</p>
        </div>
        <button class="btn btn-primary d-flex align-items-center gap-2 shadow-sm" style="background: var(--admin-primary); border: none;" data-bs-toggle="modal" data-bs-target="#createModal">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
          Thêm người dùng mới
        </button>
      </div>

      <!-- Data Table Card -->
      <div class="table-card animate-fade-in delay-2">
        <form action="{{ route('admin.nguoidung.index') }}" method="GET" class="table-header d-flex flex-wrap gap-3 p-3 border-bottom">
          <div class="input-group" style="max-width: 300px;">
            <span class="input-group-text bg-white border-end-0 text-muted">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </span>
            <input type="text" name="search" value="{{ request('search') }}" class="form-control border-start-0 ps-0" placeholder="Tìm kiếm tên, email, sđt...">
          </div>
          
          <div class="d-flex gap-2 ms-auto">
            <select name="role_id" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
              <option value="">Tất cả vai trò</option>
              @foreach($vaiTros as $vt)
              <option value="{{ $vt->id }}" {{ request('role_id') == $vt->id ? 'selected' : '' }}>{{ $vt->ten_vai_tro }}</option>
              @endforeach
            </select>
            <select name="status" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
              <option value="">Trạng thái</option>
              <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Đang hoạt động</option>
              <option value="banned" {{ request('status') === 'banned' ? 'selected' : '' }}>Bị khóa</option>
            </select>
            <button type="submit" class="d-none"></button>
          </div>
        </form>

        <div class="table-responsive">
          <table class="table table-hover mb-0 align-middle">
            <thead class="table-light text-muted small">
              <tr>
                <th class="fw-medium px-4 py-3">Người dùng</th>
                <th class="fw-medium py-3">Vai trò</th>
                <th class="fw-medium py-3">Ngày tham gia</th>
                <th class="fw-medium py-3">Đăng nhập cuối</th>
                <th class="fw-medium py-3">Trạng thái</th>
                <th class="fw-medium pe-4 py-3 text-end">Thao tác</th>
              </tr>
            </thead>
            <tbody>
              @forelse($nguoidungs as $item)
              <tr>
                <td class="px-4 py-3">
                  <div class="d-flex align-items-center gap-3">
                    <img src="{{ $item->anh_dai_dien ? Storage::url($item->anh_dai_dien) : 'https://ui-avatars.com/api/?name='.urlencode($item->ho_ten).'&background=random' }}" class="rounded-circle border border-2 border-white shadow-sm" width="42" height="42" style="object-fit: cover;" alt="Avatar">
                    <div>
                      <div class="fw-semibold text-dark">{{ $item->ho_ten }}</div>
                      <div class="small text-muted">{{ $item->email }}</div>
                    </div>
                  </div>
                </td>
                <td>
                    @if($item->vaiTro && $item->vaiTro->ten_vai_tro === 'Admin')
                        <span class="badge bg-danger">{{ $item->vaiTro->ten_vai_tro }}</span>
                    @elseif($item->vaiTro && ($item->vaiTro->ten_vai_tro === 'Giảng viên' || $item->vaiTro->ten_vai_tro === 'Giáo viên'))
                        <span class="badge" style="background-color: var(--admin-primary); opacity: 0.9;">{{ $item->vaiTro->ten_vai_tro }}</span>
                    @else
                        <span class="badge bg-light text-dark border">{{ $item->vaiTro->ten_vai_tro ?? 'Học viên' }}</span>
                    @endif
                </td>
                <td class="small text-muted">{{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') : 'N/A' }}</td>
                <td class="small text-muted">
                    @if($item->last_login_at)
                        <span title="{{ \Carbon\Carbon::parse($item->last_login_at)->format('d/m/Y H:i') }}">{{ \Carbon\Carbon::parse($item->last_login_at)->diffForHumans() }}</span>
                    @else
                        <span class="text-muted fst-italic">Chưa đăng nhập</span>
                    @endif
                </td>
                <td>
                    @if($item->trang_thai)
                        <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle">Hoạt động</span>
                    @elseif($item->user_token)
                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning-subtle">Chờ xác thực</span>
                    @else
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger-subtle">Bị khóa</span>
                    @endif
                </td>
                <td class="text-end pe-4">
                  <div class="d-flex justify-content-end gap-1">
                    <button class="icon-btn text-info" title="Xem chi tiết" data-bs-toggle="modal" data-bs-target="#showModal_{{ $item->id }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </button>
                    <button class="icon-btn text-primary" title="Chỉnh sửa" data-bs-toggle="modal" data-bs-target="#editModal_{{ $item->id }}"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></button>
                    <form action="{{ route('admin.nguoidung.destroy', $item->id) }}" method="POST" style="display:inline; margin:0; padding:0;">
                        @csrf
                        <?php echo method_field('DELETE'); ?>
                        <button type="button" class="icon-btn text-danger btn-delete" title="Xóa người dùng" onclick="deleteDataAjax({{ $item->id }}, '{{ addslashes($item->ho_ten) }}', '{{ route('admin.nguoidung.destroy', $item->id) }}')" style="background:none; border:none;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6" /><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" /></svg>
                        </button>
                    </form>
                  </div>
                </td>
              </tr>

              @empty
              <tr>
                  <td colspan="5" class="text-center py-4 text-muted">Chưa có người dùng nào trong hệ thống.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        </div>
        @if($nguoidungs->hasPages())
        <div class="p-3 border-top d-flex justify-content-end">
            {{ $nguoidungs->links('pagination::bootstrap-5') }}

        </div>
        @endif
      </div>

      <!-- Danh sách Modal Sửa Người dùng (đặt ngoài table để không bị lỗi hiển thị CSS) -->
      @foreach($nguoidungs as $item)
      
      <!-- Modal Xem chi tiết -->
      <div class="modal fade" id="showModal_{{ $item->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow-lg">
              <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Chi tiết người dùng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body text-start pt-2">
                <div class="d-flex align-items-center gap-3 mb-4 p-3 bg-light rounded-3">
                    <img src="{{ $item->anh_dai_dien ? Storage::url($item->anh_dai_dien) : 'https://ui-avatars.com/api/?name='.urlencode($item->ho_ten).'&background=random' }}" class="rounded-circle border border-2 border-white shadow-sm" width="70" height="70" style="object-fit: cover;" alt="Avatar">
                    <div>
                        <h5 class="mb-1 fw-bold">{{ $item->ho_ten }}</h5>
                        <div class="mb-1">
                            <span class="badge {{ $item->vaiTro && $item->vaiTro->ten_vai_tro === 'Admin' ? 'bg-danger' : ($item->vaiTro && ($item->vaiTro->ten_vai_tro === 'Giảng viên' || $item->vaiTro->ten_vai_tro === 'Giáo viên') ? 'bg-primary' : 'bg-secondary') }}">
                                {{ $item->vaiTro->ten_vai_tro ?? 'Học viên' }}

                            </span>
                            @if($item->trang_thai)
                                <span class="badge bg-success">Hoạt động</span>
                            @elseif($item->user_token)
                                <span class="badge bg-warning text-dark">Chờ xác thực</span>
                            @else
                                <span class="badge bg-danger">Bị khóa</span>
                            @endif
                        </div>
                        <div class="text-muted small"><i class="fas fa-envelope me-1"></i> {{ $item->email }}</div>
                    </div>
                </div>

                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Thông tin cơ bản</h6>
                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <div class="text-muted small">Tên đăng nhập</div>
                        <div class="fw-medium">{{ $item->ten_dang_nhap }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">Số điện thoại</div>
                        <div class="fw-medium">{{ $item->so_dien_thoai ?? 'Chưa cập nhật' }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">Giới tính</div>
                        <div class="fw-medium">{{ $item->gioi_tinh ?? 'Chưa cập nhật' }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">Ngày sinh</div>
                        <div class="fw-medium">{{ $item->ngay_sinh ? \Carbon\Carbon::parse($item->ngay_sinh)->format('d/m/Y') : 'Chưa cập nhật' }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">Ngày tham gia</div>
                        <div class="fw-medium">{{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') : 'N/A' }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">Đăng nhập lần cuối</div>
                        <div class="fw-medium">{{ $item->last_login_at ? \Carbon\Carbon::parse($item->last_login_at)->format('d/m/Y H:i') : 'Chưa đăng nhập' }}</div>
                    </div>
                </div>

                @if(!$item->vaiTro || mb_strtolower($item->vaiTro->ten_vai_tro, 'UTF-8') === 'học viên')
                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Hồ sơ học tập</h6>
                @if($item->hoSoHocVien)
                <div class="row g-3 mb-4">
                    <div class="col-sm-4">
                        <div class="text-muted small">Trình độ hiện tại</div>
                        <div class="fw-medium text-primary">{{ $item->hoSoHocVien->trinh_do_hien_tai ?? 'Chưa có' }}</div>
                    </div>
                    <div class="col-sm-4">
                        <div class="text-muted small">Mục tiêu học tập</div>
                        <div class="fw-medium text-danger">{{ $item->hoSoHocVien->muc_tieu_hoc_tap ?? 'Chưa có' }}</div>
                    </div>
                    <div class="col-sm-4">
                        <div class="text-muted small">Thời gian dự kiến</div>
                        <div class="fw-medium">{{ $item->hoSoHocVien->thoi_gian_hoc_du_kien ?? 'Chưa có' }}</div>
                    </div>
                </div>
                @else
                <div class="alert alert-light text-muted small py-2 mb-4">
                    Học viên chưa cập nhật hồ sơ học tập.
                </div>
                @endif
                @endif

                @if($item->ghi_chu)
                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Ghi chú nội bộ</h6>
                <div class="bg-light p-3 rounded-2 text-muted small">
                    {{ $item->ghi_chu }}

                </div>
                @endif
              </div>
              <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#editModal_{{ $item->id }}">Chỉnh sửa</button>
              </div>
            </div>
        </div>
      </div>

      <div class="modal fade" id="editModal_{{ $item->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <form action="{{ route('admin.nguoidung.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
              <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Chỉnh sửa người dùng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body text-start pt-2">
                <div class="d-flex align-items-center gap-3 mb-4 p-3 bg-light rounded-3">
                    <img src="{{ $item->anh_dai_dien ? Storage::url($item->anh_dai_dien) : 'https://ui-avatars.com/api/?name='.urlencode($item->ho_ten).'&background=random' }}" class="rounded-circle border border-2 border-white shadow-sm" width="55" height="55" style="object-fit: cover;" alt="Avatar">
                    <div>
                        <h6 class="mb-0 fw-bold">{{ $item->ho_ten }}</h6>
                        <span class="badge {{ $item->vaiTro && $item->vaiTro->ten_vai_tro === 'Admin' ? 'bg-danger' : ($item->vaiTro && ($item->vaiTro->ten_vai_tro === 'Giảng viên' || $item->vaiTro->ten_vai_tro === 'Giáo viên') ? 'bg-primary' : 'bg-secondary') }} mt-1">
                            {{ $item->vaiTro->ten_vai_tro ?? 'Học viên' }}

                        </span>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-medium text-dark">Họ và tên <span class="text-danger">*</span></label>
                        <input type="text" class="form-control bg-light" name="ho_ten" value="{{ $item->ho_ten }}" autocomplete="off" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium text-dark">Ảnh đại diện</label>
                        <input type="file" class="form-control bg-light" name="anh_dai_dien" accept="image/*">
                        @if($item->anh_dai_dien)
                            <div class="mt-2">
                                <img src="{{ Storage::url($item->anh_dai_dien) }}" alt="Avatar" class="rounded-circle border" width="40" height="40" style="object-fit: cover;">
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium text-dark">Tên đăng nhập <span class="text-danger">*</span></label>
                        <input type="text" class="form-control bg-light" name="ten_dang_nhap" value="{{ $item->ten_dang_nhap }}" autocomplete="off" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-medium text-dark">Email liên hệ <span class="text-danger">*</span></label>
                        <input type="email" class="form-control bg-light" name="email" value="{{ $item->email }}" autocomplete="off" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium text-dark">Số điện thoại</label>
                        <input type="text" class="form-control bg-light" name="so_dien_thoai" value="{{ $item->so_dien_thoai }}" placeholder="Chưa cập nhật">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium text-dark">Ngày sinh</label>
                        <input type="date" class="form-control bg-light" name="ngay_sinh" value="{{ $item->ngay_sinh ? (is_string($item->ngay_sinh) ? $item->ngay_sinh : $item->ngay_sinh->format('Y-m-d')) : '' }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-medium text-dark">Mật khẩu mới</label>
                        <div class="input-group">
                            <input type="password" class="form-control bg-light" name="mat_khau" placeholder="Để trống nếu không đổi" autocomplete="new-password">
                        </div>
                        <small class="text-muted mt-1 d-block">Chỉ nhập khi muốn đổi mật khẩu mới.</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium text-dark">Giới tính</label>
                        <select class="form-select bg-light" name="gioi_tinh">
                            <option value="">-- Chưa cập nhật --</option>
                            <option value="Nam" {{ $item->gioi_tinh == 'Nam' ? 'selected' : '' }}>Nam</option>
                            <option value="Nữ" {{ $item->gioi_tinh == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                            <option value="Khác" {{ $item->gioi_tinh == 'Khác' ? 'selected' : '' }}>Khác</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-medium text-dark">Phân quyền vai trò <span class="text-danger">*</span></label>
                        <select class="form-select bg-light border-primary" name="id_vai_tro" required>
                            @foreach($vaiTros as $vt)
                                <option value="{{ $vt->id }}" data-role="{{ mb_strtolower($vt->ten_vai_tro, 'UTF-8') }}" {{ $item->id_vai_tro == $vt->id ? 'selected' : '' }}>
                                    {{ $vt->ten_vai_tro }}

                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 d-flex align-items-center">
                        <div class="form-check form-switch fs-5 mt-4">
                            <input type="hidden" name="trang_thai" value="0">
                            <input class="form-check-input" type="checkbox" role="switch" name="trang_thai" value="1" id="trang_thai_{{ $item->id }}" {{ $item->trang_thai ? 'checked' : '' }}>
                            <label class="form-check-label fs-6 ms-2" for="trang_thai_{{ $item->id }}">
                                Cho phép đăng nhập
                            </label>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label fw-medium text-dark">Ghi chú nội bộ</label>
                        <textarea class="form-control bg-light" name="ghi_chu" rows="2" placeholder="Ghi chú dành cho quản trị viên, học viên sẽ không thấy...">{{ $item->ghi_chu }}</textarea>
                    </div>
                </div>
              </div>
              <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Hủy bỏ</button>
                <button type="submit" class="btn btn-primary px-4" style="background: var(--admin-primary); border: none;">Lưu thay đổi</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      @endforeach

      <!-- Modal Thêm Người dùng -->
      <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <form action="{{ route('admin.nguoidung.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
              <div class="modal-header">
                <h5 class="modal-title fw-bold">Thêm Người dùng mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body text-start">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="ho_ten" placeholder="Nhập họ và tên..." required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tên đăng nhập <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="ten_dang_nhap" placeholder="VD: nguyenvanA" autocomplete="off" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Ảnh đại diện</label>
                        <input type="file" class="form-control" name="anh_dai_dien" accept="image/*">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" placeholder="example@gmail.com" autocomplete="off" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="mat_khau" placeholder="Tối thiểu 6 ký tự" autocomplete="new-password" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control" name="so_dien_thoai" placeholder="Nhập số điện thoại...">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Ngày sinh</label>
                        <input type="date" class="form-control" name="ngay_sinh">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Giới tính</label>
                        <select class="form-select" name="gioi_tinh">
                            <option value="">-- Chọn giới tính --</option>
                            <option value="Nam">Nam</option>
                            <option value="Nữ">Nữ</option>
                            <option value="Khác">Khác</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium text-dark">Phân quyền vai trò <span class="text-danger">*</span></label>
                        <select class="form-select bg-light border-primary" name="id_vai_tro" required>
                            @foreach($vaiTros as $vt)
                                <option value="{{ $vt->id }}" data-role="{{ mb_strtolower($vt->ten_vai_tro, 'UTF-8') }}">{{ $vt->ten_vai_tro }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <div class="form-check mb-2">
                            <input type="hidden" name="trang_thai" value="0">
                            <input class="form-check-input" type="checkbox" name="trang_thai" value="1" id="trang_thai_new" checked>
                            <label class="form-check-label" for="trang_thai_new">
                                Kích hoạt tài khoản
                            </label>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Ghi chú nội bộ</label>
                        <textarea class="form-control" name="ghi_chu" rows="2" placeholder="Ghi chú dành cho quản trị viên, học viên sẽ không thấy..."></textarea>
                    </div>
                </div>
              </div>
              <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" class="btn btn-primary" style="background: var(--admin-primary); border: none;">Thêm mới</button>
              </div>
            </form>
          </div>
        </div>
      </div>
@endsection





