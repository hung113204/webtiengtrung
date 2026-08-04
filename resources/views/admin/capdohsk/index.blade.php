@extends('admin.layouts.main')

@section('title', 'Quản lý Cấp độ HSK — Hányǔ Admin')

@section('content')
<div class="page-header animate-fade-in delay-1">
    <div>
        <h1 class="fs-4 fw-bold mb-1">Quản lý Cấp độ HSK</h1>
        <p class="text-muted mb-0 small">Thiết lập danh mục các cấp độ HSK (HSK1 đến HSK7-9) để phân loại bài học, đề thi.</p>
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
        <button class="btn btn-primary d-flex align-items-center gap-2 shadow-sm" style="background: var(--admin-primary); border: none;" data-bs-toggle="modal" data-bs-target="#addHskModal">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Thêm cấp độ mới
        </button>
    </div>
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

<!-- HSK Data Table -->
<div class="table-card animate-fade-in delay-2">
    <div class="table-header d-flex flex-wrap gap-3">
        <div class="input-group" style="max-width: 300px;">
            <span class="input-group-text bg-white border-end-0 text-muted">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </span>
            <input type="text" class="form-control border-start-0 ps-0" placeholder="Tìm tên cấp độ...">
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light text-muted small">
                <tr>
                    <th class="fw-medium px-4 py-3" style="width: 50px;">
                        <input class="form-check-input" type="checkbox" id="selectAll">
                    </th>
                    <th class="fw-medium py-3" style="width: 80px;">ID</th>
                    <th class="fw-medium py-3" style="width: 200px;">Tên cấp độ</th>
                    <th class="fw-medium py-3 text-center">Từ vựng</th>
                    <th class="fw-medium py-3 text-center">Ngữ pháp</th>
                    <th class="fw-medium py-3 text-center" style="width: 100px;">Thứ tự</th>
                    <th class="fw-medium py-3" style="width: 150px;">Ngày tạo</th>
                    <th class="fw-medium pe-4 py-3 text-end" style="width: 120px;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($danhsach as $item)
                @php
                    // Mảng màu sắc ngẫu nhiên cho Badge
                    $colors = ['#6366f1', '#10b981', '#f59e0b', '#3b82f6', '#8b5cf6', '#ec4899', '#ef4444'];
                    $color = $colors[($item->thu_tu - 1) % count($colors)];
                @endphp
                <tr data-row-id="{{ $item->id }}">
                    <td class="px-4 py-3">
                        <input class="form-check-input row-checkbox" type="checkbox" value="{{ $item->id }}">
                    </td>
                    <td class="py-3 text-muted">{{ $item->id }}</td>
                    <td>
                        <span class="badge" style="background-color: {{ $color }}; color: white; font-size: 0.9rem; padding: 0.5em 0.8em;">{{ $item->ten_cap_do }}</span>
                        <div class="small text-muted mt-1">{{ $item->slug }}</div>
                    </td>
                    <td class="text-center fw-medium text-primary">{{ $item->tu_vungs_count ?: '0' }}</td>
                    <td class="text-center fw-medium text-success">{{ $item->ngu_phaps_count ?: '0' }}</td>
                    <td class="text-center fw-medium">{{ $item->thu_tu }}</td>
                    <td class="small text-muted">{{ $item->created_at ? $item->created_at->format('d/m/Y') : '' }}</td>
                    <td class="text-end pe-4">
                        <div class="d-flex justify-content-end gap-1">
                            <button type="button" class="icon-btn" title="Chỉnh sửa" data-bs-toggle="modal" data-bs-target="#editHskModal{{ $item->id }}">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            </button>
                            <button type="button" class="icon-btn text-danger btn-delete-ajax" data-id="{{ $item->id }}" title="Xóa">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path></svg>
                            </button>
                        </div>
                    </td>
                </tr>


                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">Chưa có cấp độ HSK nào được thiết lập.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Phân trang -->
    @if($danhsach->hasPages())
    <div class="p-3 border-top border-light">
        {{ $danhsach->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

<!-- Modal Thêm Cấp độ HSK -->
<div class="modal fade" id="addHskModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="background: var(--admin-card);">
            <form action="{{ route('admin.capdohsk.store') }}" method="POST">
                @csrf
                <div class="modal-header border-bottom border-light">
                    <h5 class="modal-title fw-bold">Thêm Cấp độ HSK mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Tên cấp độ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="ten_cap_do" placeholder="VD: HSK 1, HSK 7-9..." required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Slug <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="slug" placeholder="VD: hsk-1" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Thứ tự hiển thị <span class="text-danger">*</span></label>
                        <input type="number" min="1" class="form-control" name="thu_tu" placeholder="VD: 1, 2..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Mô tả chi tiết</label>
                        <textarea class="form-control" name="mo_ta" rows="2" placeholder="Nhập mô tả cho cấp độ này..."></textarea>
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

<!-- Modal Import Excel -->
<div class="modal fade" id="importExcelModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="background: var(--admin-card);">
            <form action="{{ route('admin.capdohsk.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header border-bottom border-light">
                    <h5 class="modal-title fw-bold">Nhập Cấp độ HSK từ Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info py-2 small">
                        <strong>Cấu trúc file hợp lệ:</strong> Bỏ qua dòng tiêu đề. Cột A là <strong>Tên Cấp độ</strong> (Bắt buộc), Cột B là <strong>Mô tả</strong>. <br>Hệ thống tự động sinh Số thứ tự và Slug.
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

<!-- Render Edit Modals outside the table -->
@foreach($danhsach as $item)
<div class="modal fade" id="editHskModal{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="background: var(--admin-card);">
            <form action="{{ route('admin.capdohsk.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header border-bottom border-light">
                    <h5 class="modal-title fw-bold">Chỉnh sửa Cấp độ HSK</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Tên cấp độ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="ten_cap_do" value="{{ $item->ten_cap_do }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Slug <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="slug" value="{{ $item->slug }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Thứ tự hiển thị <span class="text-danger">*</span></label>
                        <input type="number" min="1" class="form-control" name="thu_tu" value="{{ $item->thu_tu }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Mô tả chi tiết</label>
                        <textarea class="form-control" name="mo_ta" rows="2">{{ $item->mo_ta }}</textarea>
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
                        fetch(`/admin/capdohsk/${id}`, {
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
                    text: `Bạn chuẩn bị xóa ${ids.length} cấp độ HSK. Dữ liệu không thể khôi phục!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Xóa tất cả!',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`{{ route('admin.capdohsk.bulkDelete') }}`, {
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
