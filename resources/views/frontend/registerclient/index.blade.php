<!DOCTYPE html>
<html lang="vi" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Đăng ký — Hányǔ Bàn</title>
<meta name="description" content="Tạo tài khoản Hányǔ Bàn để bắt đầu học tiếng Trung.">
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
  padding: 3.5rem 4rem;
}

@media (max-width: 991px) {
  .glass-card { padding: 3rem 2rem; }
  .hz-3, .hz-1 { display: none; }
}

@keyframes fadeUp {
  to { opacity: 1; transform: translateY(0); }
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

.btn-outline-brand {
  background: transparent;
  color: var(--hb-primary);
  border: 2px solid var(--hb-primary);
  border-radius: 12px;
  padding: 0.75rem 1.5rem;
  font-weight: 600;
  transition: all 0.3s;
}
.btn-outline-brand:hover {
  background: rgba(220, 38, 38, 0.1);
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

/* STEPPER */
.stepper {
  display: flex;
  justify-content: space-between;
  position: relative;
  margin-bottom: 2.5rem;
  padding: 0 1rem;
}
.stepper::before {
  content: '';
  position: absolute;
  top: 18px;
  left: 10%; right: 10%;
  height: 2px;
  background: var(--input-border);
  z-index: 1;
}
.step {
  position: relative;
  z-index: 2;
  text-align: center;
  flex: 1;
}
.step .dot {
  width: 36px; height: 36px;
  border-radius: 50%;
  background: var(--glass-bg);
  border: 2px solid var(--input-border);
  color: var(--hb-text-muted);
  display: flex; align-items: center; justify-content: center;
  margin: 0 auto 0.5rem;
  font-weight: 600;
  transition: all 0.3s;
  backdrop-filter: blur(8px);
}
.step.active .dot, .step.done .dot {
  border-color: var(--hb-primary);
  background: var(--hb-primary);
  color: white;
  box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.2);
}
.step .label {
  font-size: 0.75rem;
  font-weight: 600;
  color: var(--hb-text-muted);
}
.step.active .label, .step.done .label {
  color: var(--hb-primary);
}

.reg-step {
  animation: fadeIn 0.4s ease;
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

/* PASSWORD STRENGTH */
.pw-strength-track {
  height: 6px;
  background: var(--input-border);
  border-radius: 4px;
  margin-top: 0.75rem;
  overflow: hidden;
}
.pw-strength-fill {
  height: 100%;
  width: 0%;
  transition: width 0.3s, background-color 0.3s;
  border-radius: 4px;
}
.pw-strength-label {
  font-size: 0.75rem;
  color: var(--hb-text-muted);
  margin-top: 0.25rem;
}
.pw-rules {
  list-style: none; padding: 0; margin: 0.75rem 0 0;
  display: grid; grid-template-columns: 1fr 1fr; gap: 0.25rem;
  font-size: 0.75rem; color: var(--hb-text-muted);
}
.pw-rules li.ok { color: #10b981; }
.pw-rules li .chk { opacity: 0.3; margin-right: 2px; }
.pw-rules li.ok .chk { opacity: 1; }

/* CHOICE CARDS */
.choice-card {
  border: 2px solid var(--input-border);
  border-radius: 16px;
  padding: 1.25rem 1rem;
  cursor: pointer;
  transition: all 0.3s;
  background: var(--input-bg);
  backdrop-filter: blur(8px);
}
.choice-card input[type="radio"] { display: none; }
.choice-card:hover { border-color: rgba(220, 38, 38, 0.4); transform: translateY(-2px); }
.choice-card.selected {
  border-color: var(--hb-primary);
  background: rgba(220, 38, 38, 0.05);
  box-shadow: 0 4px 12px rgba(220, 38, 38, 0.15);
}
.choice-card .zh {
  font-size: 2rem;
  color: var(--hb-primary);
}

.success-seal {
  width: 80px; height: 80px;
  border-radius: 50%;
  background: rgba(34, 197, 94, 0.1);
  color: #22c55e;
  display: flex; align-items: center; justify-content: center;
  font-size: 2.5rem;
  margin: 0 auto;
  border: 3px solid #22c55e;
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
      <span class="brand-mark zh">汉</span>
      <span class="font-head fw-bold fs-5" style="color:var(--hb-text);">Hányǔ Bàn</span>
    </a>
    <div class="d-flex align-items-center gap-3">
      <button class="theme-toggle shadow-sm" id="themeToggle" aria-label="Chuyển chế độ sáng/tối" type="button">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
      </button>
      <div class="d-none d-sm-flex align-items-center gap-2">
        <span class="small" style="color:var(--hb-text-muted);">Đã có tài khoản?</span>
        <a href="{{ route('login') }}" class="link-brand small">Đăng nhập</a>
      </div>
    </div>
  </div>
</div>

<main id="main" class="container auth-wrap pt-2 pb-5">
  <div class="row justify-content-center w-100">
    <div class="col-lg-8 col-xl-7">
      <div class="glass-card">

        <div class="text-center mb-5">
          <span class="fw-semibold zh d-block mb-1" style="color:var(--hb-primary); letter-spacing:.05em; font-size: 1.1rem;">开始学习</span>
          <h1 class="font-head fw-bold">Tạo tài khoản mới</h1>
          <p class="mb-0 mt-2" style="color:var(--hb-text-muted);">Chỉ mất 1 phút để bắt đầu hành trình học tiếng Trung của bạn.</p>
        </div>

        <!-- Stepper -->
        <div class="stepper" id="stepper">
          <div class="step active" data-step="1">
            <div class="dot">1</div>
            <div class="label">Thông tin</div>
          </div>
          <div class="step" data-step="2">
            <div class="dot">2</div>
            <div class="label">Bảo mật</div>
          </div>
          <div class="step" data-step="3">
            <div class="dot">3</div>
            <div class="label">Mục tiêu</div>
          </div>
          <div class="step" data-step="4">
            <div class="dot zh">好</div>
            <div class="label">Hoàn tất</div>
          </div>
        </div>

        <div id="regAlert" class="alert-brand mb-4 d-none" role="alert"></div>

        <!-- Google Register Button -->
        <div class="row g-3 mb-2 reg-step" data-step="1">
          <div class="col-sm-12">
            <a href="{{ route('auth.google') }}" class="btn-social shadow-sm text-decoration-none">
              <svg width="20" height="20" viewBox="0 0 48 48"><path fill="#FFC107" d="M43.6 20.5H42V20H24v8h11.3C33.9 32.6 29.4 36 24 36c-6.6 0-12-5.4-12-12s5.4-12 12-12c3.1 0 5.8 1.1 8 3l6-6C34.4 6 29.5 4 24 4 12.9 4 4 12.9 4 24s8.9 20 20 20 20-8.9 20-20c0-1.2-.1-2.4-.4-3.5z"/><path fill="#FF3D00" d="M6.3 14.7l6.6 4.8C14.6 15.1 18.9 12 24 12c3.1 0 5.8 1.1 8 3l6-6C34.4 6 29.5 4 24 4c-7.6 0-14.2 4.3-17.7 10.7z"/><path fill="#4CAF50" d="M24 44c5.3 0 10.1-2 13.7-5.4l-6.3-5.3C29.4 35.4 26.8 36 24 36c-5.3 0-9.8-3.4-11.3-8.1l-6.5 5C9.7 39.6 16.3 44 24 44z"/><path fill="#1976D2" d="M43.6 20.5H42V20H24v8h11.3c-.8 2.3-2.3 4.2-4.2 5.6l6.3 5.3C40.9 36.6 44 30.9 44 24c0-1.2-.1-2.4-.4-3.5z"/></svg>
              Đăng ký nhanh với Google
            </a>
          </div>
        </div>
        <div class="divider-text reg-step" data-step="1">hoặc đăng ký bằng email</div>

        <form id="registerForm" novalidate>

          <!-- STEP 1: Basic info -->
          <div class="reg-step" data-step="1">
            <div class="mb-4">
              <label for="fullName" class="form-label small">Họ và tên</label>
              <input type="text" class="form-control form-control-brand" id="fullName" placeholder="Ví dụ: Nguyễn Văn A" required>
              <div class="invalid-feedback text-danger small mt-1 d-none" id="nameError">Vui lòng nhập họ tên của bạn.</div>
            </div>
            <div class="mb-4">
              <label for="regEmail" class="form-label small">Địa chỉ Email</label>
              <input type="email" class="form-control form-control-brand" id="regEmail" placeholder="ví dụ: hanyu@ban.com" required>
              <div class="invalid-feedback text-danger small mt-1 d-none" id="emailError">Vui lòng nhập một địa chỉ email hợp lệ.</div>
            </div>
            <div class="mb-2">
              <label for="regPhone" class="form-label small">Số điện thoại <span style="font-weight:400; opacity:0.7;">(Tùy chọn)</span></label>
              <input type="tel" class="form-control form-control-brand" id="regPhone" placeholder="09xx xxx xxx">
            </div>
          </div>

          <!-- STEP 2: Password -->
          <div class="reg-step d-none" data-step="2">
            <div class="mb-4">
              <label for="regPassword" class="form-label small">Tạo mật khẩu</label>
              <div class="input-group-brand">
                <input type="password" class="form-control form-control-brand w-100" id="regPassword" placeholder="Ít nhất 8 ký tự" style="padding-right:3rem;" required>
                <button type="button" class="toggle-pw" id="togglePw" aria-label="Hiện/ẩn mật khẩu">
                  <svg id="eyeIcon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
              </div>
              <div class="invalid-feedback text-danger small mt-1 d-none" id="pwError">Mật khẩu của bạn chưa đủ mạnh.</div>
              <div class="pw-strength-track"><div class="pw-strength-fill" id="pwFill"></div></div>
              <div class="pw-strength-label" id="pwLabel">Nhập mật khẩu để kiểm tra độ mạnh</div>
              <ul class="pw-rules" id="pwRules">
                <li data-rule="len"><span class="chk">✓</span> Tối thiểu 8 ký tự</li>
                <li data-rule="upper"><span class="chk">✓</span> Có chữ hoa (A-Z)</li>
                <li data-rule="num"><span class="chk">✓</span> Có chữ số (0-9)</li>
                <li data-rule="special"><span class="chk">✓</span> Có ký tự đặc biệt (!@#)</li>
              </ul>
            </div>
            <div class="mb-2">
              <label for="regPasswordConfirm" class="form-label small">Xác nhận mật khẩu</label>
              <input type="password" class="form-control form-control-brand" id="regPasswordConfirm" placeholder="Nhập lại mật khẩu" required>
              <div class="invalid-feedback text-danger small mt-1 d-none" id="pwConfirmError">Mật khẩu xác nhận không khớp.</div>
            </div>
          </div>

          <!-- STEP 3: Learning goal -->
          <div class="reg-step d-none" data-step="3">
            <label class="form-label small d-block mb-3">Trình độ tiếng Trung hiện tại</label>
            <div class="row g-3 mb-4">
              <div class="col-4">
                <label class="choice-card d-block text-center position-relative">
                  <input type="radio" name="level" value="beginner" checked>
                  <span class="zh d-block mb-1">你</span>
                  <span class="small fw-semibold d-block mt-2">Mới bắt đầu</span>
                </label>
              </div>
              <div class="col-4">
                <label class="choice-card d-block text-center position-relative">
                  <input type="radio" name="level" value="intermediate">
                  <span class="zh d-block mb-1">中</span>
                  <span class="small fw-semibold d-block mt-2">Trung cấp</span>
                </label>
              </div>
              <div class="col-4">
                <label class="choice-card d-block text-center position-relative">
                  <input type="radio" name="level" value="advanced">
                  <span class="zh d-block mb-1">高</span>
                  <span class="small fw-semibold d-block mt-2">Nâng cao</span>
                </label>
              </div>
            </div>
            
            <label class="form-label small d-block mb-3">Mục tiêu học tập</label>
            <div class="row g-3">
              <div class="col-sm-6">
                <label class="choice-card d-block position-relative">
                  <input type="radio" name="goal" value="hsk" checked>
                  <span class="small fw-semibold d-block mb-1">Luyện thi HSK</span>
                  <span class="small" style="color:var(--hb-text-muted);">Chuẩn bị cho kỳ thi HSK 1-6</span>
                </label>
              </div>
              <div class="col-sm-6">
                <label class="choice-card d-block position-relative">
                  <input type="radio" name="goal" value="travel">
                  <span class="small fw-semibold d-block mb-1">Giao tiếp / Du lịch</span>
                  <span class="small" style="color:var(--hb-text-muted);">Nói chuyện hằng ngày tự nhiên</span>
                </label>
              </div>
              <div class="col-sm-6">
                <label class="choice-card d-block position-relative">
                  <input type="radio" name="goal" value="business">
                  <span class="small fw-semibold d-block mb-1">Công việc</span>
                  <span class="small" style="color:var(--hb-text-muted);">Tiếng Trung thương mại</span>
                </label>
              </div>
              <div class="col-sm-6">
                <label class="choice-card d-block position-relative">
                  <input type="radio" name="goal" value="study">
                  <span class="small fw-semibold d-block mb-1">Du học</span>
                  <span class="small" style="color:var(--hb-text-muted);">Học thuật, du học Trung Quốc</span>
                </label>
              </div>
            </div>
          </div>

          <!-- STEP 4: Confirm / Terms -->
          <div class="reg-step d-none" data-step="4" id="successStep">
            <div class="text-center py-4">
              <div class="mb-4 d-flex justify-content-center">
                <div class="form-check text-start d-flex align-items-start gap-2" style="max-width:400px;">
                  <input class="form-check-input mt-1" type="checkbox" id="agreeTerms" required>
                  <label class="form-check-label small" for="agreeTerms" style="color:var(--hb-text-muted);">
                    Tôi đồng ý với <a href="#" class="link-brand">Điều khoản dịch vụ</a> và <a href="#" class="link-brand">Chính sách bảo mật</a> của Hányǔ Bàn.
                  </label>
                </div>
              </div>
              <p class="mb-0" style="color:var(--hb-text-muted);">Bạn đã sẵn sàng! Nhấn "Hoàn tất đăng ký" để tạo tài khoản.</p>
            </div>
          </div>

          <!-- Nav buttons -->
          <div class="d-flex justify-content-between align-items-center mt-5 pt-3" style="border-top:1px solid var(--glass-border);">
            <button type="button" class="btn-outline-brand" id="prevBtn" style="visibility:hidden;">Quay lại</button>
            <button type="button" class="btn-brand px-4" id="nextBtn">Tiếp tục</button>
            <button type="submit" class="btn-brand px-4 d-none" id="submitBtn">
              <span id="submitBtnText">Hoàn tất đăng ký</span>
              <span id="submitSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
            </button>
          </div>
        </form>

        <!-- Done screen -->
        <div id="doneScreen" class="text-center d-none py-4">
          <div class="success-seal zh mb-4 shadow-sm" style="background: rgba(34, 197, 94, 0.1); color: #22c55e;">邮</div>
          <h2 class="font-head fw-bold mb-3">Vui lòng kiểm tra Email</h2>
          <p class="mb-4" style="color:var(--hb-text-muted); max-width: 400px; margin: 0 auto;">Chúng tôi đã gửi một email chứa liên kết kích hoạt đến địa chỉ email của bạn. Vui lòng kiểm tra hộp thư (bao gồm cả mục Spam) để kích hoạt tài khoản trước khi đăng nhập.</p>
          <a href="{{ route('login') }}" class="btn-brand mt-2 px-5 text-decoration-none">Đến trang Đăng nhập</a>
        </div>

        <p class="text-center small mt-5 mb-0" id="footerSwitch" style="color:var(--hb-text-muted);">
          Đã có tài khoản? <a href="{{ route('login') }}" class="link-brand">Đăng nhập</a>
        </p>
      </div>
    </div>
  </div>
</main>

<div class="toast-container position-fixed bottom-0 end-0 p-4" style="z-index:2000;">
  <div id="ajaxToast" class="toast align-items-center text-bg-success border-0 shadow-lg" role="status" aria-live="polite" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body fw-medium" id="ajaxToastBody" style="font-family: 'Inter', sans-serif;">Đăng ký thành công!</div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Đóng"></button>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

  /* ---------- Theme toggle ---------- */
  const root = document.documentElement;
  function lsGet(k){ try{ return localStorage.getItem(k); }catch(e){ return null; } }
  function lsSet(k,v){ try{ localStorage.setItem(k,v); }catch(e){} }
  root.setAttribute('data-theme', lsGet('hb-theme') || 'light');
  document.getElementById('themeToggle').addEventListener('click', function () {
    const next = root.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
    root.setAttribute('data-theme', next);
    lsSet('hb-theme', next);
  });

  /* ---------- Multi-step logic ---------- */
  const steps = Array.from(document.querySelectorAll('.reg-step'));
  const stepperItems = Array.from(document.querySelectorAll('.step'));
  const prevBtn = document.getElementById('prevBtn');
  const nextBtn = document.getElementById('nextBtn');
  const submitBtn = document.getElementById('submitBtn');
  const form = document.getElementById('registerForm');
  const regAlert = document.getElementById('regAlert');
  const footerSwitch = document.getElementById('footerSwitch');
  let current = 1;
  const total = steps.length;

  function showStep(n) {
    steps.forEach(s => s.classList.toggle('d-none', parseInt(s.dataset.step) !== n));
    stepperItems.forEach(s => {
      const idx = parseInt(s.dataset.step);
      s.classList.toggle('active', idx === n);
      s.classList.toggle('done', idx < n);
    });
    prevBtn.style.visibility = n === 1 ? 'hidden' : 'visible';
    nextBtn.classList.toggle('d-none', n === total);
    submitBtn.classList.toggle('d-none', n !== total);
    regAlert.classList.add('d-none');
  }

  // Clear errors on input
  ['fullName', 'regEmail', 'regPassword', 'regPasswordConfirm'].forEach(id => {
    const el = document.getElementById(id);
    if(el) {
      el.addEventListener('input', function() {
        this.style.borderColor = '';
        const errEl = document.getElementById(id === 'fullName' ? 'nameError' : (id === 'regEmail' ? 'emailError' : (id === 'regPassword' ? 'pwError' : 'pwConfirmError')));
        if (errEl) errEl.classList.add('d-none');
        regAlert.classList.add('d-none');
      });
    }
  });

  function validateStep(n) {
    let valid = true;
    if (n === 1) {
      const name = document.getElementById('fullName');
      const email = document.getElementById('regEmail');
      const errName = document.getElementById('nameError');
      const errEmail = document.getElementById('emailError');
      
      if (!name.value.trim()) { 
        name.style.borderColor = '#ef4444'; errName.classList.remove('d-none'); errName.classList.add('d-block'); valid = false; 
      } else { name.style.borderColor = ''; errName.classList.add('d-none'); errName.classList.remove('d-block'); }
      
      if (!email.checkValidity()) { 
        email.style.borderColor = '#ef4444'; errEmail.classList.remove('d-none'); errEmail.classList.add('d-block'); valid = false; 
      } else { email.style.borderColor = ''; errEmail.classList.add('d-none'); errEmail.classList.remove('d-block'); }
    }
    if (n === 2) {
      const pw = document.getElementById('regPassword');
      const pwc = document.getElementById('regPasswordConfirm');
      const errPw = document.getElementById('pwError');
      const errPwc = document.getElementById('pwConfirmError');
      const strengthOk = pw.value.length >= 8;
      
      if (!strengthOk) { 
        pw.style.borderColor = '#ef4444'; errPw.classList.remove('d-none'); errPw.classList.add('d-block'); valid = false; 
      } else { pw.style.borderColor = ''; errPw.classList.add('d-none'); errPw.classList.remove('d-block'); }
      
      if (pwc.value !== pw.value || !pwc.value) { 
        pwc.style.borderColor = '#ef4444'; errPwc.classList.remove('d-none'); errPwc.classList.add('d-block'); valid = false; 
      } else { pwc.style.borderColor = ''; errPwc.classList.add('d-none'); errPwc.classList.remove('d-block'); }
    }
    if (n === 4) {
      const agree = document.getElementById('agreeTerms');
      if (!agree.checked) {
        regAlert.textContent = 'Bạn cần đồng ý với Điều khoản dịch vụ để tiếp tục.';
        regAlert.classList.remove('d-none');
        valid = false;
      }
    }
    return valid;
  }

  nextBtn.addEventListener('click', function () {
    if (!validateStep(current)) return;

    if (current === 1) {
      const emailInput = document.getElementById('regEmail');
      const errEmail = document.getElementById('emailError');
      const originalText = nextBtn.innerHTML;
      
      nextBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang kiểm tra...';
      nextBtn.disabled = true;

      fetch('{{ route("check.email") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'Accept': 'application/json'
        },
        body: JSON.stringify({ email: emailInput.value })
      })
      .then(res => res.json())
      .then(data => {
        if (data.exists) {
          emailInput.style.borderColor = '#ef4444';
          errEmail.textContent = 'Địa chỉ email này đã được sử dụng. Vui lòng chọn email khác.';
          errEmail.classList.remove('d-none');
          errEmail.classList.add('d-block');
        } else {
          current++; 
          showStep(current);
        }
      })
      .catch(err => {
        console.error(err);
        current++; 
        showStep(current);
      })
      .finally(() => {
        nextBtn.innerHTML = originalText;
        nextBtn.disabled = false;
      });
      return;
    }

    if (current < total) { current++; showStep(current); }
  });
  prevBtn.addEventListener('click', function () {
    if (current > 1) { current--; showStep(current); }
  });

  /* ---------- Choice cards (step 3) ---------- */
  document.querySelectorAll('.choice-card input').forEach(function (input) {
    input.addEventListener('change', function () {
      const name = input.name;
      document.querySelectorAll('input[name="' + name + '"]').forEach(function (i) {
        i.closest('.choice-card').classList.toggle('selected', i.checked);
      });
    });
    if (input.checked) input.closest('.choice-card').classList.add('selected');
  });

  /* ---------- Password toggle visibility ---------- */
  const pwInput = document.getElementById('regPassword');
  const togglePw = document.getElementById('togglePw');
  const eyeIcon = document.getElementById('eyeIcon');
  if(togglePw && pwInput) {
      togglePw.addEventListener('click', function () {
        const showing = pwInput.type === 'text';
        pwInput.type = showing ? 'password' : 'text';
        eyeIcon.innerHTML = showing
          ? '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>'
          : '<path d="M17.94 17.94A10.94 10.94 0 0112 19c-7 0-11-7-11-7a21.6 21.6 0 015.06-6.06M9.9 4.24A10.94 10.94 0 0112 4c7 0 11 7 11 7a21.6 21.6 0 01-2.61 3.65M14.12 14.12a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
      });
  }

  /* ---------- Password strength meter ---------- */
  const pwFill = document.getElementById('pwFill');
  const pwLabel = document.getElementById('pwLabel');
  const pwRules = document.querySelectorAll('#pwRules li');
  const strengthColors = ['#EF4444', '#F59E0B', '#F59E0B', '#22C55E'];
  const strengthLabels = ['Yếu', 'Trung bình', 'Khá mạnh', 'Mạnh'];

  if(pwInput) {
      pwInput.addEventListener('input', function () {
        const val = pwInput.value;
        const checks = {
          len: val.length >= 8,
          upper: /[A-Z]/.test(val),
          num: /[0-9]/.test(val),
          special: /[!@#$%^&*(),.?":{}|<>]/.test(val)
        };
        pwRules.forEach(function (li) {
          li.classList.toggle('ok', checks[li.dataset.rule]);
        });
        const score = Object.values(checks).filter(Boolean).length;
        const pct = val.length === 0 ? 0 : Math.max(15, (score / 4) * 100);
        pwFill.style.width = pct + '%';
        pwFill.style.background = strengthColors[Math.max(score - 1, 0)];
        pwLabel.textContent = val.length === 0 ? 'Nhập mật khẩu để kiểm tra độ mạnh' : 'Độ mạnh: ' + strengthLabels[Math.max(score - 1, 0)];
      });
  }

  /* ---------- Submit (Ajax) ---------- */
  const submitBtnText = document.getElementById('submitBtnText');
  const submitSpinner = document.getElementById('submitSpinner');
  const doneScreen = document.getElementById('doneScreen');
  const toastEl = document.getElementById('ajaxToast');
  const toastBody = document.getElementById('ajaxToastBody');
  const toast = new bootstrap.Toast(toastEl, { delay: 3500 });

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    if (!validateStep(4)) return;

    submitBtn.disabled = true;
    submitBtnText.textContent = 'Đang xử lý...';
    submitSpinner.classList.remove('d-none');

    const payload = {
      fullName: document.getElementById('fullName').value,
      email: document.getElementById('regEmail').value,
      phone: document.getElementById('regPhone').value,
      password: document.getElementById('regPassword').value,
      level: (document.querySelector('input[name="level"]:checked') || {}).value,
      goal: (document.querySelector('input[name="goal"]:checked') || {}).value
    };

    // AJAX registration call
    fetch('{{ route("register") }}', {
      method: 'POST',
      headers: { 
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Accept': 'application/json'
      },
      body: JSON.stringify(payload)
    })
      .then(function (res) {
        return res.json().then(data => {
            if (!res.ok) {
                // If it is a validation error (422), handle it
                if(res.status === 422) {
                    let firstError = Object.values(data.errors)[0][0];
                    throw new Error(firstError);
                }
                throw new Error(data.message || 'Đăng ký thất bại');
            }
            return data;
        });
      })
      .then(function (data) {
        document.getElementById('stepper').classList.add('d-none');
        form.classList.add('d-none');
        footerSwitch.classList.add('d-none');
        doneScreen.classList.remove('d-none');
        toastEl.classList.remove('text-bg-danger');
        toastEl.classList.add('text-bg-success');
        toastBody.textContent = data.message || 'Tài khoản đã được tạo thành công!';
        toast.show();
      })
      .catch(function (error) {
        regAlert.textContent = error.message || 'Có lỗi xảy ra trong quá trình đăng ký. Vui lòng thử lại.';
        regAlert.classList.remove('d-none');
      })
      .finally(function () {
        submitBtn.disabled = false;
        submitBtnText.textContent = 'Hoàn tất đăng ký';
        submitSpinner.classList.add('d-none');
      });
  });

  showStep(1);
});
</script>
</body>
</html>