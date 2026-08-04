document.addEventListener("DOMContentLoaded", function () {
    // Tạo phần tử overlay cho loading
    const overlay = document.createElement("div");
    overlay.id = "hb-global-loading";
    
    // Tạo CSS đi kèm cho hiệu ứng loading hiện đại
    const style = document.createElement("style");
    style.innerHTML = `
        #hb-global-loading {
            position: fixed;
            inset: 0;
            background: var(--bg, #F8FAFC);
            z-index: 99999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: opacity 0.5s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.5s;
        }
        
        [data-theme="dark"] #hb-global-loading {
            background: var(--bg, #0F1115);
        }
        
        .hb-loader-wrapper {
            position: relative;
            width: 86px;
            height: 86px;
            margin-bottom: 24px;
        }
        
        .arc-spinner {
            width: 100%;
            height: 100%;
            position: absolute;
            inset: 0;
            animation: arcRotate 1.6s linear infinite;
        }
        
        .arc-spinner span {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            border: 3px solid transparent;
        }
        
        .arc-spinner span:nth-child(1) {
            width: 100%; height: 100%;
            border-top-color: var(--primary, #DC2626);
            border-right-color: var(--secondary, #F59E0B);
            animation: arcFade 1.6s ease-in-out infinite;
            animation-delay: 0s;
        }
        
        .arc-spinner span:nth-child(2) {
            width: 74%; height: 74%; top: 13%; left: 13%;
            border-top-color: var(--primary, #DC2626);
            animation: arcFade 1.6s ease-in-out infinite;
            animation-delay: 0.15s;
            transform: rotate(45deg);
        }
        
        .arc-spinner span:nth-child(3) {
            width: 48%; height: 48%; top: 26%; left: 26%;
            border-top-color: var(--primary, #DC2626);
            animation: arcFade 1.6s ease-in-out infinite;
            animation-delay: 0.3s;
            transform: rotate(100deg);
        }
        
        .arc-spinner span:nth-child(4) {
            width: 24%; height: 24%; top: 38%; left: 38%;
            border-top-color: var(--primary, #DC2626);
            animation: arcFade 1.6s ease-in-out infinite;
            animation-delay: 0.45s;
            transform: rotate(160deg);
        }

        @keyframes arcRotate {
            to { transform: rotate(360deg); }
        }

        @keyframes arcFade {
            0%, 100% { opacity: 0.15; }
            50% { opacity: 1; }
        }

        .hb-loader-icon {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: var(--font-zh, 'Noto Sans SC', sans-serif);
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary, #DC2626);
            animation: hb-pulse 2s ease-in-out infinite;
        }
        
        .hb-loading-text {
            font-family: var(--font-head, 'Poppins', sans-serif);
            font-weight: 600;
            color: var(--text, #111827);
            font-size: 1.1rem;
            letter-spacing: 0.5px;
            animation: hb-pulse 2s ease-in-out infinite;
        }
        
        [data-theme="dark"] .hb-loading-text {
            color: var(--text, #F3F4F6);
        }
        
        @keyframes hb-pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.6; transform: scale(0.95); }
        }
        
        #hb-global-loading.hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }
    `;
    
    // Cấu trúc HTML bên trong overlay
    overlay.innerHTML = `
        <div class="hb-loader-wrapper">
            <div class="arc-spinner">
                <span></span><span></span><span></span><span></span>
            </div>
            <div class="hb-loader-icon">汉</div>
        </div>
        <div class="hb-loading-text">Đang tải dữ liệu...</div>
    `;
    
    // Gắn vào DOM
    document.head.appendChild(style);
    document.body.prepend(overlay);
});

// Loại bỏ loading khi toàn bộ tài nguyên (hình ảnh, iframe, fonts...) đã tải xong
window.addEventListener("load", function () {
    const loading = document.getElementById("hb-global-loading");
    if (loading) {
        // Độ trễ nhẹ giúp giao diện không bị giật nếu tải quá nhanh
        setTimeout(() => {
            loading.classList.add("hidden");
            // Gỡ bỏ khỏi DOM hoàn toàn sau khi mờ dần
            setTimeout(() => {
                loading.remove();
            }, 500);
        }, 300);
    }
});
