<!doctype html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Đăng nhập — Hányǔ Admin</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="{{ asset('backend/asset/css/style.css') }}" rel="stylesheet" />
    <style>
      /* Specialized styles for login page */
      .login-page {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--admin-bg) 0%, rgba(220,38,38,0.05) 100%);
      }
      [data-theme="dark"] .login-page {
        background: linear-gradient(135deg, var(--admin-bg) 0%, rgba(220,38,38,0.1) 100%);
      }
      
      .login-card {
        width: 100%;
        max-width: 440px;
        background: var(--admin-card);
        border: 1px solid var(--admin-border);
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        padding: 2.5rem;
        position: relative;
        overflow: hidden;
      }
      
      [data-theme="dark"] .login-card {
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
      }

      /* Decorative top border for brand identity */
      .login-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: var(--admin-primary);
      }

      .login-logo {
        text-align: center;
        margin-bottom: 2rem;
      }

      .login-logo .brand-mark {
        width: 48px;
        height: 48px;
        font-size: 1.5rem;
        margin: 0 auto 12px;
      }
      
      .login-title {
        font-weight: 700;
        font-size: 1.5rem;
        color: var(--admin-text);
        margin-bottom: 0.5rem;
      }

      .theme-toggle-btn {
        position: absolute;
        top: 1.5rem;
        right: 1.5rem;
      }
    </style>
  </head>
  <body>
    
    <div class="login-page">
      <div class="login-card animate-fade-in">
        
        <!-- Theme Toggle -->
        <button id="themeToggle" class="btn border-0 text-muted p-2 theme-toggle-btn" title="Đổi giao diện Sáng/Tối">
          <svg class="sun-icon d-none" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
          <svg class="moon-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
        </button>

        <!-- Brand / Header -->
        <div class="login-logo">
          <div class="brand-mark">汉</div>
          <h1 class="login-title">Hányǔ Admin</h1>
          <p class="text-muted small">Hệ thống quản trị học tập (LMS)</p>
        </div>

        <!-- Login Form -->
        <form id="loginForm" action="{{ route('admin.login') }}" method="POST">
          @csrf
          <div class="mb-3">
            <label class="form-label fw-medium">Tài khoản / Email</label>
            <div class="input-group">
              <span class="input-group-text bg-white text-muted border-end-0">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
              </span>
              <input type="text" class="form-control border-start-0 ps-0" name="email" placeholder="admin@hanyu.edu.vn" required>
            </div>
          </div>
          
          <div class="mb-3">
            <label class="form-label fw-medium mb-1">Mật khẩu</label>
            <div class="input-group">
              <span class="input-group-text bg-white text-muted border-end-0">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
              </span>
              <input type="password" class="form-control border-start-0 ps-0 border-end-0" name="mat_khau" placeholder="••••••••" required id="passwordInput">
              <span class="input-group-text bg-white text-muted cursor-pointer border-start-0" id="togglePassword" style="cursor: pointer;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
              </span>
            </div>
          </div>

          <div class="mb-4 d-flex justify-content-between align-items-center">
            <div class="form-check mb-0">
              <input type="checkbox" class="form-check-input" name="remember" id="rememberMe">
              <label class="form-check-label text-muted small" for="rememberMe">Ghi nhớ đăng nhập</label>
            </div>
            <a href="#" class="small text-decoration-none" style="color: var(--admin-primary);">Quên mật khẩu?</a>
          </div>

          <button type="submit" class="btn btn-primary w-100 py-2 fw-medium shadow-sm mb-3 position-relative" id="loginBtn" style="background: var(--admin-primary); border: none; border-radius: 8px; transition: all 0.3s;">
            <span class="btn-text">Đăng nhập hệ thống</span>
            <div class="spinner-border spinner-border-sm text-light position-absolute top-50 start-50 translate-middle d-none" id="loginSpinner" role="status"></div>
          </button>
          
        </form>

        <div class="text-center mt-4 pt-3 border-top border-light">
          <p class="text-muted small mb-0">
            &copy; 2026 Hányǔ Bàn. All rights reserved.
          </p>
        </div>

      </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('backend/js/admin.js') }}"></script>
    <script src="{{ asset('backend/js/login.js') }}"></script>
  </body>
</html>
