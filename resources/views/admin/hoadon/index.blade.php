@extends('admin.layouts.main')

@section('title', 'Quản lý Hóa đơn — Hányǔ Admin')

@section('content')
<div class="page-header animate-fade-in delay-1 mb-4 d-flex justify-content-between align-items-center">
  <div>
    <h1 class="fs-4 fw-bold mb-1" style="letter-spacing: -0.01em;">Quản lý Hóa đơn</h1>
    <p class="text-muted mb-0 small">Theo dõi hóa đơn, doanh thu khóa học và đồng bộ trạng thái thanh toán.</p>
  </div>
</div>

<!-- Alert notifications -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show animate-fade-in mb-4" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Stats Row -->
<div class="row mb-4 animate-fade-in delay-2">
  <div class="col-md-3 mb-3 mb-md-0">
    <div class="card border-0 shadow-sm h-100" style="background: var(--admin-card); border-radius: 16px;">
      <div class="card-body d-flex align-items-center gap-3 p-4">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(16, 185, 129, 0.1); color: #10b981; display: flex; align-items: center; justify-content: center;">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
        </div>
        <div>
          <div class="text-muted small fw-medium">Tổng doanh thu</div>
          <div class="fs-5 fw-bold text-success">{{ number_format($totalRevenue) }}đ</div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-col-md-3 col-md-3 mb-3 mb-md-0">
    <div class="card border-0 shadow-sm h-100" style="background: var(--admin-card); border-radius: 16px;">
      <div class="card-body d-flex align-items-center gap-3 p-4">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(59, 130, 246, 0.1); color: #3b82f6; display: flex; align-items: center; justify-content: center;">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
        </div>
        <div>
          <div class="text-muted small fw-medium">Đã thanh toán</div>
          <div class="fs-5 fw-bold text-dark">{{ number_format($paidCount) }} HĐ</div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-col-md-3 col-md-3 mb-3 mb-md-0">
    <div class="card border-0 shadow-sm h-100" style="background: var(--admin-card); border-radius: 16px;">
      <div class="card-body d-flex align-items-center gap-3 p-4">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(245, 158, 11, 0.1); color: #f59e0b; display: flex; align-items: center; justify-content: center;">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
        </div>
        <div>
          <div class="text-muted small fw-medium">Chờ thanh toán</div>
          <div class="fs-5 fw-bold text-dark">{{ number_format($pendingCount) }} HĐ</div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-col-md-3 col-md-3">
    <div class="card border-0 shadow-sm h-100" style="background: var(--admin-card); border-radius: 16px;">
      <div class="card-body d-flex align-items-center gap-3 p-4">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(239, 68, 68, 0.1); color: #ef4444; display: flex; align-items: center; justify-content: center;">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
        </div>
        <div>
          <div class="text-muted small fw-medium">Hóa đơn đã hủy</div>
          <div class="fs-5 fw-bold text-dark">{{ number_format($canceledCount) }} HĐ</div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Filters and List -->
<div class="card border-0 shadow-sm animate-fade-in delay-3 mb-5" style="background: var(--admin-card); border-radius: 16px;">
  <div class="card-header border-0 bg-transparent p-4 pb-0">
    <form method="GET" action="{{ route('admin.hoadon.index') }}" class="row g-3">
      <div class="col-md-4">
        <div class="input-group">
          <span class="input-group-text bg-light border-0 text-muted rounded-start-3 px-3">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
          </span>
          <input type="text" name="search" value="{{ request('search') }}" class="form-control bg-light border-0 ps-0 rounded-end-3" placeholder="Mã HĐ, mã GD, học viên...">
        </div>
      </div>
      
      <div class="col-md-8 d-flex justify-content-md-end gap-2 flex-wrap">
        <!-- Status Filter -->
        <select name="trang_thai" class="form-select bg-light border-0 rounded-3" style="width: auto; min-width: 170px; box-shadow: none;" onchange="this.form.submit()">
          <option value="">Tất cả trạng thái</option>
          <option value="Chưa thanh toán" {{ request('trang_thai') === 'Chưa thanh toán' ? 'selected' : '' }}>Chưa thanh toán</option>
          <option value="Đã thanh toán" {{ request('trang_thai') === 'Đã thanh toán' ? 'selected' : '' }}>Đã thanh toán</option>
          <option value="Đã hủy" {{ request('trang_thai') === 'Đã hủy' ? 'selected' : '' }}>Đã hủy</option>
        </select>

        <!-- Payment Method Filter -->
        <select name="phuong_thuc_thanh_toan" class="form-select bg-light border-0 rounded-3" style="width: auto; min-width: 190px; box-shadow: none;" onchange="this.form.submit()">
          <option value="">Tất cả phương thức</option>
          <option value="Chuyển khoản" {{ request('phuong_thuc_thanh_toan') === 'Chuyển khoản' ? 'selected' : '' }}>Chuyển khoản</option>
          <option value="Tiền mặt" {{ request('phuong_thuc_thanh_toan') === 'Tiền mặt' ? 'selected' : '' }}>Tiền mặt</option>
          <option value="Ví Momo" {{ request('phuong_thuc_thanh_toan') === 'Ví Momo' ? 'selected' : '' }}>Ví Momo</option>
          <option value="Ví ZaloPay" {{ request('phuong_thuc_thanh_toan') === 'Ví ZaloPay' ? 'selected' : '' }}>Ví ZaloPay</option>
          <option value="Thẻ tín dụng" {{ request('phuong_thuc_thanh_toan') === 'Thẻ tín dụng' ? 'selected' : '' }}>Thẻ tín dụng</option>
        </select>
        
        @if(request('search') || request('trang_thai') || request('phuong_thuc_thanh_toan'))
          <a href="{{ route('admin.hoadon.index') }}" class="btn btn-outline-secondary rounded-3 d-flex align-items-center gap-2">
            Xóa lọc
          </a>
        @endif
      </div>
    </form>
  </div>

  <div class="card-body p-4">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th class="border-0 rounded-start-3" style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: #6b7280; padding: 12px 16px;">Mã Hóa Đơn</th>
            <th class="border-0" style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: #6b7280; padding: 12px 16px;">Học viên</th>
            <th class="border-0" style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: #6b7280; padding: 12px 16px;">Khóa học</th>
            <th class="border-0" style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: #6b7280; padding: 12px 16px;">Số tiền</th>
            <th class="border-0" style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: #6b7280; padding: 12px 16px;">Thanh toán</th>
            <th class="border-0" style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: #6b7280; padding: 12px 16px;">Trạng thái</th>
            <th class="border-0 rounded-end-3 text-end" style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: #6b7280; padding: 12px 16px; width: 150px;">Hành động</th>
          </tr>
        </thead>
        <tbody>
          @forelse($invoices as $invoice)
            <tr class="align-middle">
              <td class="px-3 py-3">
                <span class="fw-bold text-dark">{{ $invoice->ma_hoa_don }}</span>
                <div class="text-muted small" style="font-size: 0.7rem;">ID Đăng ký: #{{ $invoice->id_dang_ky ?? 'N/A' }}</div>
              </td>
              <td class="px-3">
                <div class="d-flex align-items-center gap-2">
                  <div class="avatar-circle-sm" style="width: 32px; height: 32px; border-radius: 50%; background: #f3f4f6; display: flex; align-items: center; justify-content: center; font-weight: bold; color: var(--admin-primary); font-size: 0.8rem;">
                    {{ mb_substr($invoice->nguoiDung->ho_ten ?? 'H', 0, 1) }}
                  </div>
                  <div>
                    <h6 class="mb-0 fw-semibold text-dark" style="font-size: 0.85rem;">{{ $invoice->nguoiDung->ho_ten ?? 'Người dùng ẩn' }}</h6>
                    <span class="text-muted small" style="font-size: 0.75rem;">{{ $invoice->nguoiDung->email ?? '' }}</span>
                  </div>
                </div>
              </td>
              <td class="px-3">
                <div class="fw-semibold text-dark" style="font-size: 0.85rem; max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                  {{ $invoice->dangKyKhoaHoc->khoaHoc->ten_khoa_hoc ?? 'Khóa học đã xóa' }}
                </div>
                <div class="text-muted small" style="font-size: 0.7rem;">
                  Ngày đăng ký: {{ $invoice->created_at ? $invoice->created_at->format('d/m/Y') : '' }}
                </div>
              </td>
              <td class="px-3 text-dark fw-bold" style="font-size: 0.85rem;">
                {{ number_format($invoice->so_tien) }}đ
              </td>
              <td class="px-3">
                <span class="d-block fw-medium text-dark" style="font-size: 0.8rem;">{{ $invoice->phuong_thuc_thanh_toan }}</span>
                @if($invoice->ma_giao_dich)
                  <span class="badge bg-light text-muted border px-1.5 py-0.5 rounded" style="font-size: 0.7rem; font-weight: normal;">
                    GD: {{ $invoice->ma_giao_dich }}
                  </span>
                @endif
                @if($invoice->ngay_thanh_toan)
                  <div class="text-muted small mt-0.5" style="font-size: 0.7rem;">
                    {{ $invoice->ngay_thanh_toan->format('H:i d/m/Y') }}
                  </div>
                @endif
              </td>
              <td class="px-3">
                @if($invoice->trang_thai === 'Chưa thanh toán')
                  <span class="badge bg-warning bg-opacity-10 text-warning px-2.5 py-1.5 rounded-pill border border-warning-subtle small" style="font-size: 0.75rem;">Chưa thanh toán</span>
                @elseif($invoice->trang_thai === 'Đã thanh toán')
                  <span class="badge bg-success bg-opacity-10 text-success px-2.5 py-1.5 rounded-pill border border-success-subtle small" style="font-size: 0.75rem;">Đã thanh toán</span>
                @else
                  <span class="badge bg-danger bg-opacity-10 text-danger px-2.5 py-1.5 rounded-pill border border-danger-subtle small" style="font-size: 0.75rem;">Đã hủy</span>
                @endif
              </td>
              <td class="text-end pe-4">
                <div class="d-flex justify-content-end align-items-center gap-1">
                  <!-- Edit/Detail button -->
                  <button type="button" 
                          class="icon-btn edit-invoice-btn" 
                          title="Chỉnh sửa"
                          data-bs-toggle="modal" 
                          data-bs-target="#editInvoiceModal"
                          data-id="{{ $invoice->id }}"
                          data-ma-hoa-don="{{ $invoice->ma_hoa_don }}"
                          data-ho-ten="{{ $invoice->nguoiDung->ho_ten ?? 'Người dùng ẩn' }}"
                          data-email="{{ $invoice->nguoiDung->email ?? '' }}"
                          data-khoa-hoc="{{ $invoice->dangKyKhoaHoc->khoaHoc->ten_khoa_hoc ?? 'Khóa học đã xóa' }}"
                          data-so-tien="{{ $invoice->so_tien }}"
                          data-phuong-thuc="{{ $invoice->phuong_thuc_thanh_toan }}"
                          data-ma-giao-dich="{{ $invoice->ma_giao_dich ?? '' }}"
                          data-trang-thai="{{ $invoice->trang_thai }}"
                          data-ngay-thanh-toan="{{ $invoice->ngay_thanh_toan ? $invoice->ngay_thanh_toan->format('Y-m-d\TH:i') : '' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                  </button>

                  <!-- Delete button -->
                  <form action="{{ route('admin.hoadon.destroy', $invoice->id) }}" method="POST" class="m-0 p-0 d-flex" onsubmit="return confirm('Bạn có chắc chắn muốn xóa hóa đơn này?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="icon-btn text-danger" title="Xóa">
                      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center py-5 text-muted">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mb-2 text-muted"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                <p class="mb-0 small">Không tìm thấy hóa đơn nào.</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
      {{ $invoices->links() }}
    </div>
  </div>
</div>

<!-- Edit / Detail Modal -->
<div class="modal fade" id="editInvoiceModal" tabindex="-1" aria-labelledby="editInvoiceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow" style="border-radius: 16px; background: var(--admin-card, #ffffff);">
      <div class="modal-header border-0 p-4 pb-0">
        <h5 class="modal-title fw-bold text-dark" id="editInvoiceModalLabel">Thông Tin Hóa Đơn <span id="modal-ma-hoa-don" class="text-primary"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editInvoiceForm" method="POST" action="">
        @csrf
        @method('PUT')
        <div class="modal-body p-4">
          <!-- Information Summary Section -->
          <div class="bg-light p-3 rounded-3 mb-4 border border-light-subtle">
            <div class="row g-2 small">
              <div class="col-4 text-muted">Học viên:</div>
              <div class="col-8 fw-semibold text-dark" id="modal-ho-ten"></div>
              
              <div class="col-4 text-muted">Email:</div>
              <div class="col-8 text-dark text-break" id="modal-email"></div>

              <div class="col-4 text-muted">Khóa học:</div>
              <div class="col-8 fw-semibold text-dark" id="modal-khoa-hoc"></div>
            </div>
          </div>

          <!-- Edit Inputs -->
          <div class="mb-3">
            <label for="edit-so-tien" class="form-label small fw-bold text-secondary">Số tiền thanh toán (đ)</label>
            <input type="number" name="so_tien" id="edit-so-tien" class="form-control rounded-3 border-0 bg-light p-2.5" style="box-shadow: none;" required min="0">
          </div>

          <div class="mb-3">
            <label for="edit-phuong-thuc" class="form-label small fw-bold text-secondary">Phương thức thanh toán</label>
            <select name="phuong_thuc_thanh_toan" id="edit-phuong-thuc" class="form-select rounded-3 border-0 bg-light p-2.5" style="box-shadow: none;" required>
              <option value="Chuyển khoản">Chuyển khoản</option>
              <option value="Tiền mặt">Tiền mặt</option>
              <option value="Ví Momo">Ví Momo</option>
              <option value="Ví ZaloPay">Ví ZaloPay</option>
              <option value="Thẻ tín dụng">Thẻ tín dụng</option>
              <option value="Khác">Khác</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="edit-ma-giao-dich" class="form-label small fw-bold text-secondary">Mã giao dịch (nếu có)</label>
            <input type="text" name="ma_giao_dich" id="edit-ma-giao-dich" class="form-control rounded-3 border-0 bg-light p-2.5" style="box-shadow: none;" placeholder="Nhập mã tham chiếu giao dịch">
          </div>

          <div class="mb-3">
            <label for="edit-trang-thai" class="form-label small fw-bold text-secondary">Trạng thái hóa đơn</label>
            <select name="trang_thai" id="edit-trang-thai" class="form-select rounded-3 border-0 bg-light p-2.5" style="box-shadow: none;" required>
              <option value="Chưa thanh toán">Chưa thanh toán</option>
              <option value="Đã thanh toán">Đã thanh toán</option>
              <option value="Đã hủy">Đã hủy</option>
            </select>
          </div>

          <div class="mb-3" id="payment-date-group" style="display: none;">
            <label for="edit-ngay-thanh-toan" class="form-label small fw-bold text-secondary">Ngày thanh toán</label>
            <input type="datetime-local" name="ngay_thanh_toan" id="edit-ngay-thanh-toan" class="form-control rounded-3 border-0 bg-light p-2.5" style="box-shadow: none;">
          </div>
        </div>
        <div class="modal-footer border-0 p-4 pt-0 d-flex justify-content-end gap-2">
          <button type="button" class="btn btn-outline-secondary rounded-3 px-4" data-bs-dismiss="modal">Đóng</button>
          <button type="submit" class="btn btn-success rounded-3 px-4">Lưu thay đổi</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const editButtons = document.querySelectorAll('.edit-invoice-btn');
    editButtons.forEach(btn => {
      btn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const maHoaDon = this.getAttribute('data-ma-hoa-don');
        const hoTen = this.getAttribute('data-ho-ten');
        const email = this.getAttribute('data-email');
        const khoaHoc = this.getAttribute('data-khoa-hoc');
        const soTien = this.getAttribute('data-so-tien');
        const phuongThuc = this.getAttribute('data-phuong-thuc');
        const maGiaoDich = this.getAttribute('data-ma-giao-dich');
        const trangThai = this.getAttribute('data-trang-thai');
        const ngayThanhToan = this.getAttribute('data-ngay-thanh-toan');

        // Set action route url dynamically
        document.getElementById('editInvoiceForm').action = `{{ url('admin/hoadon') }}/${id}`;

        // Populate fields
        document.getElementById('modal-ma-hoa-don').textContent = maHoaDon;
        document.getElementById('modal-ho-ten').textContent = hoTen;
        document.getElementById('modal-email').textContent = email;
        document.getElementById('modal-khoa-hoc').textContent = khoaHoc;

        document.getElementById('edit-so-tien').value = soTien;
        document.getElementById('edit-phuong-thuc').value = phuongThuc;
        document.getElementById('edit-ma-giao-dich').value = maGiaoDich;
        document.getElementById('edit-trang-thai').value = trangThai;
        document.getElementById('edit-ngay-thanh-toan').value = ngayThanhToan;

        toggleDateField(trangThai);
      });
    });

    const statusSelect = document.getElementById('edit-trang-thai');
    statusSelect.addEventListener('change', function() {
      toggleDateField(this.value);
    });

    function toggleDateField(status) {
      const group = document.getElementById('payment-date-group');
      if (status === 'Đã thanh toán') {
        group.style.display = 'block';
      } else {
        group.style.display = 'none';
      }
    }
  });
</script>
@endsection
