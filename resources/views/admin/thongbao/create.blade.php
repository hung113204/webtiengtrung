@extends('admin.layouts.main')

@section('title', 'Tạo Thông Báo Mới — Hányǔ Admin')

@section('content')
<div class="page-header animate-fade-in delay-1 mb-4">
  <div>
    <h1 class="fs-4 fw-bold mb-1" style="letter-spacing: -0.01em;">Tạo Thông Báo Mới</h1>
    <p class="text-muted mb-0 small">Soạn tiêu đề, nội dung và gửi đến các đối tượng học viên.</p>
  </div>
  <a href="{{ route('admin.thongbao.index') }}" class="btn btn-light border d-flex align-items-center gap-2 rounded-3">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
    Quay lại
  </a>
</div>

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show animate-fade-in mb-4" role="alert">
    <ul class="mb-0 small">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card border-0 shadow-sm animate-fade-in delay-2 mb-5" style="background: var(--admin-card); border-radius: 16px;">
  <div class="card-body p-4 p-md-5">
    <form action="{{ route('admin.thongbao.store') }}" method="POST">
      @csrf

      <div class="row">
        <!-- Left Column: Title & Content -->
        <div class="col-lg-8 mb-4 mb-lg-0">
          <div class="mb-4">
            <label for="tieu_de" class="form-label fw-bold text-secondary small">Tiêu đề thông báo</label>
            <input type="text" name="tieu_de" id="tieu_de" class="form-control rounded-3 border-0 bg-light p-3 fs-6" placeholder="Nhập tiêu đề thông báo..." required value="{{ old('tieu_de') }}">
          </div>

          <div class="mb-3">
            <label for="noi_dung" class="form-label fw-bold text-secondary small">Nội dung chi tiết</label>
            <textarea name="noi_dung" id="noi_dung" class="form-control ckeditor rounded-3 border-0 bg-light p-3" rows="12" placeholder="Nhập nội dung chi tiết thông báo gửi đến học viên..." required>{{ old('noi_dung') }}</textarea>
          </div>
        </div>

        <!-- Right Column: Recipient Settings -->
        <div class="col-lg-4">
          <div class="p-4 rounded-4 bg-light border border-light-subtle h-100">
            <h5 class="fw-bold text-dark mb-4 fs-6">Đối tượng nhận thông báo</h5>

            <!-- Target selection -->
            <div class="mb-4">
              <label for="gui_toi" class="form-label fw-bold text-secondary small">Gửi tới</label>
              <select name="gui_toi" id="gui_toi" class="form-select rounded-3 border-0 bg-white p-2.5 shadow-sm" required>
                <option value="all" {{ old('gui_toi') === 'all' ? 'selected' : '' }}>Tất cả học viên</option>
                <option value="custom" {{ old('gui_toi') === 'custom' ? 'selected' : '' }}>Học viên được chỉ định</option>
              </select>
            </div>

            <!-- Custom recipients list -->
            <div class="mb-4" id="custom-recipients-group" style="display: none;">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="form-label fw-bold text-secondary small mb-0">Chọn học viên</label>
                <span class="text-muted small" id="checked-count">Đã chọn 0</span>
              </div>
              
              <!-- Search box inside list -->
              <input type="text" id="user-search" class="form-control form-control-sm rounded-3 border-0 bg-white shadow-sm mb-2" placeholder="Tìm tên hoặc email...">

              <!-- Scrollable user checkbox list -->
              <div class="border rounded-3 bg-white p-3 shadow-sm" style="max-height: 280px; overflow-y: auto;">
                @foreach($users as $user)
                  <div class="form-check mb-2 user-checkbox-item">
                    <input class="form-check-input recipient-checkbox" type="checkbox" name="id_nguoi_dung[]" value="{{ $user->id }}" id="user-{{ $user->id }}" {{ is_array(old('id_nguoi_dung')) && in_array($user->id, old('id_nguoi_dung')) ? 'checked' : '' }}>
                    <label class="form-check-label text-dark small" for="user-{{ $user->id }}">
                      <strong class="user-display-name">{{ $user->ho_ten }}</strong>
                      <span class="text-muted d-block user-display-email" style="font-size: 0.75rem;">{{ $user->email }}</span>
                    </label>
                  </div>
                @endforeach
              </div>
            </div>

            <!-- Send button -->
            <div class="d-grid mt-5">
              <button type="submit" class="btn btn-primary rounded-3 p-3 shadow" style="background: var(--admin-primary); border: none;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1.5"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                Gửi thông báo
              </button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const targetSelect = document.getElementById('gui_toi');
    const customGroup = document.getElementById('custom-recipients-group');
    const searchInput = document.getElementById('user-search');
    const userItems = document.querySelectorAll('.user-checkbox-item');
    const checkboxes = document.querySelectorAll('.recipient-checkbox');
    const checkedCountText = document.getElementById('checked-count');

    // Toggle target audience
    function toggleRecipientList() {
      if (targetSelect.value === 'custom') {
        customGroup.style.display = 'block';
      } else {
        customGroup.style.display = 'none';
      }
    }
    targetSelect.addEventListener('change', toggleRecipientList);
    toggleRecipientList(); // Initial call

    // Count checked checkboxes
    function updateCheckedCount() {
      const checkedCount = document.querySelectorAll('.recipient-checkbox:checked').length;
      checkedCountText.textContent = `Đã chọn ${checkedCount}`;
    }
    checkboxes.forEach(chk => chk.addEventListener('change', updateCheckedCount));
    updateCheckedCount(); // Initial count

    // Live search filter
    searchInput.addEventListener('input', function() {
      const query = this.value.toLowerCase().trim();
      
      userItems.forEach(item => {
        const name = item.querySelector('.user-display-name').textContent.toLowerCase();
        const email = item.querySelector('.user-display-email').textContent.toLowerCase();
        
        if (name.includes(query) || email.includes(query)) {
          item.style.setProperty('display', 'block', 'important');
        } else {
          item.style.setProperty('display', 'none', 'important');
        }
      });
    });
  });
</script>
@endsection
