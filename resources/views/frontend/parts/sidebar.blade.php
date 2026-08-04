<ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-1">

  {{-- Link cố định: Khóa học --}}
  <li class="nav-item">
    <a class="nav-link nav-link-custom {{ request()->routeIs('khoahoc.index') ? 'active' : '' }}"
       href="{{ route('khoahoc.index') }}">
      Khóa học
    </a>
  </li>

  {{-- Link cố định: Lộ trình --}}
  <li class="nav-item">
    <a class="nav-link nav-link-custom {{ request()->routeIs('lotrinh.index') ? 'active' : '' }}"
       href="{{ route('lotrinh.index') }}">
      Lộ Trình
    </a>
  </li>

  {{-- Link cố định: Tính năng --}}
  <li class="nav-item">
    <a class="nav-link nav-link-custom {{ request()->routeIs('tinhnang.index') ? 'active' : '' }}"
       href="{{ route('tinhnang.index') }}">
      Tính năng
    </a>
  </li>

  {{-- Link cố định: Đánh giá --}}
  <li class="nav-item">
    <a class="nav-link nav-link-custom {{ request()->is('danh-gia') ? 'active' : '' }}"
       href="{{ url('/#testimonials') }}">
      Đánh giá
    </a>
  </li>

  {{-- Link cố định: FAQ --}}
  <li class="nav-item">
    <a class="nav-link nav-link-custom {{ request()->is('faq') ? 'active' : '' }}"
       href="{{ url('/#faq') }}">
      FAQ
    </a>
  </li>

</ul>
