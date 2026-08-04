<header class="admin-topbar">
  <!-- Logo -->
  <a href="{{ route('admin.home') ?? 'index.html' }}" class="admin-brand">
    <span class="brand-mark">汉</span>
    Hányǔ Admin
  </a>
  
  <!-- Horizontal Navigation -->
  @include('admin.parts.sidebar')
  
  <!-- Right Side (User Profile & Theme Toggle) -->
  <div class="ms-auto d-flex align-items-center gap-3">
    
    <!-- Nút Đổi giao diện -->
    <button id="themeToggle" class="btn border-0 text-muted p-2" title="Đổi giao diện Sáng/Tối">
      <svg class="sun-icon d-none" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
      <svg class="moon-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
    </button>

    <div class="dropdown">
      <button class="btn dropdown-toggle d-flex align-items-center gap-2 border-0 px-2" type="button" data-bs-toggle="dropdown"
              style="background: transparent; color: var(--topbar-text);">
        @if(auth()->check())
            <img src="{{ auth()->user()->anh_dai_dien ? Storage::url(auth()->user()->anh_dai_dien) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->ho_ten).'&background=random' }}"
                 class="rounded-circle border shadow-sm" width="32" height="32" style="object-fit: cover;" alt="Avatar">
            <span class="d-none d-sm-inline fw-medium" style="color: var(--topbar-text);">{{ auth()->user()->ho_ten }}</span>
        @else
            <div style="width: 32px; height: 32px; background: var(--admin-primary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.85rem;">A</div>
            <span class="d-none d-sm-inline fw-medium" style="color: var(--topbar-text);">Admin</span>
        @endif
      </button>
      <ul class="dropdown-menu dropdown-menu-end">
        <li>
          <a class="dropdown-item" href="#">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
            Hồ sơ
          </a>
        </li>
        <li>
          <a class="dropdown-item" href="#">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
            Hỗ trợ
          </a>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li>
          <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            Đăng xuất
          </a>
        </li>
      </ul>
      <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
          @csrf
      </form>
    </div>
  </div>
</header>
