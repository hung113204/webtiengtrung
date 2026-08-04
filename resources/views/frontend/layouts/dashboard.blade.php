<!doctype html>
<html lang="vi" data-theme="light">
  <head>
    @include('frontend.parts.dashboard.head')
  </head>
  <body>
    <a href="#main" class="skip-link">Bỏ qua đến nội dung chính</a>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- ================= SIDEBAR ================= -->
    @include('frontend.parts.dashboard.sidebar')

    <!-- ================= MAIN ================= -->
    <div class="main-content">
      @include('frontend.parts.dashboard.topbar')

      <main id="main" class="page-pad">
        @yield('content')
      </main>
    </div>

    <!-- Toast Container -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
      <div
        id="ajaxToast"
        class="toast align-items-center border-0 text-bg-primary"
        role="status"
        aria-live="polite"
        aria-atomic="true"
      >
        <div class="d-flex">
          <div class="toast-body" id="ajaxToastBody">
            Đang tải dữ liệu bảng điều khiển...
          </div>
          <button
            type="button"
            class="btn-close btn-close-white me-2 m-auto"
            data-bs-dismiss="toast"
            aria-label="Đóng"
          ></button>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        /* ---------- Theme Toggle ---------- */
        const root = document.documentElement;
        function lsGet(k) {
          try { return localStorage.getItem(k); } catch (e) { return null; }
        }
        function lsSet(k, v) {
          try { localStorage.setItem(k, v); } catch (e) {}
        }
        root.setAttribute("data-theme", lsGet("hb-theme") || "light");
        const themeBtn = document.getElementById("themeToggle");
        if(themeBtn) {
            themeBtn.addEventListener("click", function () {
              const next = root.getAttribute("data-theme") === "light" ? "dark" : "light";
              root.setAttribute("data-theme", next);
              lsSet("hb-theme", next);
            });
        }

        /* ---------- Restore sidebar state ---------- */
        if (lsGet("hb-sidebar-collapsed") === "true") {
            document.body.classList.add("sidebar-collapsed");
        }

        /* ---------- Sidebar toggle (mobile/desktop) ---------- */
        const sidebar = document.getElementById("sidebar");
        const overlay = document.getElementById("sidebarOverlay");
        const toggleBtn = document.getElementById("sidebarToggle");
        
        function openSidebar() {
          if(sidebar) sidebar.classList.add("show");
          if(overlay) overlay.classList.add("show");
        }
        function closeSidebar() {
          if(sidebar) sidebar.classList.remove("show");
          if(overlay) overlay.classList.remove("show");
        }
        
        if(toggleBtn && sidebar) {
            toggleBtn.addEventListener("click", function () {
              if (window.innerWidth < 992) {
                // Mobile behavior
                sidebar.classList.contains("show") ? closeSidebar() : openSidebar();
              } else {
                // Desktop behavior
                const isCollapsed = document.body.classList.toggle("sidebar-collapsed");
                lsSet("hb-sidebar-collapsed", isCollapsed ? "true" : "false");
              }
            });
        }
        if(overlay) {
            overlay.addEventListener("click", closeSidebar);
        }
      });
    </script>
    @stack('scripts')
    @include('components.idle-timeout')
  </body>
</html>
