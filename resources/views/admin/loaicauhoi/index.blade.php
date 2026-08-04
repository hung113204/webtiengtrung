@extends('admin.layouts.main')

@section('title', 'Quản lý Loại Câu Hỏi — Hányǔ Admin')

@section('content')
<div class="page-header animate-fade-in delay-1">
  <div>
    <h1 class="fs-4 fw-bold mb-1">Quản lý Loại Câu Hỏi</h1>
    <p class="text-muted mb-0 small">Thiết lập các loại câu hỏi cho hệ thống bài tập và đề thi.</p>
  </div>
  <div class="d-flex gap-2">
    <button class="btn btn-danger d-flex align-items-center gap-2 shadow-sm d-none" id="btnBulkDelete">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path></svg>
      Xóa mục đã chọn (<span id="selectedCount">0</span>)
    </button>
    <button class="btn btn-outline-success d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#importExcelModal">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
      Nhập Excel
    </button>
    <button class="btn btn-primary d-flex align-items-center gap-2 shadow-sm" style="background: var(--admin-primary); border: none;" data-bs-toggle="modal" data-bs-target="#addLoaiModal">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
      Thêm loại câu hỏi
    </button>
  </div>
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

<div class="table-card animate-fade-in delay-2">
  <div class="table-header d-flex flex-wrap gap-3">
    <form action="{{ route('admin.loaicauhoi.index') }}" method="GET" class="d-flex flex-wrap gap-3 w-100">
        <div class="input-group" style="max-width: 300px;">
          <span class="input-group-text bg-white border-end-0 text-muted">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
          </span>
          <input type="text" class="form-control border-start-0 ps-0" name="search" value="{{ request('search') }}" placeholder="Tìm tên loại...">
        </div>
    </form>
  </div>

  <div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead class="table-light text-muted small">
        <tr>
          <th class="fw-medium px-4 py-3" style="width: 50px;">
              <input class="form-check-input" type="checkbox" id="selectAll">
          </th>
          <th class="fw-medium py-3" style="width: 80px;">ID</th>
          <th class="fw-medium py-3" style="width: 100px;">Thứ tự</th>
          <th class="fw-medium py-3">Tên loại câu hỏi</th>
          <th class="fw-medium py-3">Slug</th>
          <th class="fw-medium py-3">Ngày tạo</th>
          <th class="fw-medium pe-4 py-3 text-end">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        @forelse($loaiCauHois as $loai)
        <tr data-row-id="{{ $loai->id }}">
          <td class="px-4 py-3">
              <input class="form-check-input row-checkbox" type="checkbox" value="{{ $loai->id }}">
          </td>
          <td class="py-3 fw-bold text-muted">{{ $loai->id }}</td>
          <td class="py-3"><span class="badge bg-light text-dark border">{{ $loai->thu_tu }}</span></td>
          <td>
            <div class="fw-bold text-dark fs-6">{{ $loai->ten_loai }}</div>
          </td>
          <td>
            <code class="text-primary bg-light px-2 py-1 rounded">{{ $loai->slug }}</code>
          </td>
          <td class="small text-muted">
            {{ $loai->created_at ? $loai->created_at->format('d/m/Y') : 'N/A' }}
          </td>
          <td class="text-end pe-4">
            <div class="d-flex justify-content-end align-items-center gap-1">
              <button class="icon-btn" title="Chỉnh sửa" data-bs-toggle="modal" data-bs-target="#editLoaiModal{{ $loai->id }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
              </button>
              <button type="button" class="icon-btn text-danger btn-delete-ajax" data-id="{{ $loai->id }}" title="Xóa">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path></svg>
              </button>
            </div>
          </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center py-4 text-muted">Chưa có loại câu hỏi nào.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  
  @if($loaiCauHois->hasPages())
  <div class="card-footer bg-white border-top p-3 d-flex justify-content-between align-items-center">
    <span class="text-muted small">Hiển thị từ {{ $loaiCauHois->firstItem() }} đến {{ $loaiCauHois->lastItem() }} trong tổng số {{ $loaiCauHois->total() }}</span>
    {{ $loaiCauHois->links('pagination::bootstrap-5') }}
  </div>
  @endif
</div>

<!-- Add Modal -->
<div class="modal fade" id="addLoaiModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow" style="background: var(--admin-card);">
      <div class="modal-header border-bottom border-light">
        <h5 class="modal-title fw-bold">Thêm Loại Câu Hỏi Mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('admin.loaicauhoi.store') }}" method="POST" id="addLoaiForm">
          @csrf
          
          <div class="mb-3">
            <label class="form-label fw-medium">Tên loại câu hỏi <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="ten_loai" placeholder="VD: Trắc nghiệm 1 đáp án" required>
          </div>
          
          <div class="mb-3">
            <label class="form-label fw-medium">Slug (Không bắt buộc)</label>
            <input type="text" class="form-control" name="slug" placeholder="VD: trac-nghiem-1-dap-an">
            <div class="form-text">Nếu để trống, hệ thống sẽ tự động tạo từ Tên loại.</div>
          </div>
          
          <div class="mb-3">
            <label class="form-label fw-medium">Thứ tự hiển thị</label>
            <input type="number" class="form-control" name="thu_tu" placeholder="VD: 1" value="0">
          </div>

        </form>
      </div>
      <div class="modal-footer border-top border-light">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy bỏ</button>
        <button type="button" class="btn btn-primary" onclick="document.getElementById('addLoaiForm').submit()" style="background: var(--admin-primary); border: none;">Thêm mới</button>
      </div>
    </div>
  </div>
</div>
@endsection

<!-- Modal Import Excel -->
<div class="modal fade" id="importExcelModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="background: var(--admin-card);">
            <form action="{{ route('admin.loaicauhoi.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header border-bottom border-light">
                    <h5 class="modal-title fw-bold">Nhập Loại Câu Hỏi từ Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info py-2 small">
                        <strong>Cấu trúc file hợp lệ:</strong> Bỏ qua dòng tiêu đề. Cột A là <strong>Tên Loại Câu Hỏi</strong> (Bắt buộc). <br>Hệ thống tự động sinh Số thứ tự và Slug.
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Chọn file Excel (.xlsx, .csv) <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="file" accept=".xlsx, .xls, .csv" required>
                    </div>
                </div>
                <div class="modal-footer border-top border-light">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy bỏ</button>
                    <button type="submit" class="btn btn-success d-flex align-items-center gap-2">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                        Tải lên & Nhập
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modals Sửa -->
@foreach($loaiCauHois as $loai)
<div class="modal fade" id="editLoaiModal{{ $loai->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow" style="background: var(--admin-card);">
      <div class="modal-header border-bottom border-light">
        <h5 class="modal-title fw-bold">Chỉnh sửa Loại Câu Hỏi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('admin.loaicauhoi.update', $loai->id) }}" method="POST" id="editLoaiForm{{ $loai->id }}">
          @csrf
          @method('PUT')
          
          <div class="mb-3">
            <label class="form-label fw-medium">Tên loại câu hỏi <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="ten_loai" value="{{ $loai->ten_loai }}" required>
          </div>
          
          <div class="mb-3">
            <label class="form-label fw-medium">Thứ tự hiển thị</label>
            <input type="number" class="form-control" name="thu_tu" value="{{ $loai->thu_tu }}">
          </div>
          
          <div class="mb-3">
            <label class="form-label fw-medium">Slug (Đường dẫn tĩnh)</label>
            <input type="text" class="form-control" name="slug" value="{{ $loai->slug }}">
            <div class="form-text">Sẽ được tự động cập nhật nếu bạn để trống.</div>
          </div>

        </form>
      </div>
      <div class="modal-footer border-top border-light">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy bỏ</button>
        <button type="button" class="btn btn-primary" onclick="document.getElementById('editLoaiForm{{ $loai->id }}').submit()" style="background: var(--admin-primary); border: none;">Lưu thay đổi</button>
      </div>
    </div>
  </div>
</div>
@endforeach

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteBtns = document.querySelectorAll('.btn-delete-ajax');
        
        // Single delete logic
        deleteBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const row = this.closest('tr');
                
                Swal.fire({
                    title: 'Bạn có chắc chắn?',
                    text: "Dữ liệu sẽ không thể khôi phục!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Vâng, Xóa nó!',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/admin/loaicauhoi/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if(data.success) {
                                Swal.fire('Đã xóa!', data.message, 'success');
                                row.style.transition = 'opacity 0.5s ease';
                                row.style.opacity = '0';
                                setTimeout(() => { row.remove(); updateCheckboxes(); }, 500);
                            } else {
                                Swal.fire('Lỗi', data.message || 'Có lỗi xảy ra', 'error');
                            }
                        })
                        .catch(error => {
                            Swal.fire('Lỗi', 'Không thể kết nối đến máy chủ.', 'error');
                        });
                    }
                })
            });
        });

        // Bulk delete logic
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.row-checkbox');
        const btnBulkDelete = document.getElementById('btnBulkDelete');
        const selectedCount = document.getElementById('selectedCount');

        function updateCheckboxes() {
            const activeCheckboxes = document.querySelectorAll('.row-checkbox');
            const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
            
            if(activeCheckboxes.length > 0) {
                selectAll.checked = activeCheckboxes.length === checkedBoxes.length;
            } else {
                selectAll.checked = false;
            }

            if (checkedBoxes.length > 0) {
                btnBulkDelete.classList.remove('d-none');
                selectedCount.textContent = checkedBoxes.length;
            } else {
                btnBulkDelete.classList.add('d-none');
            }
        }

        if(selectAll) {
            selectAll.addEventListener('change', function() {
                document.querySelectorAll('.row-checkbox').forEach(cb => {
                    cb.checked = this.checked;
                });
                updateCheckboxes();
            });
        }

        document.querySelectorAll('.row-checkbox').forEach(cb => {
            cb.addEventListener('change', updateCheckboxes);
        });

        if(btnBulkDelete) {
            btnBulkDelete.addEventListener('click', function() {
                const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
                const ids = Array.from(checkedBoxes).map(cb => cb.value);

                Swal.fire({
                    title: 'Xác nhận xóa nhiều?',
                    text: `Bạn chuẩn bị xóa ${ids.length} loại câu hỏi. Dữ liệu không thể khôi phục!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Xóa tất cả!',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`{{ route('admin.loaicauhoi.bulkDelete') }}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ ids: ids })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if(data.success) {
                                Swal.fire('Thành công!', data.message, 'success');
                                checkedBoxes.forEach(cb => {
                                    const row = cb.closest('tr');
                                    row.style.transition = 'opacity 0.5s ease';
                                    row.style.opacity = '0';
                                    setTimeout(() => row.remove(), 500);
                                });
                                setTimeout(updateCheckboxes, 550);
                            } else {
                                Swal.fire('Lỗi', data.message || 'Có lỗi xảy ra', 'error');
                            }
                        })
                        .catch(error => {
                            Swal.fire('Lỗi', 'Không thể kết nối đến máy chủ.', 'error');
                        });
                    }
                });
            });
        }
    });
</script>
@endsection
