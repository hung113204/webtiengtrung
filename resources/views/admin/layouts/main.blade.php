<!doctype html>
<html lang="vi">
<head>
    @include('admin.parts.head')
    <!-- Dành cho CSS tùy chỉnh của từng trang con -->
    @yield('styles')
</head>
<body>
    
    <!-- TOP NAVBAR -->
    @include('admin.parts.header')
    
    <!-- MAIN CONTENT -->
    <main class="admin-content">
        <!-- Khu vực này sẽ được thay thế bằng nội dung của các trang con (ví dụ: home.blade.php) -->
        @yield('content')
    </main>

    @include('components.idle-timeout')

    <!-- FOOTER SCRIPTS -->
    @include('admin.parts.footer')
    
    <!-- Dành cho JS tùy chỉnh của từng trang con (ví dụ: Chart.js của home) -->
    @yield('scripts')

</body>
</html>
