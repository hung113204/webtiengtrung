<!doctype html>
<html lang="vi" data-theme="light">
  <head>
    @include('frontend.parts.dashboard.head')
  </head>
  <body>
    <!-- Main Learning Layout -->
    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
    @include('components.idle-timeout')
  </body>
</html>
