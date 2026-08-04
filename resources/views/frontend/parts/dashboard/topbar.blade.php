      <div class="topbar">
        <button
          class="sidebar-toggle"
          id="sidebarToggle"
          aria-label="Mở/đóng menu"
        >
          <svg
            width="18"
            height="18"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
          >
            <path d="M3 12h18M3 6h18M3 18h18" />
          </svg>
        </button>
        <div class="search-input-wrap d-none d-md-block">
          <svg
            width="16"
            height="16"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
          >
            <circle cx="11" cy="11" r="8" />
            <path d="M21 21l-4.35-4.35" />
          </svg>
          <input
            type="text"
            class="search-input"
            placeholder="Tìm khóa học, từ vựng, chữ Hán..."
          />
        </div>
        <div class="ms-auto d-flex align-items-center gap-2">
          <button
            class="icon-btn"
            id="themeToggle"
            aria-label="Chuyển chế độ sáng/tối"
            type="button"
          >
            <svg
              width="17"
              height="17"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
            >
              <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />
            </svg>
          </button>
          @php
              $unreadNotificationsCount = 0;
              $latestNotifications = collect();
              if (auth()->check()) {
                  $unreadNotificationsCount = \App\Models\ThongBaoNguoiDung::where('id_nguoi_dung', auth()->id())
                                                  ->where('da_doc', false)
                                                  ->count();
                  $latestNotifications = \App\Models\ThongBaoNguoiDung::with('thongBao')
                                                  ->where('id_nguoi_dung', auth()->id())
                                                  ->orderBy('created_at', 'desc')
                                                  ->take(5)
                                                  ->get();
              }
          @endphp
          <div class="dropdown">
            <button
              class="icon-btn"
              id="notifBtn"
              aria-label="Thông báo"
              type="button"
              data-bs-toggle="dropdown"
              aria-expanded="false"
            >
              <svg
                width="17"
                height="17"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
              >
                <path d="M18 8a6 6 0 00-12 0c0 7-3 9-3 9h18s-3-2-3-9" />
                <path d="M13.73 21a2 2 0 01-3.46 0" />
              </svg>
              @if($unreadNotificationsCount > 0)
                <span class="dot-badge"></span>
              @endif
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="notifBtn" style="width: 320px; max-height: 400px; overflow-y: auto;">
              <li><h6 class="dropdown-header fw-bold">Thông báo mới</h6></li>
              @if($latestNotifications->isEmpty())
                <li><span class="dropdown-item-text text-muted">Không có thông báo nào.</span></li>
              @else
                @foreach($latestNotifications as $tbnd)
                  <li>
                    <a class="dropdown-item border-bottom py-2 {{ !$tbnd->da_doc ? 'bg-light' : '' }}" href="#" style="white-space: normal;">
                      <strong class="d-block text-dark" style="font-size: 14px;">{{ $tbnd->thongBao->tieu_de ?? 'Thông báo' }}</strong>
                      <small class="text-muted d-block mt-1" style="font-size: 12px; line-height: 1.4;">{{ Str::limit($tbnd->thongBao->noi_dung ?? '', 80) }}</small>
                      <small class="text-muted d-block mt-1" style="font-size: 11px;"><i class="bi bi-clock me-1"></i>{{ $tbnd->created_at ? $tbnd->created_at->diffForHumans() : '' }}</small>
                    </a>
                  </li>
                @endforeach
                <li><a class="dropdown-item text-center text-primary py-2 fw-semibold" href="#">Xem tất cả thông báo</a></li>
              @endif
            </ul>
          </div>
        </div>
      </div>
