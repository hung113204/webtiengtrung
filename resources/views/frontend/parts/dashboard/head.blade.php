    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Bảng điều khiển — Hányǔ Bàn')</title>
    <meta
      name="description"
      content="Theo dõi tiến độ học tiếng Trung của bạn: streak, XP, khóa học đang học, lịch học."
    />
    
    @php
        $faviconUrl = \App\Models\CauHinh::getByKey('website_favicon');
    @endphp
    @if($faviconUrl)
        <link rel="icon" type="image/png" href="{{ Storage::url($faviconUrl) }}">
        <link rel="shortcut icon" type="image/png" href="{{ Storage::url($faviconUrl) }}">
    @endif
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link
      href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&family=Inter:wght@400;500;600&family=Noto+Sans+SC:wght@400;600;700&display=swap"
      rel="stylesheet"
    />
    <script src="{{ asset('frontend/asset/js/loading.js') }}"></script>
    <link href="{{ asset('frontend/asset/css/dashboard.css') }}" rel="stylesheet" />
    @stack('styles')