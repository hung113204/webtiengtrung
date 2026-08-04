    <aside class="sidebar" id="sidebar">
      <div class="sidebar-brand">
        <a href="{{ route('home') }}" class="d-flex align-items-center gap-2">
          <span class="brand-mark zh">汉</span>
          <span class="font-head fw-bold fs-6" style="color: var(--text)"
            >Hányǔ Bàn</span
          >
        </a>
      </div>
      <nav class="sidebar-nav">
        <div class="sidebar-section-label">Học tập</div>
        <a href="{{ route('frontend.dashboard') }}" class="nav-link {{ request()->routeIs('frontend.dashboard') ? 'active' : '' }}">
          <svg
            width="18"
            height="18"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
          >
            <rect x="3" y="3" width="7" height="7" rx="1.5" />
            <rect x="14" y="3" width="7" height="7" rx="1.5" />
            <rect x="3" y="14" width="7" height="7" rx="1.5" />
            <rect x="14" y="14" width="7" height="7" rx="1.5" />
          </svg>
          <span class="nav-text">Bảng điều khiển</span>
        </a>
        <a href="{{ route('frontend.dashboard.khoahoc') }}" class="nav-link {{ request()->routeIs('frontend.dashboard.khoahoc') ? 'active' : '' }}">
          <svg
            width="18"
            height="18"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
          >
            <path d="M4 19.5A2.5 2.5 0 016.5 17H20" />
            <path
              d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"
            />
          </svg>
          <span class="nav-text">Khóa học của tôi</span>
        </a>
        <a href="{{ route('frontend.dashboard.yeuthich') }}" class="nav-link {{ request()->routeIs('frontend.dashboard.yeuthich') ? 'active' : '' }}">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"></path>
          </svg>
          <span class="nav-text">Khóa học yêu thích</span>
        </a>
        <a href="{{ route('frontend.dashboard.tuvung') }}" class="nav-link {{ request()->routeIs('frontend.dashboard.tuvung') ? 'active' : '' }}">
          <svg
            width="18"
            height="18"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
          >
            <rect x="3" y="3" width="18" height="18" rx="2" />
            <path d="M3 9h18" />
          </svg>
          <span class="nav-text">Từ vựng</span>
        </a>
        <a href="#" class="nav-link">
          <svg
            width="18"
            height="18"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
          >
            <path d="M12 19l7-7 3 3-7 7-3-3z" />
            <path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z" />
          </svg>
          <span class="nav-text">Luyện viết chữ Hán</span>
        </a>
        <a href="{{ route('frontend.dashboard.luyentap') }}" class="nav-link {{ request()->routeIs('frontend.dashboard.luyentap') ? 'active' : '' }}">
          <svg
            width="18"
            height="18"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
          >
            <circle cx="12" cy="12" r="10" />
            <path d="M12 6v6l4 2" />
          </svg>
          <span class="nav-text">Luyện thi HSK</span>
        </a>
        <a href="{{ route('frontend.dashboard.lotrinh_ai') }}" class="nav-link {{ request()->routeIs('frontend.dashboard.lotrinh_ai') ? 'active' : '' }}">
          <svg
            width="18"
            height="18"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
          >
            <path d="M21 12a9 9 0 0 1-9 9m9-9a9 9 0 0 0-9-9m9 9H3m9 9a9 9 0 0 1-9-9m9 9c1.66 0 3-4.03 3-9s-1.34-9-3-9m0 18c-1.66 0-3-4.03-3-9s1.34-9 3-9m-9 9a9 9 0 0 1 9-9"></path>
          </svg>
          <span class="nav-text">Lộ trình AI</span>
        </a>
        <a href="#" class="nav-link">
          <svg
            width="18"
            height="18"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
          >
            <path
              d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"
            />
          </svg>
          <span class="nav-text">AI Gia sư</span>
        </a>
        <div class="sidebar-section-label">Khác</div>
        <a href="#" class="nav-link">
          <svg
            width="18"
            height="18"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
          >
            <path d="M4 19.5A2.5 2.5 0 016.5 17H20" />
            <path
              d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"
            />
          </svg>
          <span class="nav-text">Từ điển</span>
        </a>
        <a href="#" class="nav-link">
          <svg
            width="18"
            height="18"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
          >
            <circle cx="9" cy="7" r="4" />
            <path d="M2 21v-2a4 4 0 014-4h6a4 4 0 014 4v2" />
            <path d="M16 3.13a4 4 0 010 7.75" />
            <path d="M22 21v-2a4 4 0 00-3-3.87" />
          </svg>
          <span class="nav-text">Diễn đàn</span>
        </a>
        <a href="#" class="nav-link">
          <svg
            width="18"
            height="18"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
          >
            <circle cx="12" cy="12" r="3" />
            <path
              d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 11-2.83 2.83l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 11-2.83-2.83l.06-.06A1.65 1.65 0 004.6 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 112.83-2.83l.06.06A1.65 1.65 0 009 4.6a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 112.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"
            />
          </svg>
          <span class="nav-text">Cài đặt</span>
        </a>
      </nav>
      <div class="sidebar-footer">
        <div class="d-flex align-items-center gap-2">
          <div class="avatar-sm">{{ mb_substr(Auth::user()->ho_ten ?? Auth::user()->ten_dang_nhap ?? 'U', 0, 1) }}</div>
          <div class="flex-fill" style="min-width: 0">
            <div class="fw-semibold small text-truncate">{{ Auth::user()->ho_ten ?? Auth::user()->ten_dang_nhap ?? 'Học viên' }}</div>
            <div class="small" style="color: var(--text-muted)">
              Học viên
            </div>
          </div>
          <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
            @csrf
            <button
              type="submit"
              class="icon-btn border-0 bg-transparent"
              title="Đăng xuất"
              aria-label="Đăng xuất"
            >
              <svg
                width="16"
                height="16"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
              >
                <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4" />
                <path d="M16 17l5-5-5-5" />
                <path d="M21 12H9" />
              </svg>
            </button>
          </form>
        </div>
      </div>
    </aside>
