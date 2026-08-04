@extends('frontend.layouts.main')

@section('title', ($title ?? 'Kết quả xác thực') . ' - Hányǔ Bàn')

@push('styles')
<style>
  /* Reuse auth-wrap from frontend.css or custom styles */
  .auth-wrap {
    min-height: calc(100vh - 78px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem 0;
    position: relative;
  }
  .verify-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    box-shadow: 0 20px 50px -25px rgba(17,24,39,.25);
    padding: 3rem 2rem;
    max-width: 500px;
    width: 100%;
    text-align: center;
    animation: fadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
  }
  .success-seal {
    width: 88px; height: 88px; border-radius: 20px;
    background: var(--primary); color: #fff;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1.5rem; font-family: var(--font-zh);
    font-weight: 700; font-size: 2.2rem;
    box-shadow: 0 12px 30px -12px rgba(220,38,38,.6);
    transform: rotate(-4deg);
  }
  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
  }
</style>
@endpush

@section('content')
<div class="auth-wrap">
  <div class="container d-flex justify-content-center">
    <div class="verify-card">
      @php $isSuccess = ($status ?? 'success') === 'success'; @endphp
      <div class="success-seal zh" style="background: {{ $isSuccess ? 'rgba(34, 197, 94, 0.1)' : 'rgba(239, 68, 68, 0.1)' }}; color: {{ $isSuccess ? '#22c55e' : '#ef4444' }};">
        {{ $isSuccess ? '验' : '!' }}
      </div>
      <h2 class="font-head fw-bold mb-3">{{ $title ?? 'Xác thực thành công!' }}</h2>
      <p class="mb-4" style="color:var(--text-muted); font-size: 1.05rem;">
        {{ $message ?? 'Tài khoản của bạn đã được kích hoạt thành công.' }}
      </p>
      <a href="{{ route('login') }}" class="btn-brand d-inline-block px-5 py-2 text-decoration-none">
        Quay lại đăng nhập
      </a>
    </div>
  </div>
</div>
@endsection
