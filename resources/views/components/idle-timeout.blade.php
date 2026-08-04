@if(Auth::check())
{{-- ===== IDLE TIMEOUT MODAL ===== --}}
<div id="idle-timeout-overlay" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.55); z-index:99998; backdrop-filter:blur(4px); -webkit-backdrop-filter:blur(4px);"></div>
<div id="idle-timeout-modal" role="dialog" aria-modal="true" aria-labelledby="idle-modal-title" style="
    display: none;
    position: fixed;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%) scale(0.85);
    z-index: 99999;
    width: 90%;
    max-width: 440px;
    background: var(--admin-card-bg, #fff);
    border-radius: 20px;
    box-shadow: 0 24px 60px rgba(0,0,0,0.3);
    padding: 2.4rem 2rem 2rem;
    text-align: center;
    opacity: 0;
    transition: opacity 0.3s ease, transform 0.3s ease;
    font-family: 'Inter', sans-serif;
">
    {{-- Icon --}}
    <div style="width:72px; height:72px; border-radius:50%; background:linear-gradient(135deg,#fef3c7,#fde68a); display:flex; align-items:center; justify-content:center; margin:0 auto 1.2rem; font-size:2rem;">⏱️</div>
    <h2 id="idle-modal-title" style="font-size:1.25rem; font-weight:700; color:var(--admin-text,#111827); margin-bottom:0.5rem;">Phiên làm việc sắp hết hạn</h2>
    <p style="color:var(--admin-text-muted,#6b7280); font-size:0.9rem; line-height:1.6; margin-bottom:1.5rem;">
        Bạn không có thao tác nào trong một khoảng thời gian dài.<br>
        Hệ thống sẽ tự động đăng xuất sau <strong id="idle-countdown" style="color:#DC2626; font-size:1.1rem;">300</strong> giây.
    </p>
    {{-- Progress bar --}}
    <div style="height:5px; background:#f3f4f6; border-radius:999px; overflow:hidden; margin-bottom:1.8rem;">
        <div id="idle-progress-bar" style="height:100%; width:100%; background:linear-gradient(90deg,#DC2626,#f59e0b); border-radius:999px; transition:width 1s linear;"></div>
    </div>
    {{-- Buttons --}}
    <div style="display:flex; gap:12px; justify-content:center;">
        <button id="idle-stay-btn" onclick="idleKeepAlive()" style="
            flex:1; padding:0.7rem 1.2rem;
            background:linear-gradient(135deg,#DC2626,#b91c1c);
            color:#fff; border:none; border-radius:12px;
            font-weight:600; font-size:0.95rem; cursor:pointer;
            transition:transform 0.15s, box-shadow 0.15s;
            box-shadow:0 4px 14px rgba(220,38,38,0.35);
        " onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(220,38,38,0.45)'"
           onmouseout="this.style.transform=''; this.style.boxShadow='0 4px 14px rgba(220,38,38,0.35)'">
            ✋ Tôi vẫn đang làm việc
        </button>
        <button id="idle-logout-btn" onclick="idleForceLogout()" style="
            flex:1; padding:0.7rem 1.2rem;
            background:transparent; color:var(--admin-text-muted,#6b7280);
            border:2px solid #e5e7eb; border-radius:12px;
            font-weight:600; font-size:0.95rem; cursor:pointer;
            transition:border-color 0.2s, color 0.2s;
        " onmouseover="this.style.borderColor='#DC2626'; this.style.color='#DC2626'"
           onmouseout="this.style.borderColor='#e5e7eb'; this.style.color='var(--admin-text-muted,#6b7280)'">
            Đăng xuất ngay
        </button>
    </div>
</div>

<script>
(function () {
    // ---- CẤU HÌNH (chỉnh tại đây) ----
    var IDLE_TIMEOUT_MS   = 25 * 60 * 1000; // 25 phút không hoạt động → hiện cảnh báo
    var WARN_COUNTDOWN_S  = 300;             // 5 phút (300 giây) đếm ngược trước khi đăng xuất
    var KEEPALIVE_PING_MS = 4 * 60 * 1000;  // Ping server mỗi 4 phút để giữ session PHP

    var modal   = document.getElementById('idle-timeout-modal');
    var overlay = document.getElementById('idle-timeout-overlay');
    if (!modal || !overlay) return;

    // ---- Lấy URLs dựa trên route hiện tại ----
    var isAdmin = window.location.pathname.startsWith('/admin');
    var keepaliveUrl = isAdmin ? "{{ route('admin.keepalive') }}" : "{{ route('keepalive') }}";
    var logoutUrl    = isAdmin ? "{{ route('admin.logout') }}" : "{{ route('logout') }}";
    var csrfToken    = "{{ csrf_token() }}";
    var sessionCheckUrl = isAdmin ? "{{ route('admin.session.check') }}" : "{{ route('session.check') }}";
    var loginUrl     = isAdmin ? "{{ route('admin.login') }}" : "{{ route('login') }}";

    var idleTimer      = null;
    var countdownTimer = null;
    var pingTimer      = null;
    var warningShown   = false;
    var secondsLeft    = WARN_COUNTDOWN_S;

    // ---- Hiện modal cảnh báo ----
    function showWarning() {
        if (warningShown) return;
        warningShown = true;
        secondsLeft  = WARN_COUNTDOWN_S;

        overlay.style.display = 'block';
        modal.style.display   = 'block';
        requestAnimationFrame(function() {
            modal.style.opacity   = '1';
            modal.style.transform = 'translate(-50%, -50%) scale(1)';
        });

        var countdownEl   = document.getElementById('idle-countdown');
        var progressBarEl = document.getElementById('idle-progress-bar');
        if (countdownEl)   countdownEl.textContent = secondsLeft;
        if (progressBarEl) progressBarEl.style.width = '100%';

        countdownTimer = setInterval(function() {
            secondsLeft--;
            if (countdownEl)   countdownEl.textContent = secondsLeft;
            if (progressBarEl) {
                var pct = (secondsLeft / WARN_COUNTDOWN_S) * 100;
                progressBarEl.style.width = pct + '%';
                if (secondsLeft <= 60) {
                    progressBarEl.style.background = 'linear-gradient(90deg,#b91c1c,#dc2626)';
                }
            }
            if (secondsLeft <= 0) {
                clearInterval(countdownTimer);
                performLogout();
            }
        }, 1000);
    }

    // ---- Ẩn modal ----
    function hideWarning() {
        modal.style.opacity   = '0';
        modal.style.transform = 'translate(-50%, -50%) scale(0.85)';
        setTimeout(function() {
            modal.style.display   = 'none';
            overlay.style.display = 'none';
        }, 300);
        clearInterval(countdownTimer);
        warningShown = false;
        var progressBarEl = document.getElementById('idle-progress-bar');
        if (progressBarEl) {
            progressBarEl.style.background = 'linear-gradient(90deg,#DC2626,#f59e0b)';
            progressBarEl.style.width = '100%';
        }
    }

    // ---- Thực hiện đăng xuất ----
    function performLogout() {
        clearInterval(countdownTimer);
        clearInterval(pingTimer);
        clearTimeout(idleTimer);

        var titleEl = document.getElementById('idle-modal-title');
        if (titleEl) titleEl.textContent = 'Đang đăng xuất...';

        var form   = document.createElement('form');
        form.method = 'POST';
        form.action = logoutUrl;
        var csrf   = document.createElement('input');
        csrf.type  = 'hidden';
        csrf.name  = '_token';
        csrf.value = csrfToken;
        form.appendChild(csrf);
        document.body.appendChild(form);
        form.submit();
    }

    // ---- Reset bộ đếm khi có hoạt động ----
    function resetIdleTimer() {
        if (warningShown) return;
        clearTimeout(idleTimer);
        idleTimer = setTimeout(showWarning, IDLE_TIMEOUT_MS);
    }

    // ---- Theo dõi mọi hoạt động ----
    var ACTIVITY_EVENTS = ['mousemove', 'mousedown', 'keydown', 'touchstart', 'scroll', 'wheel', 'click'];
    ACTIVITY_EVENTS.forEach(function(evt) {
        document.addEventListener(evt, resetIdleTimer, { passive: true });
    });

    // ---- Nút "Tôi vẫn đang làm việc" ----
    window.idleKeepAlive = function () {
        hideWarning();
        resetIdleTimer();
        fetch(keepaliveUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            },
            credentials: 'same-origin'
        }).catch(function() {});
    };

    // ---- Nút "Đăng xuất ngay" ----
    window.idleForceLogout = function () {
        clearInterval(countdownTimer);
        performLogout();
    };

    // ---- Ping server định kỳ để session PHP không expire sớm ----
    function startPing() {
        pingTimer = setInterval(function() {
            if (warningShown) return;
            fetch(keepaliveUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                },
                credentials: 'same-origin'
            }).catch(function() {});
        }, KEEPALIVE_PING_MS);
    }

    // ---- Khi tab được focus lại, kiểm tra session vẫn còn không ----
    document.addEventListener('visibilitychange', function() {
        if (document.visibilityState === 'visible' && !warningShown) {
            fetch(sessionCheckUrl, { credentials: 'same-origin' })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (!data.authenticated) {
                        window.location.href = loginUrl;
                    }
                })
                .catch(function() {});
        }
    });

    // ---- Đăng xuất khi API trả 401/419 (Token/Session hết hạn) ----
    var originalFetch = window.fetch;
    window.fetch = function() {
        return originalFetch.apply(this, arguments).then(function(response) {
            if (response.status === 401 || response.status === 419) {
                // Hết hạn session hoặc unauthenticated
                window.location.href = loginUrl;
            }
            return response;
        });
    };

    // ---- Khởi động ----
    resetIdleTimer();
    startPing();
})();
</script>
@endif
