@extends('admin.layouts.main')

@section('title', 'Cấu hình Hệ thống — Hányǔ Admin')

@section('content')
<form action="{{ route('admin.caihinh.update') }}" method="POST" enctype="multipart/form-data">
  @csrf

  <div class="page-header animate-fade-in delay-1 mb-4 d-flex justify-content-between align-items-center">
    <div>
      <h1 class="fs-4 fw-bold mb-1" style="letter-spacing: -0.01em;">Cấu hình Hệ thống</h1>
      <p class="text-muted mb-0 small">Cấu hình chung, cổng thanh toán trực tuyến, SMTP email và bảo mật reCAPTCHA.</p>
    </div>
    <button type="submit" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm" style="background: var(--admin-primary); border: none;">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
      Lưu thay đổi
    </button>
  </div>

  <!-- Alert notifications -->
  @if(session('success'))
  <div class="alert alert-success alert-dismissible fade show animate-fade-in mb-4" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  @endif

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

  <div class="row g-4 animate-fade-in delay-2 mb-5">
    <!-- Settings Sidebar Tabs Navigation -->
    <div class="col-lg-3">
      <div class="table-card p-3 sticky-top" style="top: 80px; z-index: 1; border-radius: 16px;">
        <nav class="nav flex-column settings-nav" id="settings-tab" role="tablist">
          <button class="nav-link active text-start border-0 bg-transparent w-100 py-2.5 px-3 rounded-3" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" style="font-weight: 500;">
            <svg class="me-2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
            Cấu hình chung
          </button>
          <button class="nav-link text-start border-0 bg-transparent w-100 py-2.5 px-3 rounded-3 mt-1" data-bs-toggle="tab" data-bs-target="#payment" type="button" role="tab" style="font-weight: 500;">
            <svg class="me-2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
            Cổng thanh toán
          </button>
          <button class="nav-link text-start border-0 bg-transparent w-100 py-2.5 px-3 rounded-3 mt-1" data-bs-toggle="tab" data-bs-target="#smtp" type="button" role="tab" style="font-weight: 500;">
            <svg class="me-2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
            Cấu hình Email (SMTP)
          </button>
          <button class="nav-link text-start border-0 bg-transparent w-100 py-2.5 px-3 rounded-3 mt-1" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab" style="font-weight: 500;">
            <svg class="me-2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0110 0v4"></path></svg>
            Bảo mật & Captcha
          </button>
          
          <div class="my-2 border-top"></div>
          
          <button class="nav-link text-start border-0 bg-transparent w-100 py-2.5 px-3 rounded-3 mt-1" data-bs-toggle="tab" data-bs-target="#ui-khoahoc" type="button" role="tab" style="font-weight: 500;">
            <svg class="me-2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
            Giao diện Khóa học
          </button>
          <button class="nav-link text-start border-0 bg-transparent w-100 py-2.5 px-3 rounded-3 mt-1" data-bs-toggle="tab" data-bs-target="#ui-trangchu" type="button" role="tab" style="font-weight: 500;">
            <svg class="me-2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
            Giao diện Trang chủ
          </button>
        </nav>
      </div>
    </div>

    <!-- Settings Tabs Content -->
    <div class="col-lg-9">
      <div class="tab-content" id="settings-tabContent">
        
        <!-- Tab: Cấu hình chung -->
        <div class="tab-pane fade show active" id="general" role="tabpanel">
          <div class="table-card p-4 p-md-5" style="border-radius: 16px;">
            <h4 class="card-title fw-bold fs-5 text-dark mb-4">Thông tin cơ bản</h4>
            
            <div class="row g-4">
              <div class="col-md-6">
                <label for="website_name" class="form-label fw-semibold text-secondary small">Tên Website</label>
                <input type="text" name="website_name" id="website_name" class="form-control rounded-3 border-0 bg-light p-3" value="{{ old('website_name', $settings['website_name'] ?? '') }}" required>
              </div>
              <div class="col-md-6">
                <label for="contact_email" class="form-label fw-semibold text-secondary small">Email liên hệ</label>
                <input type="email" name="contact_email" id="contact_email" class="form-control rounded-3 border-0 bg-light p-3" value="{{ old('contact_email', $settings['contact_email'] ?? '') }}" required>
              </div>
              
              <div class="col-12">
                <label for="meta_description" class="form-label fw-semibold text-secondary small">Mô tả SEO (Meta Description)</label>
                <textarea name="meta_description" id="meta_description" class="form-control rounded-3 border-0 bg-light p-3" rows="4">{{ old('meta_description', $settings['meta_description'] ?? '') }}</textarea>
                <div class="form-text text-muted small mt-1.5">Mô tả này hiển thị trên kết quả tìm kiếm của Google giúp tối ưu hóa SEO.</div>
              </div>

              <div class="col-md-6">
                <label class="form-label fw-semibold text-secondary small d-block">Logo Website</label>
                <div class="d-flex align-items-center gap-3 mt-2">
                  <div class="bg-light border rounded-3 d-flex align-items-center justify-content-center overflow-hidden position-relative" style="width: 80px; height: 80px;">
                    @if(!empty($settings['website_logo']))
                      <img id="logo_preview" src="{{ Storage::url($settings['website_logo']) }}" alt="Logo" class="img-fluid" style="max-height: 100%; object-fit: contain;">
                      <span id="logo_preview_text" class="fs-1 fw-bold text-danger d-none">汉</span>
                      <button type="button" class="btn-close position-absolute top-0 end-0 p-1 m-1 bg-white rounded-circle shadow-sm" aria-label="Close" id="logo_remove_btn" style="font-size: 0.5rem;" onclick="removeImage('website_logo', 'logo_preview', 'logo_preview_text', 'logo_remove_btn', 'remove_website_logo')"></button>
                    @else
                      <span id="logo_preview_text" class="fs-1 fw-bold text-danger">汉</span>
                      <img id="logo_preview" src="" alt="Logo" class="img-fluid d-none" style="max-height: 100%; object-fit: contain;">
                      <button type="button" class="btn-close position-absolute top-0 end-0 p-1 m-1 bg-white rounded-circle shadow-sm d-none" aria-label="Close" id="logo_remove_btn" style="font-size: 0.5rem;" onclick="removeImage('website_logo', 'logo_preview', 'logo_preview_text', 'logo_remove_btn', 'remove_website_logo')"></button>
                    @endif
                  </div>
                  <div>
                    <input type="file" name="website_logo" id="website_logo" class="d-none" accept="image/*" onchange="previewImage(event, 'logo_preview', 'logo_preview_text', 'logo_remove_btn')">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-3" onclick="document.getElementById('website_logo').click()">Đổi Logo</button>
                    <input type="hidden" name="remove_website_logo" id="remove_website_logo" value="0">
                  </div>
                </div>
              </div>
              
              <div class="col-md-6">
                <label class="form-label fw-semibold text-secondary small d-block">Favicon</label>
                <div class="d-flex align-items-center gap-3 mt-2">
                  <div class="bg-light border rounded-3 d-flex align-items-center justify-content-center overflow-hidden position-relative" style="width: 40px; height: 40px;">
                    @if(!empty($settings['website_favicon']))
                      <img id="favicon_preview" src="{{ Storage::url($settings['website_favicon']) }}" alt="Favicon" class="img-fluid" style="max-height: 100%; object-fit: contain;">
                      <span id="favicon_preview_text" class="fw-bold text-danger d-none">H</span>
                      <button type="button" class="btn-close position-absolute top-0 end-0 bg-white rounded-circle shadow-sm" aria-label="Close" id="favicon_remove_btn" style="font-size: 0.35rem; padding: 0.15rem; margin: 2px;" onclick="removeImage('website_favicon', 'favicon_preview', 'favicon_preview_text', 'favicon_remove_btn', 'remove_website_favicon')"></button>
                    @else
                      <span id="favicon_preview_text" class="fw-bold text-danger">H</span>
                      <img id="favicon_preview" src="" alt="Favicon" class="img-fluid d-none" style="max-height: 100%; object-fit: contain;">
                      <button type="button" class="btn-close position-absolute top-0 end-0 bg-white rounded-circle shadow-sm d-none" aria-label="Close" id="favicon_remove_btn" style="font-size: 0.35rem; padding: 0.15rem; margin: 2px;" onclick="removeImage('website_favicon', 'favicon_preview', 'favicon_preview_text', 'favicon_remove_btn', 'remove_website_favicon')"></button>
                    @endif
                  </div>
                  <div>
                    <input type="file" name="website_favicon" id="website_favicon" class="d-none" accept="image/*" onchange="previewImage(event, 'favicon_preview', 'favicon_preview_text', 'favicon_remove_btn')">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-3" onclick="document.getElementById('website_favicon').click()">Đổi Favicon</button>
                    <input type="hidden" name="remove_website_favicon" id="remove_website_favicon" value="0">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Tab: Cổng thanh toán -->
        <div class="tab-pane fade" id="payment" role="tabpanel">
          <div class="table-card p-4 p-md-5" style="border-radius: 16px;">
            <div class="d-flex justify-content-between align-items-center mb-4">
              <h4 class="card-title fw-bold fs-5 text-dark mb-0">Cổng thanh toán & VNPay</h4>
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="enable_payment" id="enable_payment" value="1" {{ old('enable_payment', $settings['enable_payment'] ?? '0') == '1' ? 'checked' : '' }}>
                <label class="form-check-label fw-medium small" for="enable_payment">Bật thanh toán Online</label>
              </div>
            </div>
            
            <div class="row g-4">
              <div class="col-md-6">
                <label for="vnpay_tmncode" class="form-label fw-semibold text-secondary small">VNPay TmnCode</label>
                <input type="text" name="vnpay_tmncode" id="vnpay_tmncode" class="form-control rounded-3 border-0 bg-light p-3 font-monospace" value="{{ old('vnpay_tmncode', $settings['vnpay_tmncode'] ?? '') }}">
              </div>
              <div class="col-md-6">
                <label for="vnpay_hashsecret" class="form-label fw-semibold text-secondary small">VNPay HashSecret</label>
                <input type="password" name="vnpay_hashsecret" id="vnpay_hashsecret" class="form-control rounded-3 border-0 bg-light p-3 font-monospace" value="{{ old('vnpay_hashsecret', $settings['vnpay_hashsecret'] ?? '') }}">
              </div>
              <div class="col-md-6">
                <label for="vnpay_environment" class="form-label fw-semibold text-secondary small">Môi trường</label>
                <select name="vnpay_environment" id="vnpay_environment" class="form-select rounded-3 border-0 bg-light p-3">
                  <option value="sandbox" {{ old('vnpay_environment', $settings['vnpay_environment'] ?? '') === 'sandbox' ? 'selected' : '' }}>Sandbox (Thử nghiệm)</option>
                  <option value="production" {{ old('vnpay_environment', $settings['vnpay_environment'] ?? '') === 'production' ? 'selected' : '' }}>Production (Thực tế)</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <!-- Tab: SMTP Email -->
        <div class="tab-pane fade" id="smtp" role="tabpanel">
          <div class="table-card p-4 p-md-5" style="border-radius: 16px;">
            <h4 class="card-title fw-bold fs-5 text-dark mb-3">Cấu hình gửi Email tự động (SMTP)</h4>
            <div class="alert alert-info border-0 bg-info bg-opacity-10 d-flex gap-2.5 rounded-3 mb-4">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mt-0.5 text-info"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
              <span class="small text-info-emphasis">Hệ thống sử dụng cấu hình này để tự động gửi thư kích hoạt tài khoản và thư xác nhận giao dịch thanh toán của học viên.</span>
            </div>
            
            <div class="row g-4">
              <div class="col-md-6">
                <label for="smtp_host" class="form-label fw-semibold text-secondary small">SMTP Host</label>
                <input type="text" name="smtp_host" id="smtp_host" class="form-control rounded-3 border-0 bg-light p-3" value="{{ old('smtp_host', $settings['smtp_host'] ?? '') }}">
              </div>
              <div class="col-md-6">
                <label for="smtp_port" class="form-label fw-semibold text-secondary small">SMTP Port</label>
                <input type="text" name="smtp_port" id="smtp_port" class="form-control rounded-3 border-0 bg-light p-3" value="{{ old('smtp_port', $settings['smtp_port'] ?? '') }}">
              </div>
              <div class="col-md-6">
                <label for="smtp_username" class="form-label fw-semibold text-secondary small">SMTP Username</label>
                <input type="text" name="smtp_username" id="smtp_username" class="form-control rounded-3 border-0 bg-light p-3" value="{{ old('smtp_username', $settings['smtp_username'] ?? '') }}">
              </div>
              <div class="col-md-6">
                <label for="smtp_password" class="form-label fw-semibold text-secondary small">SMTP Password</label>
                <input type="password" name="smtp_password" id="smtp_password" class="form-control rounded-3 border-0 bg-light p-3" value="{{ old('smtp_password', $settings['smtp_password'] ?? '') }}">
              </div>
              <div class="col-12 mt-4">
                <button type="button" class="btn btn-outline-primary rounded-3 btn-sm">Gửi Mail Thử Nghiệm</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Tab: Bảo mật & Captcha -->
        <div class="tab-pane fade" id="security" role="tabpanel">
          <div class="table-card p-4 p-md-5" style="border-radius: 16px;">
            <h4 class="card-title fw-bold fs-5 text-dark mb-3">Cấu hình Google reCAPTCHA v3</h4>
            <p class="text-muted small mb-4">Giúp bảo vệ trang đăng nhập, đăng ký và lấy lại mật khẩu khỏi các bot spam có hại.</p>
            
            <div class="row g-4">
              <div class="col-12">
                <label for="recaptcha_site_key" class="form-label fw-semibold text-secondary small">Site Key</label>
                <input type="text" name="recaptcha_site_key" id="recaptcha_site_key" class="form-control rounded-3 border-0 bg-light p-3 font-monospace" placeholder="Nhập Google reCAPTCHA Site Key..." value="{{ old('recaptcha_site_key', $settings['recaptcha_site_key'] ?? '') }}">
              </div>
              <div class="col-12">
                <label for="recaptcha_secret_key" class="form-label fw-semibold text-secondary small">Secret Key</label>
                <input type="password" name="recaptcha_secret_key" id="recaptcha_secret_key" class="form-control rounded-3 border-0 bg-light p-3 font-monospace" placeholder="Nhập Google reCAPTCHA Secret Key..." value="{{ old('recaptcha_secret_key', $settings['recaptcha_secret_key'] ?? '') }}">
              </div>
              <div class="col-12 mt-4">
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" name="require_email_verification" id="require_email_verification" value="1" {{ old('require_email_verification', $settings['require_email_verification'] ?? '0') == '1' ? 'checked' : '' }}>
                  <label class="form-check-label fw-medium small" for="require_email_verification">Bắt buộc xác thực Email khi đăng ký</label>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Tab: Giao diện Khóa học -->
        <div class="tab-pane fade" id="ui-khoahoc" role="tabpanel">
          <div class="table-card p-4 p-md-5" style="border-radius: 16px;">
            <h4 class="card-title fw-bold fs-5 text-dark mb-3">Trang danh sách Khóa học</h4>
            <p class="text-muted small mb-4">Tùy chỉnh nội dung hiển thị ở phần giới thiệu của trang danh sách Khóa học.</p>
            
            <div class="row g-4">
              <div class="col-12">
                <label for="khoahoc_page_title" class="form-label fw-semibold text-secondary small">Tiêu đề chính</label>
                <input type="text" name="khoahoc_page_title" id="khoahoc_page_title" class="form-control rounded-3 border-0 bg-light p-3" value="{{ old('khoahoc_page_title', $settings['khoahoc_page_title'] ?? 'Khám phá lộ trình học tiếng Trung của bạn') }}">
              </div>

              <div class="col-12">
                <label for="khoahoc_page_description" class="form-label fw-semibold text-secondary small">Mô tả phụ</label>
                <textarea name="khoahoc_page_description" id="khoahoc_page_description" class="form-control rounded-3 border-0 bg-light p-3" rows="3">{{ old('khoahoc_page_description', $settings['khoahoc_page_description'] ?? 'Hàng chục khóa học chất lượng cao, từ cơ bản đến nâng cao, giao tiếp thực tế và luyện thi HSK chứng chỉ quốc tế.') }}</textarea>
              </div>
            </div>
          </div>
        </div>

        <!-- Tab: Giao diện Trang chủ -->
        <div class="tab-pane fade" id="ui-trangchu" role="tabpanel">
          <div class="table-card p-4 p-md-5" style="border-radius: 16px;">
            <h4 class="card-title fw-bold fs-5 text-dark mb-3">Giao diện Trang chủ</h4>
            <p class="text-muted small mb-4">Tùy chỉnh nội dung hiển thị ở các phần trên trang chủ (như phần Tính năng cốt lõi).</p>
            
            <h5 class="fw-bold fs-6 text-dark mt-4 mb-3 border-bottom pb-2">Khu vực Tính năng (Vì sao chọn Hányǔ Bàn)</h5>
            <div class="row g-4">
              <div class="col-12">
                <label for="home_features_subtitle" class="form-label fw-semibold text-secondary small">Tiêu đề nhỏ (Subtitle)</label>
                <input type="text" name="home_features_subtitle" id="home_features_subtitle" class="form-control rounded-3 border-0 bg-light p-3" value="{{ old('home_features_subtitle', $settings['home_features_subtitle'] ?? 'VÌ SAO CHỌN HÁNYǓ BÀN') }}">
              </div>

              <div class="col-12">
                <label for="home_features_title" class="form-label fw-semibold text-secondary small">Tiêu đề chính (Title)</label>
                <input type="text" name="home_features_title" id="home_features_title" class="form-control rounded-3 border-0 bg-light p-3" value="{{ old('home_features_title', $settings['home_features_title'] ?? 'Mọi kỹ năng, một nền tảng duy nhất') }}">
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</form>
@endsection

@section('styles')
<style>
  .settings-nav .nav-link {
    color: #4b5563;
    transition: all 0.2s ease;
  }
  .settings-nav .nav-link:hover {
    background-color: #f3f4f6 !important;
    color: #1f2937;
  }
  .settings-nav .nav-link.active {
    background-color: #fef2f2 !important;
    color: var(--admin-primary) !important;
  }
  .settings-nav .nav-link svg {
    transition: transform 0.2s ease;
  }
  .settings-nav .nav-link:hover svg {
    transform: translateX(2px);
  }
</style>

<script>
function previewImage(event, previewId, textId, removeBtnId) {
    const input = event.target;
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const preview = document.getElementById(previewId);
            preview.src = e.target.result;
            preview.classList.remove('d-none');
            
            const textElement = document.getElementById(textId);
            if (textElement) {
                textElement.classList.add('d-none');
            }

            const removeBtn = document.getElementById(removeBtnId);
            if (removeBtn) {
                removeBtn.classList.remove('d-none');
            }
            
            const inputId = input.id;
            const removeInput = document.getElementById('remove_' + inputId);
            if (removeInput) {
                removeInput.value = '0';
            }
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

function removeImage(inputId, previewId, textId, removeBtnId, hiddenInputId) {
    const input = document.getElementById(inputId);
    if (input) {
        input.value = '';
    }
    
    const preview = document.getElementById(previewId);
    if (preview) {
        preview.src = '';
        preview.classList.add('d-none');
    }
    
    const textElement = document.getElementById(textId);
    if (textElement) {
        textElement.classList.remove('d-none');
    }
    
    const removeBtn = document.getElementById(removeBtnId);
    if (removeBtn) {
        removeBtn.classList.add('d-none');
    }
    
    const hiddenInput = document.getElementById(hiddenInputId);
    if (hiddenInput) {
        hiddenInput.value = '1';
    }
}
</script>
@endsection
