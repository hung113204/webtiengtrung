<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Admin Dashboard — Hányǔ Bàn')</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
@php
    $faviconUrl = \App\Models\CauHinh::getByKey('website_favicon');
@endphp
@if($faviconUrl)
    <link rel="icon" type="image/png" href="{{ Storage::url($faviconUrl) }}">
    <link rel="shortcut icon" type="image/png" href="{{ Storage::url($faviconUrl) }}">
@endif
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
<link href="{{ asset('backend/asset/css/style.css') }}" rel="stylesheet" />
{{-- Inline script: apply saved theme BEFORE paint to prevent white flash (FOUC) --}}
<script>
  (function() {
    var t = localStorage.getItem('hanyu_admin_theme');
    if (t === 'dark') {
      document.documentElement.setAttribute('data-theme', 'dark');
    }
  })();
</script>

