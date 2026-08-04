@extends('admin.layouts.main')

@section('title', 'Phân công Giảng dạy — Hányǔ Admin')

@section('content')
      <div class="page-header animate-fade-in delay-1">
        <div>
          <h1 class="fs-4 fw-bold mb-1">Phân công Giảng dạy</h1>
          <p class="text-muted mb-0 small">Quản lý và điều phối giáo viên phụ trách các khóa học trong hệ thống.</p>
        </div>
        <button class="btn btn-primary d-flex align-items-center gap-2 shadow-sm" style="background: var(--admin-primary); border: none;" data-bs-toggle="modal" data-bs-target="#createModal">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
          Phân công mới
        </button>
      </div>

      @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show animate-fade-in delay-2" role="alert">
            <ul class="mb-0 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show animate-fade-in delay-2" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      @endif

      <!-- Data Table Card (with inline filter header — chuonghoc style) -->
      <div class="table-card animate-fade-in delay-2">
        <div class="table-header d-flex flex-wrap gap-3">
          <form action="{{ route('admin.phancong.index') }}" method="GET" class="d-flex flex-wrap gap-3 w-100">
            <div class="input-group" style="max-width: 340px;">
              <span class="input-group-text bg-white border-end-0 text-muted">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
              </span>
              <input type="text" class="form-control border-start-0 ps-0" name="keyword" value="{{ request('keyword') }}" placeholder="Tìm theo tên giáo viên hoặc khóa học...">
            </div>

            <select name="role" class="form-select" style="max-width: 220px;" onchange="this.form.submit()">
              <option value="">Tất cả vai trò</option>
              <option value="Giảng viên chính" {{ request('role') == 'Giảng viên chính' ? 'selected' : '' }}>Giảng viên chính</option>
              <option value="Trợ giảng" {{ request('role') == 'Trợ giảng' ? 'selected' : '' }}>Trợ giảng</option>
              <option value="Cố vấn chuyên môn" {{ request('role') == 'Cố vấn chuyên môn' ? 'selected' : '' }}>Cố vấn chuyên môn</option>
            </select>

            <button type="submit" class="btn btn-dark btn-sm px-4">Tìm kiếm</button>
            @if(request()->anyFilled(['keyword', 'role']))
              <a href="{{ route('admin.phancong.index') }}" class="btn btn-outline-secondary btn-sm px-3">Làm mới</a>
            @endif
          </form>
        </div>

        <div class="table-responsive">
          <table class="table table-hover mb-0 align-middle">
            <thead class="table-light text-muted small">
              <tr>
                <th class="fw-medium px-4 py-3">Giảng viên</th>
                <th class="fw-medium py-3">Khóa học đảm nhận</th>
                <th class="fw-medium py-3">Vai trò giảng dạy</th>
                <th class="fw-medium py-3">Ngày phân công</th>
                <th class="fw-medium pe-4 py-3 text-end">Thao tác</th>
              </tr>
            </thead>
            <tbody>
              @forelse($phanCongs as $item)
              <tr>
                <td class="px-4 py-3">
                  <div class="d-flex align-items-center gap-3">
                    <img src="{{ $item->giaoVien->nguoiDung->anh_dai_dien ? Storage::url($item->giaoVien->nguoiDung->anh_dai_dien) : 'https://ui-avatars.com/api/?name='.urlencode($item->giaoVien->nguoiDung->ho_ten ?? 'User').'&background=random' }}" class="rounded-circle border border-2 border-white shadow-sm" width="42" height="42" style="object-fit: cover;" alt="Avatar">
                    <div>
                      <div class="fw-semibold text-dark">{{ $item->giaoVien->nguoiDung->ho_ten ?? 'N/A' }}</div>
                      <div class="small text-muted">{{ $item->giaoVien->nguoiDung->email ?? '' }}</div>
                    </div>
                  </div>
                </td>
                <td>
                  <div class="fw-semibold text-dark">{{ $item->khoaHoc->ten_khoa_hoc ?? 'N/A' }}</div>
                  <div class="small text-muted">{{ $item->khoaHoc->danhMucKhoaHoc->ten_danh_muc ?? '' }}</div>
                </td>
                <td>
                  @if($item->vai_tro_giang_day == 'Giảng viên chính')
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle fw-semibold px-2.5 py-1">Giảng viên chính</span>
                  @elseif($item->vai_tro_giang_day == 'Trợ giảng')
                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning-subtle fw-semibold px-2.5 py-1">Trợ giảng</span>
                  @else
                    <span class="badge bg-info bg-opacity-10 text-info border border-info-subtle fw-semibold px-2.5 py-1">{{ $item->vai_tro_giang_day }}</span>
                  @endif
                </td>
                <td class="text-muted small">
                  {{ $item->ngay_phan_cong ? \Carbon\Carbon::parse($item->ngay_phan_cong)->format('d/m/Y H:i') : '' }}
                </td>
                <td class="text-end pe-4">
                  <div class="d-flex justify-content-end gap-1">
                    <button class="icon-btn text-primary" title="Chỉnh sửa vai trò" data-bs-toggle="modal" data-bs-target="#editModal_{{ $item->id }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </button>
                    <form action="{{ route('admin.phancong.destroy', $item->id) }}" method="POST" style="display:inline; margin:0; padding:0;">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="icon-btn text-danger btn-delete" title="Hủy phân công" onclick="deleteDataAjax({{ $item->id }}, 'Phân công của {{ addslashes($item->giaoVien->nguoiDung->ho_ten ?? '') }} tại khóa {{ addslashes($item->khoaHoc->ten_khoa_hoc ?? '') }}', '{{ route('admin.phancong.destroy', $item->id) }}')" style="background:none; border:none;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6" /><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" /></svg>
                        </button>
                    </form>
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                  <td colspan="5" class="text-center py-4 text-muted">Không tìm thấy bản ghi phân công nào.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        
        @if($phanCongs->hasPages())
          <div class="card-footer bg-white border-top-0 py-3 d-flex justify-content-center">
             {{ $phanCongs->withQueryString()->links('pagination::bootstrap-5') }}
          </div>
        @endif
      </div>

      <!-- Modal Thêm Phân Công -->
      <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.phancong.store') }}" method="POST">
              @csrf
              <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Phân công Giảng dạy mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body text-start pt-3">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label fw-medium text-dark">Chọn Giảng viên <span class="text-danger">*</span></label>
                        <select class="form-select bg-light border-0 py-2" name="id_giao_vien" required>
                            <option value="">-- Click để chọn giảng viên --</option>
                            @foreach($giaoViens as $gv)
                                <option value="{{ $gv->id }}">{{ $gv->nguoiDung->ho_ten ?? 'N/A' }} ({{ $gv->nguoiDung->email ?? '' }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-medium text-dark">Chọn Khóa học <span class="text-danger">*</span></label>
                        <select class="form-select bg-light border-0 py-2" name="id_khoa_hoc" required>
                            <option value="">-- Click để chọn khóa học --</option>
                            @foreach($khoaHocs as $kh)
                                <option value="{{ $kh->id }}">{{ $kh->ten_khoa_hoc }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-12">
                        <label class="form-label fw-medium text-dark">Vai trò giảng dạy <span class="text-danger">*</span></label>
                        <select class="form-select bg-light border-0 py-2" name="vai_tro_giang_day" required>
                            <option value="Giảng viên chính">Giảng viên chính</option>
                            <option value="Trợ giảng">Trợ giảng</option>
                            <option value="Cố vấn chuyên môn">Cố vấn chuyên môn</option>
                        </select>
                    </div>
                </div>
              </div>
              <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Hủy bỏ</button>
                <button type="submit" class="btn btn-primary px-4" style="background: var(--admin-primary); border: none;">Xác nhận phân công</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Modal Sửa Vai Trò -->
      @foreach($phanCongs as $item)
      <div class="modal fade" id="editModal_{{ $item->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.phancong.update', $item->id) }}" method="POST">
              @csrf
              @method('PUT')
              <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Chỉnh sửa vai trò giảng dạy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body text-start pt-3">
                <div class="d-flex align-items-center gap-3 mb-4 p-3 bg-light rounded-3">
                    <img src="{{ $item->giaoVien->nguoiDung->anh_dai_dien ? Storage::url($item->giaoVien->nguoiDung->anh_dai_dien) : 'https://ui-avatars.com/api/?name='.urlencode($item->giaoVien->nguoiDung->ho_ten ?? 'User').'&background=random' }}" class="rounded-circle border border-2 border-white shadow-sm" width="50" height="50" style="object-fit: cover;" alt="Avatar">
                    <div>
                        <h6 class="mb-0 fw-bold">{{ $item->giaoVien->nguoiDung->ho_ten ?? 'N/A' }}</h6>
                        <span class="small text-muted">{{ $item->khoaHoc->ten_khoa_hoc ?? 'N/A' }}</span>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label fw-medium text-dark">Vai trò giảng dạy <span class="text-danger">*</span></label>
                        <select class="form-select bg-light border-0 py-2" name="vai_tro_giang_day" required>
                            <option value="Giảng viên chính" {{ $item->vai_tro_giang_day == 'Giảng viên chính' ? 'selected' : '' }}>Giảng viên chính</option>
                            <option value="Trợ giảng" {{ $item->vai_tro_giang_day == 'Trợ giảng' ? 'selected' : '' }}>Trợ giảng</option>
                            <option value="Cố vấn chuyên môn" {{ $item->vai_tro_giang_day == 'Cố vấn chuyên môn' ? 'selected' : '' }}>Cố vấn chuyên môn</option>
                        </select>
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
@endsection
