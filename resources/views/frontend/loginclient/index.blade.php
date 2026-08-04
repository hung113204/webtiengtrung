<!DOCTYPE html>
<html lang="vi" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Đăng nhập — Hányǔ Bàn</title>
<meta name="description" content="Đăng nhập vào Hányǔ Bàn để tiếp tục học tiếng Trung.">
<meta name="csrf-token" content="{{ csrf_token() }}">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
@php
    $faviconUrl = \App\Models\CauHinh::getByKey('website_favicon');
@endphp
@if($faviconUrl)
    <link rel="icon" type="image/png" href="{{ Storage::url($faviconUrl) }}">
    <link rel="shortcut icon" type="image/png" href="{{ Storage::url($faviconUrl) }}">
@endif
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&family=Inter:wght@400;500;600&family=Noto+Sans+SC:wght@400;600;700;900&display=swap" rel="stylesheet">
<script src="/asset/js/loading.js"></script>
<style>
/* PREMIUM DESIGN CSS */
:root {
  --hb-primary: #dc2626;
  --hb-primary-hover: #b91c1c;
  --hb-bg: #f8fafc;
  --hb-text: #0f172a;
  --hb-text-muted: #64748b;
  --hb-border: #e2e8f0;
  
  --glass-bg: rgba(255, 255, 255, 0.75);
  --glass-border: rgba(255, 255, 255, 0.5);
  --glass-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.08);
  
  --input-bg: rgba(255, 255, 255, 0.8);
  --input-border: rgba(226, 232, 240, 0.8);
  --input-focus-shadow: 0 0 0 4px rgba(220, 38, 38, 0.15);
}

[data-theme="dark"] {
  --hb-bg: #020617;
  --hb-text: #f8fafc;
  --hb-text-muted: #94a3b8;
  --hb-border: #1e293b;
  
  --glass-bg: rgba(15, 23, 42, 0.65);
  --glass-border: rgba(255, 255, 255, 0.08);
  --glass-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.5);
  
  --input-bg: rgba(30, 41, 59, 0.7);
  --input-border: rgba(51, 65, 85, 0.6);
  --input-focus-shadow: 0 0 0 4px rgba(220, 38, 38, 0.25);
}

body {
  font-family: 'Inter', sans-serif;
  background-color: var(--hb-bg);
  color: var(--hb-text);
  margin: 0;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  transition: background-color 0.4s ease, color 0.4s ease;
  overflow-x: hidden;
}

h1, h2, h3, h4, h5, h6, .font-head {
  font-family: 'Poppins', sans-serif;
}

.zh {
  font-family: 'Noto Sans SC', sans-serif;
}

/* ANIMATED MESH GRADIENT */
.mesh-bg {
  position: fixed;
  top: 0; left: 0; width: 100vw; height: 100vh;
  z-index: -1;
  overflow: hidden;
}
.blob {
  position: absolute;
  filter: blur(90px);
  border-radius: 50%;
  opacity: 0.6;
  animation: floatBlob 20s infinite alternate cubic-bezier(0.4, 0, 0.2, 1);
}
.blob-1 {
  background: #ff9a9e; width: 40vw; height: 40vw; top: -10%; left: -10%;
}
.blob-2 {
  background: #fecfef; width: 50vw; height: 50vw; bottom: -20%; right: -10%; animation-delay: -5s;
}
.blob-3 {
  background: #a18cd1; width: 35vw; height: 35vw; top: 40%; left: 30%; animation-delay: -10s;
}

[data-theme="dark"] .blob-1 { background: #5c1825; }
[data-theme="dark"] .blob-2 { background: #2b1131; }
[data-theme="dark"] .blob-3 { background: #1a1e3a; }

@keyframes floatBlob {
  0% { transform: translate(0, 0) scale(1); }
  50% { transform: translate(5%, 10%) scale(1.1); }
  100% { transform: translate(-5%, 5%) scale(0.9); }
}

/* FLOATING HANZI */
.floating-hanzi {
  position: fixed;
  color: var(--hb-primary);
  opacity: 0.05;
  font-weight: 900;
  z-index: 0;
  user-select: none;
  pointer-events: none;
  font-family: 'Noto Sans SC', sans-serif;
}
[data-theme="dark"] .floating-hanzi { opacity: 0.08; }
.hz-1 { font-size: 250px; top: -5%; left: 2%; animation: floatY 12s ease-in-out infinite; }
.hz-2 { font-size: 180px; bottom: 5%; left: 15%; animation: floatY 15s ease-in-out infinite 2s; }
.hz-3 { font-size: 300px; top: 20%; right: -5%; animation: floatY 18s ease-in-out infinite 5s; }
.hz-4 { font-size: 150px; bottom: 15%; right: 25%; animation: floatY 14s ease-in-out infinite 1s; }

@keyframes floatY {
  0%, 100% { transform: translateY(0) rotate(0deg); }
  50% { transform: translateY(-30px) rotate(5deg); }
}

/* NAVIGATION */
.mini-nav {
  padding: 1.5rem 0;
  position: relative;
  z-index: 20;
}
.brand-mark {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 36px;
  height: 36px;
  background: var(--hb-primary);
  color: white;
  border-radius: 8px;
  font-weight: 700;
  font-size: 1.25rem;
  box-shadow: 0 4px 14px rgba(220, 38, 38, 0.4);
}
.theme-toggle {
  background: var(--glass-bg);
  border: 1px solid var(--glass-border);
  color: var(--hb-text);
  width: 40px; height: 40px;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  cursor: pointer;
  transition: all 0.3s ease;
  backdrop-filter: blur(8px);
}
.theme-toggle:hover {
  transform: scale(1.05);
  background: var(--hb-border);
}

/* GLASS CARD */
.auth-wrap {
  flex-grow: 1;
  display: flex;
  align-items: center;
  position: relative;
  z-index: 10;
  padding: 2rem 0;
}
.glass-card {
  background: var(--glass-bg);
  backdrop-filter: blur(24px);
  -webkit-backdrop-filter: blur(24px);
  border: 1px solid var(--glass-border);
  border-radius: 24px;
  box-shadow: var(--glass-shadow);
  overflow: hidden;
  opacity: 0;
  transform: translateY(30px);
  animation: fadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

@keyframes fadeUp {
  to { opacity: 1; transform: translateY(0); }
}

.illustration-side {
  background: linear-gradient(145deg, var(--hb-primary) 0%, #991b1b 100%);
  color: white;
  padding: 4rem 3rem;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  position: relative;
  overflow: hidden;
}
.illustration-side::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0; bottom: 0;
  background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI4IiBoZWlnaHQ9IjgiPgo8cmVjdCB3aWR0aD0iOCIgaGVpZ2h0PSI4IiBmaWxsPSIjZmZmIiBmaWxsLW9wYWNpdHk9IjAuMDUiLz4KPC9zdmc+') repeat;
  opacity: 0.3;
}

.grid-deco {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 15px;
  max-width: 280px;
  position: relative;
  z-index: 2;
}
.grid-deco .cell {
  aspect-ratio: 1;
  background: rgba(255,255,255,0.1);
  border: 1px solid rgba(255,255,255,0.2);
  border-radius: 12px;
  display: flex; align-items: center; justify-content: center;
  font-size: 2rem; font-weight: 700;
  font-family: 'Noto Sans SC', sans-serif;
  transition: all 0.5s ease;
}
.grid-deco .cell.filled {
  background: white;
  color: var(--hb-primary);
  box-shadow: 0 10px 25px rgba(0,0,0,0.2);
  transform: translateY(-5px);
}

.form-side {
  padding: 4rem 3.5rem;
}

/* FORM CONTROLS */
.form-label {
  color: var(--hb-text);
  font-weight: 600;
}
.form-control-brand {
  background: var(--input-bg);
  border: 1px solid var(--input-border);
  color: var(--hb-text);
  border-radius: 12px;
  padding: 0.75rem 1rem;
  transition: all 0.3s ease;
  backdrop-filter: blur(8px);
}
.form-control-brand:focus {
  background: var(--hb-bg);
  border-color: var(--hb-primary);
  box-shadow: var(--input-focus-shadow);
  color: var(--hb-text);
  outline: none;
}
.form-control-brand::placeholder {
  color: var(--hb-text-muted);
}

.btn-brand {
  background: var(--hb-primary);
  color: white;
  border: none;
  border-radius: 12px;
  padding: 0.875rem 1.5rem;
  font-weight: 600;
  font-family: 'Poppins', sans-serif;
  transition: all 0.3s ease;
  box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
  text-decoration: none;
}
.btn-brand:hover {
  background: var(--hb-primary-hover);
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(220, 38, 38, 0.4);
  color: white;
  text-decoration: none;
}

.btn-social {
  background: var(--glass-bg);
  border: 1px solid var(--glass-border);
  color: var(--hb-text);
  border-radius: 12px;
  padding: 0.75rem 1rem;
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  font-weight: 500;
  transition: all 0.3s ease;
  backdrop-filter: blur(8px);
}
.btn-social:hover {
  background: var(--input-border);
  transform: translateY(-2px);
}

.divider-text {
  display: flex;
  align-items: center;
  text-align: center;
  color: var(--hb-text-muted);
  font-size: 0.875rem;
  margin: 1.5rem 0;
}
.divider-text::before, .divider-text::after {
  content: '';
  flex: 1;
  border-bottom: 1px solid var(--glass-border);
}
.divider-text:not(:empty)::before { margin-right: .5em; }
.divider-text:not(:empty)::after { margin-left: .5em; }

.link-brand {
  color: var(--hb-primary);
  text-decoration: none;
  font-weight: 600;
  transition: color 0.2s;
}
.link-brand:hover {
  color: var(--hb-primary-hover);
  text-decoration: underline;
}

.input-group-brand {
  position: relative;
}
.toggle-pw {
  position: absolute;
  right: 1rem;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  color: var(--hb-text-muted);
  cursor: pointer;
  padding: 0;
}
.toggle-pw:hover { color: var(--hb-text); }

/* Custom Checkbox */
.form-check-input:checked {
  background-color: var(--hb-primary);
  border-color: var(--hb-primary);
}

.alert-brand {
  background: rgba(220, 38, 38, 0.1);
  border: 1px solid rgba(220, 38, 38, 0.2);
  color: #ef4444;
  border-radius: 12px;
  padding: 1rem;
  font-size: 0.875rem;
}

@media (max-width: 991px) {
  .form-side { padding: 3rem 2rem; }
  .hz-3, .hz-1 { display: none; }
}
</style>
</head>
<body>

<!-- MESH GRADIENT & FLOATING HANZI -->
<div class="mesh-bg">
  <div class="blob blob-1"></div>
  <div class="blob blob-2"></div>
  <div class="blob blob-3"></div>
</div>
<div class="floating-hanzi hz-1">汉</div>
<div class="floating-hanzi hz-2">语</div>
<div class="floating-hanzi hz-3">学</div>
<div class="floating-hanzi hz-4">习</div>

<div class="container mini-nav">
  <div class="d-flex justify-content-between align-items-center">
    <a href="/" class="d-flex align-items-center gap-2 text-decoration-none">
      @php
          $logoUrl = \App\Models\CauHinh::getByKey('website_logo');
          $websiteName = \App\Models\CauHinh::getByKey('website_name', 'Hányǔ Bàn');
      @endphp
      @if($logoUrl)
          <img src="{{ Storage::url($logoUrl) }}" alt="{{ $websiteName }}" style="height: 44px; object-fit: contain; border-radius: 8px;">
      @else
          <span class="brand-mark zh">汉</span>
      @endif
      <span class="font-head fw-bold fs-5" style="color:var(--hb-text);">{{ $websiteName }}</span>
    </a>
    <div class="d-flex align-items-center gap-3">
      <button class="theme-toggle shadow-sm" id="themeToggle" aria-label="Chuyển chế độ sáng/tối" type="button">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
      </button>
      <div class="d-none d-sm-flex align-items-center gap-2">
        <span class="small" style="color:var(--hb-text-muted);">Chưa có tài khoản?</span>
        <a href="{{ route('register.form') }}" class="link-brand small">Đăng ký ngay</a>
      </div>
    </div>
  </div>
</div>

<main id="main" class="container auth-wrap pb-5">
  <div class="row justify-content-center w-100">
    <div class="col-xl-10">
      <div class="glass-card row g-0 p-0">
        <!-- Illustration side -->
        <div class="col-lg-5 d-none d-lg-flex">
          <div class="illustration-side w-100">
            <div style="position: relative; z-index: 2;">
              <span class="badge rounded-pill mb-4 px-3 py-2" style="background:rgba(255,255,255,.2); font-weight:600; font-size:.75rem; letter-spacing: 1px;">HÁNYǓ BÀN</span>
              <div class="grid-deco mb-4">
                <div class="cell filled">你</div>
                <div class="cell">好</div>
                <div class="cell">吗</div>
                <div class="cell">学</div>
                <div class="cell filled">中</div>
                <div class="cell">文</div>
              </div>
            </div>
            <div style="position: relative; z-index: 2;">
              <h3 class="font-head fw-bold mb-2">Đăng nhập để tiếp tục</h3>
              <p class="mb-0 text-white-50" style="font-size: 0.95rem;">
                "Mỗi ngày học một chữ, một năm thông thạo một ngôn ngữ."
                <span class="d-block mt-1 fst-italic" style="opacity: 0.7;">Měitiān xué yīgè zì, yī nián jīngtōng yī zhǒng yǔyán.</span>
              </p>
            </div>
          </div>
        </div>

        <!-- Form side -->
        <div class="col-lg-7">
          <div class="form-side">
            <span class="fw-semibold zh" style="color:var(--hb-primary); letter-spacing:.05em; font-size: 1.1rem;">欢迎回来</span>
            <h1 class="font-head mt-1 mb-2 fw-bold">Chào mừng trở lại!</h1>
            <p class="mb-4" style="color:var(--hb-text-muted);">Đăng nhập tài khoản để tiếp tục hành trình chinh phục tiếng Trung.</p>

            <div id="loginAlert" class="alert-brand mb-4 d-none" role="alert"></div>

            <div class="row g-3 mb-2">
              <div class="col-sm-6">
                <button type="button" class="btn-social shadow-sm" id="googleLoginBtn">
                  <svg width="20" height="20" viewBox="0 0 48 48"><path fill="#FFC107" d="M43.6 20.5H42V20H24v8h11.3C33.9 32.6 29.4 36 24 36c-6.6 0-12-5.4-12-12s5.4-12 12-12c3.1 0 5.8 1.1 8 3l6-6C34.4 6 29.5 4 24 4 12.9 4 4 12.9 4 24s8.9 20 20 20 20-8.9 20-20c0-1.2-.1-2.4-.4-3.5z"/><path fill="#FF3D00" d="M6.3 14.7l6.6 4.8C14.6 15.1 18.9 12 24 12c3.1 0 5.8 1.1 8 3l6-6C34.4 6 29.5 4 24 4c-7.6 0-14.2 4.3-17.7 10.7z"/><path fill="#4CAF50" d="M24 44c5.3 0 10.1-2 13.7-5.4l-6.3-5.3C29.4 35.4 26.8 36 24 36c-5.3 0-9.8-3.4-11.3-8.1l-6.5 5C9.7 39.6 16.3 44 24 44z"/><path fill="#1976D2" d="M43.6 20.5H42V20H24v8h11.3c-.8 2.3-2.3 4.2-4.2 5.6l6.3 5.3C40.9 36.6 44 30.9 44 24c0-1.2-.1-2.4-.4-3.5z"/></svg>
                  Tiếp tục với Google
                </button>
              </div>
              <div class="col-sm-6">
                <button type="button" class="btn-social shadow-sm" id="facebookLoginBtn">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="#1877F2"><path d="M22 12a10 10 0 10-11.6 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.4h-1.3c-1.2 0-1.6.8-1.6 1.6V12h2.8l-.4 2.9h-2.4v7A10 10 0 0022 12z"/></svg>
                  Tiếp tục với Facebook
                </button>
              </div>
            </div>

            <div class="divider-text">hoặc đăng nhập bằng email</div>

            <form id="loginForm" novalidate>
              <div class="mb-4">
                <label for="loginEmail" class="form-label small">Tên đăng nhập hoặc Email</label>
                <input type="text" class="form-control form-control-brand" id="loginEmail" placeholder="ví dụ: hanyu@ban.com hoặc username" required>
                <div class="text-danger small mt-1 d-none" id="emailError"></div>
              </div>
              
              <div class="mb-3">
                <label for="loginPassword" class="form-label small mb-1">Mật khẩu</label>
                <div class="input-group-brand">
                  <input type="password" class="form-control form-control-brand w-100" id="loginPassword" placeholder="Nhập mật khẩu của bạn" required minlength="6" style="padding-right:3rem;">
                  <button type="button" class="toggle-pw" id="togglePw" aria-label="Hiện/ẩn mật khẩu">
                    <svg id="eyeIcon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                  </button>
                </div>
                <div class="text-danger small mt-1 d-none" id="pwError"></div>
              </div>

              <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
                <div class="form-check mb-0">
                  <input class="form-check-input" type="checkbox" id="rememberMe">
                  <label class="form-check-label small" for="rememberMe" style="color:var(--hb-text-muted);">
                    Ghi nhớ đăng nhập
                  </label>
                </div>
                <a href="forgot-password.html" class="link-brand small" style="font-weight: 500;">Quên mật khẩu?</a>
              </div>

              <button type="submit" class="btn-brand w-100 d-flex justify-content-center align-items-center gap-2" id="loginSubmitBtn">
                <span id="loginBtnText">Đăng nhập</span>
                <span id="loginSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
              </button>
            </form>

            <div class="d-sm-none text-center mt-4">
              <span class="small" style="color:var(--hb-text-muted);">Chưa có tài khoản?</span>
              <a href="{{ route('register.form') }}" class="link-brand small">Đăng ký ngay</a>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<div class="toast-container position-fixed bottom-0 end-0 p-4" style="z-index:2000;">
  <div id="ajaxToast" class="toast align-items-center text-bg-success border-0 shadow-lg" role="status" aria-live="polite" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body fw-medium" id="ajaxToastBody" style="font-family: 'Inter', sans-serif;">Đăng nhập thành công!</div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Đóng"></button>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

  /* ---------- Theme toggle ---------- */
  const root = document.documentElement;
  const themeToggle = document.getElementById('themeToggle');
  function lsGet(k){ try{ return localStorage.getItem(k); }catch(e){ return null; } }
  function lsSet(k,v){ try{ localStorage.setItem(k,v); }catch(e){} }
  root.setAttribute('data-theme', lsGet('hb-theme') || 'light');
  themeToggle.addEventListener('click', function () {
    const next = root.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
    root.setAttribute('data-theme', next);
    lsSet('hb-theme', next);
  });

  /* ---------- Password visibility toggle ---------- */
  const pwInput = document.getElementById('loginPassword');
  const togglePw = document.getElementById('togglePw');
  const eyeIcon = document.getElementById('eyeIcon');
  togglePw.addEventListener('click', function () {
    const showing = pwInput.type === 'text';
    pwInput.type = showing ? 'password' : 'text';
    eyeIcon.innerHTML = showing
      ? '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>'
      : '<path d="M17.94 17.94A10.94 10.94 0 0112 19c-7 0-11-7-11-7a21.6 21.6 0 015.06-6.06M9.9 4.24A10.94 10.94 0 0112 4c7 0 11 7 11 7a21.6 21.6 0 01-2.61 3.65M14.12 14.12a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
  });

  /* ---------- Login form + Ajax ---------- */
  const form = document.getElementById('loginForm');
  const emailInput = document.getElementById('loginEmail');
  const passwordInput = document.getElementById('loginPassword');
  const emailErr = document.getElementById('emailError');
  const pwErr = document.getElementById('pwError');
  const submitBtn = document.getElementById('loginSubmitBtn');
  const btnText = document.getElementById('loginBtnText');
  const spinner = document.getElementById('loginSpinner');
  const alertBox = document.getElementById('loginAlert');

  emailInput.addEventListener('input', function() {
    emailInput.style.borderColor = '';
    emailErr.classList.add('d-none');
    alertBox.classList.add('d-none');
  });

  passwordInput.addEventListener('input', function() {
    passwordInput.style.borderColor = '';
    pwErr.classList.add('d-none');
    alertBox.classList.add('d-none');
  });

  const toastEl = document.getElementById('ajaxToast');
  const toastBody = document.getElementById('ajaxToastBody');
  const toast = new bootstrap.Toast(toastEl, { delay: 3500 });

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    alertBox.classList.add('d-none');

    submitBtn.disabled = true;
    btnText.textContent = 'Đang đăng nhập...';
    spinner.classList.remove('d-none');

    // AJAX login request
    fetch('{{ route("login") }}', {
      method: 'POST',
      headers: { 
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        email: emailInput.value,
        password: passwordInput.value,
        remember: document.getElementById('rememberMe').checked
      })
    })
      .then(function (res) {
        return res.json().then(data => {
            if (!res.ok) {
                if (res.status === 422 && data.errors) {
                    let firstError = '';
                    if (data.errors.email) {
                        emailErr.textContent = data.errors.email[0];
                        emailErr.classList.remove('d-none');
                        emailInput.style.borderColor = '#ef4444';
                        firstError = data.errors.email[0];
                    }
                    if (data.errors.password) {
                        pwErr.textContent = data.errors.password[0];
                        pwErr.classList.remove('d-none');
                        passwordInput.style.borderColor = '#ef4444';
                        if(!firstError) firstError = data.errors.password[0];
                    }
                    throw new Error(firstError || 'Vui lòng kiểm tra lại thông tin.');
                }
                throw new Error(data.message || 'Đăng nhập thất bại.');
            }
            return data;
        });
      })
      .then(function (data) {
        toastEl.classList.remove('text-bg-danger');
        toastEl.classList.add('text-bg-success');
        toastBody.textContent = data.message || 'Đăng nhập thành công! Đang chuyển đến bảng điều khiển...';
        toast.show();
        
        setTimeout(() => {
          window.location.href = data.redirect || '/';
        }, 1500);
      })
      .catch(function (error) {
        alertBox.textContent = error.message || 'Email hoặc mật khẩu không chính xác. Vui lòng thử lại.';
        alertBox.classList.remove('d-none');
      })
      .finally(function () {
        submitBtn.disabled = false;
        btnText.textContent = 'Đăng nhập';
        spinner.classList.add('d-none');
      });
  });

  document.getElementById('googleLoginBtn').addEventListener('click', function () {
    toastEl.classList.remove('text-bg-danger');
    toastEl.classList.add('text-bg-success');
    toastBody.textContent = 'Đang chuyển hướng đến trang xác thực Google...';
    toast.show();
    setTimeout(() => {
        window.location.href = '{{ route("auth.google") }}';
    }, 1000);
  });

  document.getElementById('facebookLoginBtn').addEventListener('click', function () {
    toastEl.classList.remove('text-bg-danger');
    toastEl.classList.add('text-bg-success');
    toastBody.textContent = 'Tính năng đang phát triển...';
    toast.show();
  });

});
</script>
</body>
</html>