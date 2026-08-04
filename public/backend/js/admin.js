document.addEventListener('DOMContentLoaded', function() {
  // --- MEGA MENU HOVER ---
  document.querySelectorAll('.dropdown').forEach(function (dropdown) {
    dropdown.addEventListener('mouseenter', function () {
      if (window.innerWidth > 992) {
        let toggle = dropdown.querySelector('[data-bs-toggle="dropdown"]');
        let menu = dropdown.querySelector('.dropdown-menu');
        if(toggle && menu) {
          toggle.classList.add('show');
          menu.classList.add('show');
        }
      }
    });
    dropdown.addEventListener('mouseleave', function () {
      if (window.innerWidth > 992) {
        let toggle = dropdown.querySelector('[data-bs-toggle="dropdown"]');
        let menu = dropdown.querySelector('.dropdown-menu');
        if(toggle && menu) {
          toggle.classList.remove('show');
          menu.classList.remove('show');
        }
      }
    });
  });

  // --- CHẾ ĐỘ SÁNG / TỐI (THEME TOGGLE) ---
  const themeToggle = document.getElementById('themeToggle');
  const htmlElement = document.documentElement;
  
  // Lấy trạng thái theme đã lưu từ localStorage, nếu không có mặc định là light
  const savedTheme = localStorage.getItem('hanyu_admin_theme') || 'light';
  htmlElement.setAttribute('data-theme', savedTheme);
  updateThemeIcons(savedTheme);

  // Bắt sự kiện click để đổi theme
  if(themeToggle) {
    themeToggle.addEventListener('click', () => {
      const currentTheme = htmlElement.getAttribute('data-theme');
      const newTheme = currentTheme === 'light' ? 'dark' : 'light';
      
      htmlElement.setAttribute('data-theme', newTheme);
      localStorage.setItem('hanyu_admin_theme', newTheme);
      updateThemeIcons(newTheme);
    });
  }

  // Cập nhật hiển thị icon Mặt trời / Mặt trăng
  function updateThemeIcons(theme) {
    const sunIcon = document.querySelector('.sun-icon');
    const moonIcon = document.querySelector('.moon-icon');
    const btn = document.getElementById('themeToggle');
    
    if(sunIcon && moonIcon && btn) {
      if (theme === 'dark') {
        sunIcon.classList.remove('d-none');
        moonIcon.classList.add('d-none');
        btn.classList.remove('text-muted');
        btn.classList.add('text-warning'); // Làm nút sáng lên (màu vàng)
      } else {
        sunIcon.classList.add('d-none');
        moonIcon.classList.remove('d-none');
        btn.classList.add('text-muted');
        btn.classList.remove('text-warning');
      }
    }
  }
});

// --- GLOBAL LOADING EFFECT CHO BACKEND ---
function initGlobalLoading() {
    // Không thêm nếu đã có (tránh trùng lặp)
    if (document.getElementById("hb-global-loading")) return;

    // Tạo phần tử overlay cho loading
    const overlay = document.createElement("div");
    overlay.id = "hb-global-loading";
    
    // Tạo CSS đi kèm cho hiệu ứng loading hiện đại
    const style = document.createElement("style");
    style.innerHTML = `
        #hb-global-loading {
            position: fixed;
            inset: 0;
            background: var(--admin-bg, #F8FAFC);
            z-index: 99999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: opacity 0.5s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.5s;
        }
        
        [data-theme="dark"] #hb-global-loading {
            background: var(--admin-bg, #111827);
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
            border-top-color: var(--admin-primary, #DC2626);
            border-right-color: var(--admin-primary-hover, #B91C1C);
            animation: arcFade 1.6s ease-in-out infinite;
            animation-delay: 0s;
        }
        
        .arc-spinner span:nth-child(2) {
            width: 74%; height: 74%; top: 13%; left: 13%;
            border-top-color: var(--admin-primary, #DC2626);
            animation: arcFade 1.6s ease-in-out infinite;
            animation-delay: 0.15s;
            transform: rotate(45deg);
        }
        
        .arc-spinner span:nth-child(3) {
            width: 48%; height: 48%; top: 26%; left: 26%;
            border-top-color: var(--admin-primary, #DC2626);
            animation: arcFade 1.6s ease-in-out infinite;
            animation-delay: 0.3s;
            transform: rotate(100deg);
        }
        
        .arc-spinner span:nth-child(4) {
            width: 24%; height: 24%; top: 38%; left: 38%;
            border-top-color: var(--admin-primary, #DC2626);
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
            color: var(--admin-primary, #DC2626);
            animation: hb-pulse 2s ease-in-out infinite;
        }
        
        .hb-loading-text {
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            color: var(--admin-text, #111827);
            font-size: 1.1rem;
            letter-spacing: 0.5px;
            animation: hb-pulse 2s ease-in-out infinite;
        }
        
        [data-theme="dark"] .hb-loading-text {
            color: var(--admin-text, #F3F4F6);
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
        <div class="hb-loading-text">Đang tải không gian làm việc...</div>
    `;
    
    // Gắn vào DOM
    document.head.appendChild(style);
    document.body.prepend(overlay);
}

// Khởi chạy ngay nếu DOM đã sẵn sàng, hoặc đợi DOMContentLoaded
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initGlobalLoading);
} else {
    initGlobalLoading();
}

// Loại bỏ loading khi toàn bộ tài nguyên đã tải xong
if (document.readyState === "complete") {
    removeGlobalLoading();
} else {
    window.addEventListener("load", removeGlobalLoading);
}

function removeGlobalLoading() {
    const loading = document.getElementById("hb-global-loading");
    if (loading) {
        setTimeout(() => {
            loading.classList.add("hidden");
            setTimeout(() => {
                loading.remove();
            }, 500);
        }, 2000); // 2000ms delay để xem loading rõ hơn
    }
}

// --- GLOBAL AJAX DELETE FUNCTION ---
function deleteDataAjax(id, name, url) {
    Swal.fire({
        title: 'Xác nhận xóa?',
        html: `Bạn có chắc chắn muốn xóa <b>${name}</b>?<br>Hành động này không thể hoàn tác!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6e7d88',
        confirmButtonText: 'Đồng ý xóa',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            // Hiển thị loading trong quá trình xóa
            Swal.fire({
                title: 'Đang xử lý...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Lấy CSRF token từ thẻ meta
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ _method: 'DELETE' })
            })
            .then(response => {
                if(response.ok) return response.json();
                return response.json().then(err => { throw err; });
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Đã xóa!',
                        text: data.message || 'Xóa dữ liệu thành công.',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: error.message || 'Không thể xóa dữ liệu này, vui lòng thử lại.'
                });
            });
        }
    });
}

// --- GLOBAL AUTO SLUG GENERATOR ---
function generateSlug(text) {
    return text.toString().toLowerCase()
        .replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a')
        .replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e')
        .replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i')
        .replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o')
        .replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u')
        .replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y')
        .replace(/đ/gi, 'd')
        .replace(/\s+/g, '-')
        .replace(/[^\w\-]+/g, '')
        .replace(/\-\-+/g, '-')
        .replace(/^-+/, '')
        .replace(/-+$/, '');
}

document.addEventListener('DOMContentLoaded', function() {
    // Tự động quét tất cả các trường có khả năng tạo slug
    const sourceNames = ['ten_vai_tro', 'ten_danh_muc', 'ten_khoa_hoc', 'ten_chuong', 'ten_bai_hoc', 'tieu_de', 'ten_cap_do', 'ten_loai','ten_muc_do'];
    const selector = sourceNames.map(name => `input[name="${name}"]`).join(', ');
    const sourceInputs = document.querySelectorAll(selector);
    
    sourceInputs.forEach(function(input) {
        input.addEventListener('keyup', function() {
            const form = this.closest('form');
            if(form) {
                const slugInput = form.querySelector('input[name="slug"]');
                if(slugInput) {
                    slugInput.value = generateSlug(this.value);
                }
            }
        });
    });
});


