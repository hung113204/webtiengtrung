@extends('admin.layouts.main')

@section('content')
      <!-- Thông báo -->
      @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
              {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
      @endif
      @if($errors->any())
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <ul class="mb-0">
                  @foreach($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
      @endif

      <div class="page-header animate-fade-in delay-1">
        <div>
          <h1 class="fs-4 fw-bold mb-1">Quản lý danh mục</h1>
          <p class="text-muted mb-0 small">Phân loại và tổ chức các nhóm khóa học theo cấp cha-con.</p>
        </div>
        <button class="btn btn-primary d-flex align-items-center gap-2 shadow-sm"
                data-bs-toggle="modal" data-bs-target="#createModal"
                style="background: var(--admin-primary); border: none;">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
          Thêm danh mục mới
        </button>
      </div>

      <!-- Category Tree Table -->
      <div class="table-card animate-fade-in delay-2">
        <div class="table-header d-flex flex-wrap gap-3">
          <div class="input-group" style="max-width: 300px;">
            <span class="input-group-text bg-white border-end-0 text-muted">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </span>
            <input type="text" id="searchInput" class="form-control border-start-0 ps-0" placeholder="Tìm tên danh mục...">
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-hover mb-0 align-middle">
            <thead class="table-light text-muted small">
              <tr>
                <th class="fw-medium px-4 py-3">Tên danh mục</th>
                <th class="fw-medium py-3">Danh mục cha</th>
                <th class="fw-medium py-3">Slug</th>
                <th class="fw-medium py-3">Số khóa học</th>
                <th class="fw-medium py-3">Trạng thái</th>
                <th class="fw-medium pe-4 py-3 text-end">Thao tác</th>
              </tr>
            </thead>
            <tbody>
              @forelse($danhMucs as $item)
                {{-- Hàng danh mục GỐC --}}
                <tr class="table-row-root fw-semibold" data-name="{{ strtolower($item->ten_danh_muc) }}">
                  <td class="px-4 py-3">
                    <div class="d-flex align-items-center gap-3">
                      <div style="width:40px;height:40px;background:#fee2e2;color:var(--admin-primary);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                      </div>
                      <div>
                        <div class="fw-semibold text-dark">{{ $item->ten_danh_muc }}</div>
                        <div class="small text-muted">{{ $item->mo_ta ?? 'Không có mô tả' }}</div>
                      </div>
                    </div>
                  </td>
                  <td><span class="badge bg-light text-secondary border">— Gốc —</span></td>
                  <td class="small text-muted">{{ $item->slug }}</td>
                  <td><span class="badge bg-light text-dark border">{{ $item->khoa_hocs_count ?? 0 }} khóa học</span></td>
                  <td>
                    @if($item->trang_thai)
                      <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle">Hiển thị</span>
                    @else
                      <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle">Đang ẩn</span>
                    @endif
                  </td>
                  <td class="text-end pe-4">
                    <div class="d-flex justify-content-end gap-1">
                      <button class="icon-btn" title="Chỉnh sửa" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                      </button>
                      <form action="{{ route('admin.danhmuc.destroy', $item->id) }}" method="POST" style="display:inline;margin:0;padding:0;">
                        @csrf @method('DELETE')
                        <button type="button" class="icon-btn text-danger btn-delete" title="Xóa"
                          onclick="deleteDataAjax({{ $item->id }}, '{{ addslashes($item->ten_danh_muc) }}', '{{ route('admin.danhmuc.destroy', $item->id) }}')">
                          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>

                {{-- Hàng danh mục CON (thụt lề) --}}
                @foreach($item->children as $child)
                <tr class="table-row-child" style="background:#fafafa;" data-name="{{ strtolower($child->ten_danh_muc) }}">
                  <td class="px-4 py-2">
                    <div class="d-flex align-items-center gap-2" style="padding-left:2rem;">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2" style="flex-shrink:0;"><polyline points="9 18 15 12 9 6"/></svg>
                      <div style="width:32px;height:32px;background:#f3f4f6;border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2"><path d="M13 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
                      </div>
                      <div>
                        <div class="fw-medium text-dark small">{{ $child->ten_danh_muc }}</div>
                        <div class="small text-muted" style="font-size:.75rem;">{{ $child->mo_ta ?? '' }}</div>
                      </div>
                    </div>
                  </td>
                  <td><span class="badge bg-warning bg-opacity-10 text-warning border border-warning-subtle small">{{ $item->ten_danh_muc }}</span></td>
                  <td class="small text-muted">{{ $child->slug }}</td>
                  <td><span class="badge bg-light text-dark border small">{{ $child->khoa_hocs_count ?? 0 }} khóa học</span></td>
                  <td>
                    @if($child->trang_thai)
                      <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle small">Hiển thị</span>
                    @else
                      <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle small">Đang ẩn</span>
                    @endif
                  </td>
                  <td class="text-end pe-4">
                    <div class="d-flex justify-content-end gap-1">
                      <button class="icon-btn" title="Chỉnh sửa" data-bs-toggle="modal" data-bs-target="#editModal{{ $child->id }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                      </button>
                      <form action="{{ route('admin.danhmuc.destroy', $child->id) }}" method="POST" style="display:inline;margin:0;padding:0;">
                        @csrf @method('DELETE')
                        <button type="button" class="icon-btn text-danger btn-delete" title="Xóa"
                          onclick="deleteDataAjax({{ $child->id }}, '{{ addslashes($child->ten_danh_muc) }}', '{{ route('admin.danhmuc.destroy', $child->id) }}')">
                          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
                @endforeach

              @empty
              <tr>
                <td colspan="6" class="text-center py-4 text-muted">Chưa có danh mục nào được tạo.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <!-- ===== MODALS EDIT (cho từng item gốc và con) ===== -->
      @foreach($danhMucs as $item)
        {{-- Edit modal cho cha --}}
        <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog">
            <form action="{{ route('admin.danhmuc.update', $item->id) }}" method="POST">
              @csrf @method('PUT')
              <div class="modal-content text-start">
                <div class="modal-header">
                  <h5 class="modal-title">Cập nhật Danh mục</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  <div class="mb-3">
                    <label class="form-label">Danh mục cha</label>
                    <select class="form-select" name="parent_id">
                      <option value="">— Không có (Danh mục gốc) —</option>
                      @foreach($danhMucRoots as $root)
                        @if($root->id !== $item->id)
                          <option value="{{ $root->id }}" {{ $item->parent_id == $root->id ? 'selected' : '' }}>
                            {{ $root->ten_danh_muc }}
                          </option>
                        @endif
                      @endforeach
                    </select>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="ten_danh_muc" value="{{ $item->ten_danh_muc }}" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Đường dẫn (Slug) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="slug" value="{{ $item->slug }}" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Mô tả</label>
                    <textarea class="form-control" name="mo_ta" rows="2">{{ $item->mo_ta }}</textarea>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Thứ tự hiển thị</label>
                    <input type="number" class="form-control" name="thu_tu" value="{{ $item->thu_tu }}">
                  </div>
                  <div class="mb-3 form-check">
                    <input type="hidden" name="trang_thai" value="0">
                    <input type="checkbox" class="form-check-input" id="tt_edit_{{ $item->id }}" name="trang_thai" value="1" {{ $item->trang_thai ? 'checked' : '' }}>
                    <label class="form-check-label" for="tt_edit_{{ $item->id }}">Hiển thị trên trang chủ</label>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                  <button type="submit" class="btn btn-primary" style="background:var(--admin-primary);border:none;">Lưu thay đổi</button>
                </div>
              </div>
            </form>
          </div>
        </div>

        {{-- Edit modal cho từng con --}}
        @foreach($item->children as $child)
        <div class="modal fade" id="editModal{{ $child->id }}" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog">
            <form action="{{ route('admin.danhmuc.update', $child->id) }}" method="POST">
              @csrf @method('PUT')
              <div class="modal-content text-start">
                <div class="modal-header">
                  <h5 class="modal-title">Cập nhật Danh mục</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  <div class="mb-3">
                    <label class="form-label">Danh mục cha</label>
                    <select class="form-select" name="parent_id">
                      <option value="">— Không có (Danh mục gốc) —</option>
                      @foreach($danhMucRoots as $root)
                        @if($root->id !== $child->id)
                          <option value="{{ $root->id }}" {{ $child->parent_id == $root->id ? 'selected' : '' }}>
                            {{ $root->ten_danh_muc }}
                          </option>
                        @endif
                      @endforeach
                    </select>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="ten_danh_muc" value="{{ $child->ten_danh_muc }}" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Đường dẫn (Slug) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="slug" value="{{ $child->slug }}" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Mô tả</label>
                    <textarea class="form-control" name="mo_ta" rows="2">{{ $child->mo_ta }}</textarea>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Thứ tự hiển thị</label>
                    <input type="number" class="form-control" name="thu_tu" value="{{ $child->thu_tu }}">
                  </div>
                  <div class="mb-3 form-check">
                    <input type="hidden" name="trang_thai" value="0">
                    <input type="checkbox" class="form-check-input" id="tt_edit_{{ $child->id }}" name="trang_thai" value="1" {{ $child->trang_thai ? 'checked' : '' }}>
                    <label class="form-check-label" for="tt_edit_{{ $child->id }}">Hiển thị trên trang chủ</label>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                  <button type="submit" class="btn btn-primary" style="background:var(--admin-primary);border:none;">Lưu thay đổi</button>
                </div>
              </div>
            </form>
          </div>
        </div>
        @endforeach
      @endforeach

      <!-- ===== MODAL CREATE ===== -->
      <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
          <form action="{{ route('admin.danhmuc.store') }}" method="POST">
            @csrf
            <div class="modal-content text-start">
              <div class="modal-header">
                <h5 class="modal-title">Thêm Danh mục mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <div class="mb-3">
                  <label class="form-label">Danh mục cha</label>
                  <select class="form-select" name="parent_id">
                    <option value="">— Không có (Danh mục gốc) —</option>
                    @foreach($danhMucRoots as $root)
                      <option value="{{ $root->id }}">{{ $root->ten_danh_muc }}</option>
                    @endforeach
                  </select>
                  <div class="form-text">Chọn nếu đây là danh mục con.</div>
                </div>
                <div class="mb-3">
                  <label class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="ten_danh_muc" placeholder="VD: Luyện thi HSK..." required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Đường dẫn (Slug) <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="slugInput" name="slug" placeholder="VD: luyen-thi-hsk" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Mô tả</label>
                  <textarea class="form-control" name="mo_ta" rows="2" placeholder="Mô tả ngắn..."></textarea>
                </div>
                <div class="mb-3">
                  <label class="form-label">Thứ tự hiển thị</label>
                  <input type="number" class="form-control" name="thu_tu" value="0">
                </div>
                <div class="mb-3 form-check">
                  <input type="hidden" name="trang_thai" value="0">
                  <input type="checkbox" class="form-check-input" id="trang_thai_create" name="trang_thai" value="1" checked>
                  <label class="form-check-label" for="trang_thai_create">Hiển thị trên trang chủ</label>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" class="btn btn-primary" style="background:var(--admin-primary);border:none;">Thêm mới</button>
              </div>
            </div>
          </form>
        </div>
      </div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    // Auto-generate slug từ tên danh mục
    const nameInput = document.querySelector('#createModal input[name="ten_danh_muc"]');
    const slugInput = document.getElementById('slugInput');
    if (nameInput && slugInput) {
        nameInput.addEventListener('input', function() {
            slugInput.value = this.value
                .toLowerCase()
                .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                .replace(/đ/g, 'd').replace(/Đ/g, 'd')
                .replace(/[^a-z0-9\s-]/g, '')
                .trim().replace(/\s+/g, '-');
        });
    }

    // Tìm kiếm trong bảng
    document.getElementById('searchInput')?.addEventListener('input', function() {
        const q = this.value.toLowerCase();
        document.querySelectorAll('tbody tr').forEach(row => {
            const name = row.dataset.name || '';
            row.style.display = name.includes(q) ? '' : 'none';
        });
    });

    // AJAX Submit forms
    document.querySelectorAll('form').forEach(form => {
        if (form.action.includes('danhmuc') && form.method.toUpperCase() === 'POST') {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn ? submitBtn.innerHTML : '';
                if (submitBtn) {
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang xử lý...';
                    submitBtn.disabled = true;
                }
                fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || formData.get('_token')
                    },
                    body: formData
                })
                .then(r => r.ok ? r.json() : r.json().then(e => { throw e; }))
                .then(data => {
                    if (data.success) {
                        const modal = this.closest('.modal');
                        if (modal) bootstrap.Modal.getInstance(modal)?.hide();
                        window.location.reload();
                    }
                })
                .catch(err => {
                    if (submitBtn) { submitBtn.innerHTML = originalText; submitBtn.disabled = false; }
                    if (err.errors) {
                        alert('Dữ liệu không hợp lệ:\n' + Object.values(err.errors).flat().join('\n'));
                    } else {
                        alert(err.message || 'Đã có lỗi xảy ra.');
                    }
                });
            });
        }
    });

    // AJAX Delete
    document.getElementById('confirmDeleteBtn')?.addEventListener('click', function() {
        if (!window._deleteUrl) return;
        const btn = this;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang xóa...';
        btn.disabled = true;
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        fetch(window._deleteUrl, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json' },
            body: JSON.stringify({ _method: 'DELETE' })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('deleteModal'))?.hide();
                if (window._rowToDelete) {
                    window._rowToDelete.style.transition = 'opacity .3s';
                    window._rowToDelete.style.opacity = '0';
                    setTimeout(() => window._rowToDelete.remove(), 300);
                }
            }
        })
        .catch(() => alert('Lỗi: Không thể xóa danh mục này.'))
        .finally(() => { btn.innerHTML = 'Xóa'; btn.disabled = false; });
    });
});

function deleteDataAjax(id, name, url) {
    window._deleteUrl = url;
    window._rowToDelete = event?.target?.closest('tr');
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    document.getElementById('deleteModalLabel') && (document.getElementById('deleteModalLabel').textContent = 'Xác nhận xóa');
    modal.show();
}
</script>
@endsection
