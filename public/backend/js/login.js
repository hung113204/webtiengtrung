// Toggle password visibility
const togglePassword = document.getElementById('togglePassword');
const passwordInput = document.getElementById('passwordInput');

togglePassword.addEventListener('click', function () {
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    
    if (type === 'text') {
        this.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>';
    } else {
        this.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
    }
});

// Cool Login Split & Push Up Animation with AJAX
document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const btn = document.getElementById('loginBtn');
    const btnText = btn.querySelector('.btn-text');
    const spinner = document.getElementById('loginSpinner');
    const loginPage = document.querySelector('.login-page');
    
    // Clear old errors
    const existingError = document.getElementById('loginErrorMsg');
    if (existingError) {
        existingError.remove();
    }

    // 1. Show loading state on button briefly
    btnText.style.opacity = '0';
    spinner.classList.remove('d-none');
    spinner.classList.add('d-inline-block');
    btn.style.pointerEvents = 'none';
    btn.style.opacity = '0.8';

    // Collect Data
    const formData = new FormData(this);
    
    // Send AJAX request
    fetch(this.action, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // SUCCESS - Trigger Notification & Animation

            // Show success notification
            const successToast = document.createElement('div');
            successToast.className = 'alert alert-success position-fixed top-0 start-50 translate-middle-x mt-4 shadow-lg text-center fw-medium';
            successToast.style.zIndex = '9999';
            successToast.style.minWidth = '320px';
            successToast.style.animation = 'slideDown 0.4s ease-out forwards';
            successToast.innerHTML = '<svg width="20" height="20" class="me-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> Đăng nhập thành công!';
            
            if (!document.getElementById('toastStyles')) {
                const style = document.createElement('style');
                style.id = 'toastStyles';
                style.innerHTML = `
                    @keyframes slideDown {
                        from { transform: translate(-50%, -100%); opacity: 0; }
                        to { transform: translate(-50%, 0); opacity: 1; }
                    }
                `;
                document.head.appendChild(style);
            }
            document.body.appendChild(successToast);
            
            // Wait for 1.5 seconds so user can see the notification, then start transition
            setTimeout(() => {
                // Prevent animation re-triggering on clones (which causes the flash)
                const loginCard = loginPage.querySelector('.login-card');
                if(loginCard) loginCard.classList.remove('animate-fade-in');

                // Sync input values so the text doesn't disappear in clones
                loginPage.querySelectorAll('input').forEach(input => {
                    if (input.type === 'checkbox' || input.type === 'radio') {
                        if (input.checked) input.setAttribute('checked', 'checked');
                    } else {
                        input.setAttribute('value', input.value);
                    }
                });

                const leftClone = loginPage.cloneNode(true);
                const rightClone = loginPage.cloneNode(true);

                leftClone.style.cssText = "position:fixed; top:0; left:0; width:100vw; height:100vh; z-index:1050; overflow:hidden; clip-path: polygon(0 0, 50% 0, 50% 100%, 0% 100%); transition: transform 0.9s cubic-bezier(0.77, 0, 0.175, 1), filter 0.9s;";
                rightClone.style.cssText = "position:fixed; top:0; left:0; width:100vw; height:100vh; z-index:1050; overflow:hidden; clip-path: polygon(50% 0, 100% 0, 100% 100%, 50% 100%); transition: transform 0.9s cubic-bezier(0.77, 0, 0.175, 1), filter 0.9s;";

                document.body.appendChild(leftClone);
                document.body.appendChild(rightClone);

                // Hide original and fix white flash behind the split animation
                document.body.style.backgroundColor = 'var(--admin-bg)';
                loginPage.style.display = 'none';

                // 3. Create the fake "Index Page" sliding up from bottom
                const slideUpPage = document.createElement('div');
                slideUpPage.style.cssText = "position:fixed; top:100vh; left:0; width:100vw; height:100vh; background:var(--admin-bg); z-index:1000; transition: transform 0.9s cubic-bezier(0.77, 0, 0.175, 1); display:flex; align-items:center; justify-content:center; flex-direction:column;";
                
                slideUpPage.innerHTML = `
                    <div class="hb-loader-wrapper">
                        <div class="arc-spinner">
                            <span></span><span></span><span></span><span></span>
                        </div>
                        <div class="hb-loader-icon">汉</div>
                    </div>
                    <div class="hb-loading-text mt-2">Đang tải không gian làm việc...</div>
                `;
                document.body.appendChild(slideUpPage);

                // Force reflow
                void leftClone.offsetWidth;

                // 4. Trigger Animations
                setTimeout(() => {
                    leftClone.style.transform = 'translateX(-50vw) scale(0.95)';
                    leftClone.style.filter = 'brightness(0.5)';
                    
                    rightClone.style.transform = 'translateX(50vw) scale(0.95)';
                    rightClone.style.filter = 'brightness(0.5)';

                    slideUpPage.style.transform = 'translateY(-100vh)';
                }, 50);

                // 5. Actually redirect after animation completes
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 1300);
            }, 1200); // Wait 1.2s before splitting screen
        } else {
            // ERROR - Reset button and show error
            btnText.style.opacity = '1';
            btnText.innerText = 'Đăng nhập hệ thống';
            spinner.classList.add('d-none');
            spinner.classList.remove('d-inline-block');
            btn.style.pointerEvents = 'auto';
            btn.style.opacity = '1';
            
            const errorDiv = document.createElement('div');
            errorDiv.id = 'loginErrorMsg';
            errorDiv.className = 'alert alert-danger small py-2 mb-3';
            errorDiv.innerText = data.message || 'Có lỗi xảy ra.';
            document.getElementById('loginForm').insertBefore(errorDiv, document.getElementById('loginForm').firstChild);
        }
    })
    .catch(error => {
        console.error(error);
        btnText.style.opacity = '1';
        btnText.innerText = 'Đăng nhập hệ thống';
        spinner.classList.add('d-none');
        spinner.classList.remove('d-inline-block');
        btn.style.pointerEvents = 'auto';
        btn.style.opacity = '1';
        alert('Lỗi kết nối máy chủ!');
    });
});
