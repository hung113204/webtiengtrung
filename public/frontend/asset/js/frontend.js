document.addEventListener('DOMContentLoaded', function () {

  /* ---------- Theme toggle (Dark mode) ---------- */
  const root = document.documentElement;
  const themeToggle = document.getElementById('themeToggle');
  const savedTheme = localStorageSafeGet('hb-theme') || 'light';
  root.setAttribute('data-theme', savedTheme);

  themeToggle.addEventListener('click', function () {
    const current = root.getAttribute('data-theme');
    const next = current === 'light' ? 'dark' : 'light';
    root.setAttribute('data-theme', next);
    localStorageSafeSet('hb-theme', next);
  });

  // Guard localStorage in case of restricted environments
  function localStorageSafeGet(key){ try { return localStorage.getItem(key); } catch(e){ return null; } }
  function localStorageSafeSet(key, val){ try { localStorage.setItem(key, val); } catch(e){ /* no-op */ } }

  /* ---------- Animated counters ---------- */
  const counters = document.querySelectorAll('[data-count]');
  const counterObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        animateCount(entry.target);
        counterObserver.unobserve(entry.target);
      }
    });
  }, { threshold: 0.4 });
  counters.forEach(c => counterObserver.observe(c));

  function animateCount(el) {
    const target = parseInt(el.getAttribute('data-count'), 10);
    const duration = 1400;
    const start = performance.now();
    function tick(now) {
      const progress = Math.min((now - start) / duration, 1);
      const eased = 1 - Math.pow(1 - progress, 3);
      const value = Math.floor(eased * target);
      el.textContent = value.toLocaleString('vi-VN');
      if (progress < 1) requestAnimationFrame(tick);
      else el.textContent = target.toLocaleString('vi-VN');
    }
    requestAnimationFrame(tick);
  }

  /* ---------- Reveal on scroll ---------- */
  const revealEls = document.querySelectorAll('.reveal');
  const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('in');
        revealObserver.unobserve(entry.target);
      }
    });
  }, { threshold: 0.15 });
  revealEls.forEach(el => revealObserver.observe(el));

  /* ---------- AJAX: trial signup form ---------- */
  const trialForm = document.getElementById('trialForm');
  const trialEmail = document.getElementById('trialEmail');
  const trialBtn = document.getElementById('trialSubmitBtn');
  const trialBtnText = document.getElementById('trialBtnText');
  const trialSpinner = document.getElementById('trialSpinner');
  const trialNote = document.getElementById('trialFormNote');
  const ajaxToastEl = document.getElementById('ajaxToast');
  const ajaxToastBody = document.getElementById('ajaxToastBody');
  const ajaxToast = ajaxToastEl ? new bootstrap.Toast(ajaxToastEl, { delay: 4000 }) : null;

  if (trialForm) {
    trialForm.addEventListener('submit', function (e) {
      e.preventDefault();
  
      if (!trialEmail.checkValidity()) {
      trialEmail.classList.add('is-invalid');
      trialEmail.focus();
      return;
    }
    trialEmail.classList.remove('is-invalid');

    // UI: loading state
    trialBtn.disabled = true;
    trialBtnText.textContent = 'Đang gửi...';
    trialSpinner.classList.remove('d-none');

    // AJAX call (fetch) to a public demo endpoint to simulate a real request
    fetch('https://jsonplaceholder.typicode.com/posts', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        email: trialEmail.value,
        source: 'landing-page-trial-form'
      })
    })
      .then(function (res) {
        if (!res.ok) throw new Error('Network response was not ok');
        return res.json();
      })
      .then(function () {
        setToast('success', 'Đăng ký thành công! Kiểm tra email để nhận bài học đầu tiên.');
        trialNote.textContent = 'Đã gửi liên kết học thử đến ' + trialEmail.value;
        trialForm.reset();
      })
      .catch(function () {
        setToast('danger', 'Có lỗi xảy ra, vui lòng thử lại sau.');
      })
      .finally(function () {
        trialBtn.disabled = false;
        trialBtnText.textContent = 'Đăng ký học thử';
        trialSpinner.classList.add('d-none');
      });
    });
  }

  function setToast(type, message) {
    ajaxToastEl.classList.remove('text-bg-success', 'text-bg-danger');
    ajaxToastEl.classList.add(type === 'success' ? 'text-bg-success' : 'text-bg-danger');
    ajaxToastBody.textContent = message;
    ajaxToast.show();
  }

  /* ---------- Navbar shrink shadow on scroll ---------- */
  const nav = document.querySelector('.navbar-custom');
  window.addEventListener('scroll', function () {
    if (window.scrollY > 12) nav.style.boxShadow = '0 8px 24px -12px rgba(17,24,39,.18)';
    else nav.style.boxShadow = 'none';
  });

});
