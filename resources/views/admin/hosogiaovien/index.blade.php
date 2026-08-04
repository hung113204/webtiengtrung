@extends('admin.layouts.main')

@section('title', 'Hồ sơ Giảng viên — Hányǔ Admin')

@section('content')
      <div class="page-header animate-fade-in delay-1">
        <div>
          <h1 class="fs-4 fw-bold mb-1">Quản lý Hồ sơ Giảng viên</h1>
          <p class="text-muted mb-0 small">Cập nhật bằng cấp, chuyên môn và mức lương của giảng viên.</p>
        </div>
        <div class="d-flex gap-2">
          <style>
            .btn-excel {
                background-color: #fff;
                color: var(--admin-primary, #0d6efd);
                border: 1px solid var(--admin-primary, #0d6efd);
                transition: all 0.3s ease;
            }
            .btn-excel:hover {
                background-color: var(--admin-primary, #0d6efd);
                color: #fff;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2) !important;
            }
            .btn-excel:hover svg {
                stroke: #fff;
            }
          </style>
          <button class="btn btn-excel d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#importModal">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
            Nhập từ Excel
          </button>
          <button class="btn btn-primary d-flex align-items-center gap-2 shadow-sm" style="background: var(--admin-primary); border: none;" data-bs-toggle="modal" data-bs-target="#createModal">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Thêm Hồ sơ
          </button>
        </div>
      </div>

      <!-- Data Table Card -->
      <div class="table-card animate-fade-in delay-2">
        <div class="table-responsive">
          <table class="table table-hover mb-0 align-middle">
            <thead class="table-light text-muted small">
              <tr>
                <th class="fw-medium px-4 py-3">Giảng viên</th>
                <th class="fw-medium py-3">Chuyên môn</th>
                <th class="fw-medium py-3">Bằng cấp</th>
                <th class="fw-medium py-3">Khóa học phụ trách</th>
                <th class="fw-medium py-3">Mức lương (VNĐ)</th>
                <th class="fw-medium pe-4 py-3 text-end">Thao tác</th>
              </tr>
            </thead>
            <tbody>
              @forelse($hosos as $item)
              <tr>
                <td class="px-4 py-3">
                  <div class="d-flex align-items-center gap-3">
                    <img src="{{ $item->nguoiDung->anh_dai_dien ? Storage::url($item->nguoiDung->anh_dai_dien) : 'https://ui-avatars.com/api/?name='.urlencode($item->nguoiDung->ho_ten ?? 'User').'&background=random' }}" class="rounded-circle border border-2 border-white shadow-sm" width="42" height="42" style="object-fit: cover;" alt="Avatar">
                    <div>
                      <div class="fw-semibold text-dark">{{ $item->nguoiDung->ho_ten ?? 'N/A' }}</div>
                      <div class="small text-muted">{{ $item->nguoiDung->email ?? '' }}</div>
                    </div>
                  </div>
                </td>
                <td><span class="badge bg-light text-dark border">{{ $item->chuyen_mon ?? 'Chưa cập nhật' }}</span></td>
                <td>{{ $item->bang_cap ?? 'Chưa cập nhật' }}</td>
                <td>
                  <span class="badge bg-danger bg-opacity-10 text-danger border border-danger-subtle fw-semibold">
                    {{ $item->khoaHocs->count() }} khóa học
                  </span>
                </td>
                <td class="fw-semibold text-danger">{{ $item->muc_luong ? number_format($item->muc_luong, 0, ',', '.') . ' đ' : 'Thỏa thuận' }}</td>
                <td class="text-end pe-4">
                  <div class="d-flex justify-content-end gap-1">
                    <button class="icon-btn text-success" title="Phân công khóa học" data-bs-toggle="modal" data-bs-target="#assignModal_{{ $item->id }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    </button>
                    <button class="icon-btn text-primary" title="Chỉnh sửa" data-bs-toggle="modal" data-bs-target="#editModal_{{ $item->id }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </button>
                    <form action="{{ route('admin.hosogiaovien.destroy', $item->id) }}" method="POST" style="display:inline; margin:0; padding:0;">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="icon-btn text-danger btn-delete" title="Xóa" onclick="deleteDataAjax({{ $item->id }}, 'Hồ sơ của {{ addslashes($item->nguoiDung->ho_ten ?? '') }}', '{{ route('admin.hosogiaovien.destroy', $item->id) }}')" style="background:none; border:none;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6" /><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" /></svg>
                        </button>
                    </form>
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                  <td colspan="6" class="text-center py-4 text-muted">Chưa có hồ sơ giảng viên nào trong hệ thống.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <!-- Modal Sửa Hồ Sơ -->
      @foreach($hosos as $item)
      <div class="modal fade" id="editModal_{{ $item->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <form action="{{ route('admin.hosogiaovien.update', $item->id) }}" method="POST">
              @csrf
              @method('PUT')
              <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Chỉnh sửa Hồ sơ Giảng viên</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body text-start pt-2">
                <div class="d-flex align-items-center gap-3 mb-4 p-3 bg-light rounded-3">
                    <img src="{{ $item->nguoiDung->anh_dai_dien ? Storage::url($item->nguoiDung->anh_dai_dien) : 'https://ui-avatars.com/api/?name='.urlencode($item->nguoiDung->ho_ten ?? '').'&background=random' }}" class="rounded-circle border border-2 border-white shadow-sm" width="55" height="55" style="object-fit: cover;" alt="Avatar">
                    <div>
                        <h6 class="mb-0 fw-bold">{{ $item->nguoiDung->ho_ten ?? 'N/A' }}</h6>
                        <span class="badge bg-primary mt-1">Giảng viên</span>
                    </div>
                </div>

                <!-- Ẩn ID người dùng vì không cho phép đổi chủ sở hữu hồ sơ -->
                <input type="hidden" name="id_nguoi_dung" value="{{ $item->id_nguoi_dung }}">

                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-medium text-dark">Chuyên môn giảng dạy</label>
                        <input type="text" class="form-control bg-light" name="chuyen_mon" value="{{ $item->chuyen_mon }}" placeholder="VD: Giao tiếp, Tiếng Trung thương mại...">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium text-dark">Kinh nghiệm</label>
                        <input type="text" class="form-control bg-light" name="kinh_nghiem" value="{{ $item->kinh_nghiem }}" placeholder="VD: 5 năm giảng dạy...">
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-medium text-dark">Bằng cấp cao nhất</label>
                        <input type="text" class="form-control bg-light" name="bang_cap" value="{{ $item->bang_cap }}" placeholder="VD: HSK 6, Thạc sĩ...">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium text-dark">Mức lương cơ bản (VNĐ)</label>
                        <input type="number" class="form-control bg-light" name="muc_luong" value="{{ $item->muc_luong }}" placeholder="VD: 15000000">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-medium text-dark">Giới thiệu bản thân</label>
                        <textarea class="form-control bg-light" name="gioi_thieu" rows="4" placeholder="Viết một đoạn giới thiệu ngắn về giáo viên này...">{{ $item->gioi_thieu }}</textarea>
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

      <!-- Modal Thêm Hồ Sơ -->
      <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <form action="{{ route('admin.hosogiaovien.store') }}" method="POST">
              @csrf
              <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Tạo Hồ sơ Giảng viên</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body text-start pt-3">
                <div class="row g-4">
                    <div class="col-md-12">
                        <label class="form-label fw-medium text-dark">Chọn Giảng viên (Chưa có hồ sơ) <span class="text-danger">*</span></label>
                        <select class="form-select bg-light border-primary" name="id_nguoi_dung" required>
                            <option value="">-- Click để chọn giảng viên --</option>
                            @foreach($giaoViens as $gv)
                                <option value="{{ $gv->id }}">{{ $gv->ho_ten }} ({{ $gv->email }})</option>
                            @endforeach
                        </select>
                        @if($giaoViens->isEmpty())
                            <small class="text-danger mt-1 d-block">Không có giảng viên nào cần tạo hồ sơ (hoặc tất cả đã có hồ sơ).</small>
                        @endif
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-medium text-dark">Chuyên môn giảng dạy</label>
                        <input type="text" class="form-control bg-light" name="chuyen_mon" placeholder="VD: Giao tiếp, Tiếng Trung thương mại...">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium text-dark">Kinh nghiệm</label>
                        <input type="text" class="form-control bg-light" name="kinh_nghiem" placeholder="VD: 5 năm giảng dạy...">
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-medium text-dark">Bằng cấp cao nhất</label>
                        <input type="text" class="form-control bg-light" name="bang_cap" placeholder="VD: HSK 6, Thạc sĩ...">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium text-dark">Mức lương cơ bản (VNĐ)</label>
                        <input type="number" class="form-control bg-light" name="muc_luong" placeholder="VD: 15000000">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-medium text-dark">Giới thiệu bản thân</label>
                        <textarea class="form-control bg-light" name="gioi_thieu" rows="4" placeholder="Viết một đoạn giới thiệu ngắn về giáo viên này..."></textarea>
                    </div>
                </div>
              </div>
              <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Hủy bỏ</button>
                <button type="submit" class="btn btn-primary px-4" style="background: var(--admin-primary); border: none;" {{ $giaoViens->isEmpty() ? 'disabled' : '' }}>Thêm hồ sơ</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Modal Phân công giảng dạy khóa học -->
      @foreach($hosos as $item)
      <div class="modal fade" id="assignModal_{{ $item->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <form action="{{ route('admin.hosogiaovien.assign', $item->id) }}" method="POST">
              @csrf
              <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Phân công khóa học giảng dạy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body text-start pt-2">
                <div class="d-flex align-items-center gap-3 mb-4 p-3 bg-light rounded-3">
                    <img src="{{ $item->nguoiDung->anh_dai_dien ? Storage::url($item->nguoiDung->anh_dai_dien) : 'https://ui-avatars.com/api/?name='.urlencode($item->nguoiDung->ho_ten ?? '').'&background=random' }}" class="rounded-circle border border-2 border-white shadow-sm" width="55" height="55" style="object-fit: cover;" alt="Avatar">
                    <div>
                        <h6 class="mb-0 fw-bold">{{ $item->nguoiDung->ho_ten ?? 'N/A' }}</h6>
                        <span class="badge bg-success mt-1">Giảng viên</span>
                    </div>
                </div>

                <p class="text-muted small mb-3">Tích chọn các khóa học mà giảng viên này sẽ phụ trách giảng dạy và chọn vai trò tương ứng:</p>

                <div class="table-responsive border rounded-3 bg-white mb-2">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light text-muted small">
                            <tr>
                                <th class="fw-medium px-3 py-2.5" style="width: 50px;">Chọn</th>
                                <th class="fw-medium py-2.5">Khóa học</th>
                                <th class="fw-medium py-2.5">Vai trò giảng dạy</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($khoaHocs as $course)
                                @php
                                    $isAssigned = $item->khoaHocs->contains($course->id);
                                    $assignedPivot = $isAssigned ? $item->khoaHocs->where('id', $course->id)->first()->pivot : null;
                                    $role = $assignedPivot ? $assignedPivot->vai_tro_giang_day : 'Giảng viên chính';
                                @endphp
                                <tr>
                                    <td class="px-3 py-2">
                                        <input class="form-check-input" type="checkbox" name="khoa_hoc_ids[]" value="{{ $course->id }}" id="course_{{ $item->id }}_{{ $course->id }}" {{ $isAssigned ? 'checked' : '' }}>
                                    </td>
                                    <td>
                                        <label class="form-check-label fw-semibold text-dark d-block" for="course_{{ $item->id }}_{{ $course->id }}">
                                            {{ $course->ten_khoa_hoc }}
                                        </label>
                                        <span class="small text-muted">{{ $course->danhMucKhoaHoc->ten_danh_muc ?? '' }}</span>
                                    </td>
                                    <td>
                                        <select name="vai_tro_giang_day_{{ $course->id }}" class="form-select form-select-sm bg-light border-0 py-1.5 px-3 rounded-2 text-dark" style="max-width: 200px;">
                                            <option value="Giảng viên chính" {{ $role === 'Giảng viên chính' ? 'selected' : '' }}>Giảng viên chính</option>
                                            <option value="Trợ giảng" {{ $role === 'Trợ giảng' ? 'selected' : '' }}>Trợ giảng</option>
                                            <option value="Cố vấn chuyên môn" {{ $role === 'Cố vấn chuyên môn' ? 'selected' : '' }}>Cố vấn chuyên môn</option>
                                        </select>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted small">Không tìm thấy khóa học hoạt động nào trong hệ thống.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
              </div>
              <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Hủy bỏ</button>
                <button type="submit" class="btn btn-primary px-4" style="background: var(--admin-primary); border: none;">Cập nhật phân công</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      @endforeach
      <!-- Modal Import Excel -->
      <div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <form action="{{ route('admin.hosogiaovien.import') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Nhập Hồ sơ Giảng viên từ Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body text-start pt-3">
                <div class="alert alert-info bg-info bg-opacity-10 text-info border-info border-opacity-25 small mb-4">
                  <div class="fw-semibold mb-1 d-flex align-items-center gap-2">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                    Hướng dẫn:
                  </div>
                  <ul class="mb-0 ps-3">
                    <li>Hệ thống sẽ dựa vào <strong>Email</strong> để kiểm tra tài khoản đã tồn tại hay chưa.</li>
                    <li>Nếu tài khoản chưa tồn tại, hệ thống sẽ tự động tạo tài khoản mới. Mật khẩu mặc định sẽ là ngày sinh (định dạng <strong>DDMMYY</strong>, ví dụ: <strong>110304</strong>). Nếu cột ngày sinh trống, mật khẩu mặc định là <strong>12345678</strong>.</li>
                    <li>Tài khoản sẽ được tự động gán quyền <strong>Giảng viên</strong>.</li>
                    <li>Bắt buộc phải có cột: <strong>ho_ten</strong>, <strong>email</strong>. Các cột khác: <strong>ngay_sinh, so_dien_thoai, so_nam_kinh_nghiem, chuyen_mon, tieu_su</strong>.</li>
                  </ul>
                </div>
                
                <div class="mb-3">
                  <label class="form-label fw-medium text-dark">Chọn file Excel (.xlsx, .xls) <span class="text-danger">*</span></label>
                  <input type="file" name="file" class="form-control bg-light" accept=".xlsx, .xls, .csv" required>
                </div>
              </div>
              <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Hủy bỏ</button>
                <button type="submit" class="btn btn-primary px-4" style="background: var(--admin-primary); border: none;">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                  Bắt đầu Import
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
@endsection
