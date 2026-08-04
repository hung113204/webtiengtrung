@extends('admin.layouts.main')

@section('title', 'Quản lý Vai trò — Hányǔ Admin')

@section('content')
      <div class="page-header animate-fade-in delay-1">
        <div>
          <h1 class="fs-4 fw-bold mb-1">Quản lý Phân quyền / Vai trò</h1>
          <p class="text-muted mb-0 small">Thiết lập các nhóm vai trò và quyền hạn trong hệ thống.</p>
        </div>
        <button class="btn btn-primary d-flex align-items-center gap-2 shadow-sm" style="background: var(--admin-primary); border: none;" data-bs-toggle="modal" data-bs-target="#createModal">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
          Thêm Vai trò mới
        </button>
      </div>

      <!-- Data Table Card -->
      <div class="table-card animate-fade-in delay-2">
        <div class="table-responsive">
          <table class="table table-hover mb-0 align-middle">
            <thead class="table-light text-muted small">
              <tr>
                <th class="fw-medium px-4 py-3">Tên Vai trò</th>
                <th class="fw-medium py-3 text-center">Level</th>
                <th class="fw-medium py-3">Mô tả</th>
                <th class="fw-medium py-3">Ngày tạo</th>
                <th class="fw-medium pe-4 py-3 text-end">Thao tác</th>
              </tr>
            </thead>
            <tbody>
              @forelse($vaitros as $item)
              <tr>
                <td class="px-4 py-3">
                    <div class="fw-semibold text-dark">{{ $item->ten_vai_tro }} 
                        @if($item->is_default)
                            <span class="badge bg-success ms-1" style="font-size: 0.7rem;">Mặc định</span>
                        @endif
                    </div>
                    <div class="small text-muted">{{ $item->slug }}</div>
                </td>
                <td class="text-center fw-medium">{{ $item->level }}</td>
                <td class="text-muted">{{ $item->mo_ta ?? 'Không có mô tả' }}</td>
                <td class="small text-muted">{{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') : 'N/A' }}</td>
                <td class="text-end pe-4">
                  <div class="d-flex justify-content-end gap-1">
                    <button class="icon-btn text-primary" title="Chỉnh sửa" data-bs-toggle="modal" data-bs-target="#editModal_{{ $item->id }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </button>
                    <form action="{{ route('admin.vaitro.destroy', $item->id) }}" method="POST" style="display:inline; margin:0; padding:0;">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="icon-btn text-danger btn-delete" title="Xóa" onclick="deleteDataAjax({{ $item->id }}, '{{ addslashes($item->ten_vai_tro) }}', '{{ route('admin.vaitro.destroy', $item->id) }}')" style="background:none; border:none;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6" /><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" /></svg>
                        </button>
                    </form>
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                  <td colspan="4" class="text-center py-4 text-muted">Chưa có vai trò nào trong hệ thống.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <!-- Modal Sửa Vai trò -->
      @foreach($vaitros as $item)
      <div class="modal fade" id="editModal_{{ $item->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <form action="{{ route('admin.vaitro.update', $item->id) }}" method="POST">
              @csrf
              @method('PUT')
              <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Chỉnh sửa Vai trò</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body text-start pt-3">
                <div class="mb-3">
                    <label class="form-label fw-medium text-dark">Tên vai trò <span class="text-danger">*</span></label>
                    <input type="text" class="form-control bg-light" name="ten_vai_tro" value="{{ $item->ten_vai_tro }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium text-dark">Mã Slug</label>
                    <input type="text" class="form-control bg-light" name="slug" value="{{ $item->slug }}" placeholder="Để trống sẽ tự động tạo">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium text-dark">Quyền lực (Level)</label>
                    <input type="number" class="form-control bg-light" name="level" value="{{ $item->level }}" placeholder="Ví dụ: 10, 50, 100...">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium text-dark">Mô tả chi tiết</label>
                    <textarea class="form-control bg-light" name="mo_ta" rows="2">{{ $item->mo_ta }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium text-dark">Phân quyền thao tác</label>
                    <div class="border rounded p-3 bg-white" style="max-height: 250px; overflow-y: auto;">
                        @foreach($quyens as $nhom => $danhSachQuyen)
                            <div class="mb-3 last:mb-0">
                                <h6 class="fw-bold text-primary mb-2 border-bottom pb-1" style="font-size: 0.85rem;">{{ $nhom ?: 'Quyền khác' }}</h6>
                                <div class="row g-2">
                                    @foreach($danhSachQuyen as $quyen)
                                        <div class="col-md-6">
                                            <div class="form-check custom-checkbox">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $quyen->id }}" id="perm_{{ $item->id }}_{{ $quyen->id }}"
                                                    {{ $item->quyens->contains('id', $quyen->id) ? 'checked' : '' }}>
                                                <label class="form-check-label text-dark small" for="perm_{{ $item->id }}_{{ $quyen->id }}">
                                                    {{ $quyen->ten_quyen }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="is_default" value="1" id="isDefaultEdit{{ $item->id }}" {{ $item->is_default ? 'checked' : '' }}>
                    <label class="form-check-label fw-medium" for="isDefaultEdit{{ $item->id }}">Đặt làm Vai trò mặc định cho người dùng mới</label>
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

      <!-- Modal Thêm Vai trò -->
      <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <form action="{{ route('admin.vaitro.store') }}" method="POST">
              @csrf
              <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Thêm Vai trò mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body text-start pt-3">
                <div class="mb-3">
                    <label class="form-label fw-medium text-dark">Tên vai trò <span class="text-danger">*</span></label>
                    <input type="text" class="form-control bg-light" name="ten_vai_tro" placeholder="Ví dụ: Super Admin" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium text-dark">Mã Slug</label>
                    <input type="text" class="form-control bg-light" name="slug" placeholder="Để trống sẽ tự động tạo">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium text-dark">Quyền lực (Level)</label>
                    <input type="number" class="form-control bg-light" name="level" value="0" placeholder="Ví dụ: 10, 50, 100...">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium text-dark">Mô tả chi tiết</label>
                    <textarea class="form-control bg-light" name="mo_ta" rows="2" placeholder="Nhập mô tả quyền hạn..."></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium text-dark">Phân quyền thao tác</label>
                    <div class="border rounded p-3 bg-white" style="max-height: 250px; overflow-y: auto;">
                        @foreach($quyens as $nhom => $danhSachQuyen)
                            <div class="mb-3 last:mb-0">
                                <h6 class="fw-bold text-primary mb-2 border-bottom pb-1" style="font-size: 0.85rem;">{{ $nhom ?: 'Quyền khác' }}</h6>
                                <div class="row g-2">
                                    @foreach($danhSachQuyen as $quyen)
                                        <div class="col-md-6">
                                            <div class="form-check custom-checkbox">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $quyen->id }}" id="perm_create_{{ $quyen->id }}">
                                                <label class="form-check-label text-dark small" for="perm_create_{{ $quyen->id }}">
                                                    {{ $quyen->ten_quyen }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="is_default" value="1" id="isDefaultCreate">
                    <label class="form-check-label fw-medium" for="isDefaultCreate">Đặt làm Vai trò mặc định cho người dùng mới</label>
                </div>
              </div>
              <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Hủy bỏ</button>
                <button type="submit" class="btn btn-primary px-4" style="background: var(--admin-primary); border: none;">Thêm mới</button>
              </div>
            </form>
          </div>
        </div>
      </div>
@endsection
