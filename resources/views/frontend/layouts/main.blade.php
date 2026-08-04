<!DOCTYPE html>
<html lang="vi" data-theme="light">
<head>
@include('frontend.parts.head')
</head>
<body>

<a href="#main" class="skip-link">Bỏ qua đến nội dung chính</a>

@include('frontend.parts.navbar')

<main id="main" style="padding-top: 83px;">
    @yield('content')
</main>

@include('frontend.parts.footer')

<!-- Toast container for AJAX feedback -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index:2000;">
  <div id="ajaxToast" class="toast align-items-center border-0" role="status" aria-live="polite" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body" id="ajaxToastBody">Đăng ký thành công!</div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Đóng"></button>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/hanzi-writer@3.5/dist/hanzi-writer.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('frontend/asset/js/frontend.js') }}"></script>
@stack('scripts')
@include('components.idle-timeout')
</body>
</html>
