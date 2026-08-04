<!-- ================= NAVBAR ================= -->
<nav class="navbar navbar-expand-lg navbar-custom fixed-top py-3">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
      @php
          $logoUrl = \App\Models\CauHinh::getByKey('website_logo');
          $websiteName = \App\Models\CauHinh::getByKey('website_name', 'Hányǔ Bàn');
      @endphp
      @if($logoUrl)
          <img src="{{ Storage::url($logoUrl) }}" alt="{{ $websiteName }}" style="height: 44px; object-fit: contain; border-radius: 8px;">
      @else
          <span class="brand-mark zh">汉</span>
      @endif
      <span class="font-head fw-bold fs-5">{{ $websiteName }}</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Mở menu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNav">
      @include('frontend.parts.sidebar')
      <div class="d-flex align-items-center gap-2">
        <button class="theme-toggle" id="themeToggle" aria-label="Chuyển chế độ sáng/tối" type="button">
          <svg id="iconMoon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
        </button>
        @auth
          <div class="dropdown">
            <button class="btn btn-outline-brand dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              {{ Auth::user()->ho_ten ?? Auth::user()->ten_dang_nhap ?? 'Tài khoản' }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="{{ route('frontend.dashboard') }}">Không gian học tập</a></li>
              @if(Auth::user()->isAdmin() || Auth::user()->isTeacher())
              <li><a class="dropdown-item" href="{{ route('admin.home') }}">Vào trang Quản trị</a></li>
              @endif
              <li><hr class="dropdown-divider"></li>
              <li>
                <form action="{{ route('logout') }}" method="POST">
                  @csrf
                  <button type="submit" class="dropdown-item text-danger">Đăng xuất</button>
                </form>
              </li>
            </ul>
          </div>
        @else
          <a href="{{ route('login') }}" class="btn-outline-brand d-none d-md-inline-block">Đăng nhập</a>
          <a href="{{ route('register.form') }}" class="btn-brand">Đăng ký</a>
        @endauth
      </div>
    </div>
  </div>
</nav>
