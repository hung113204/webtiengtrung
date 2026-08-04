<!-- ================= TRIAL / AJAX FORM ================= -->
<section id="trial" class="section-pad" style="background:color-mix(in srgb, var(--primary) 3%, var(--bg));">
  <div class="container">
    <div class="row justify-content-center reveal">
      <div class="col-lg-7">
        <div class="brand-card p-4 p-md-5 text-center">
          <span class="eyebrow">Học thử miễn phí</span>
          <h2 class="font-head fw-bold mt-2 mb-3">Nhận ngay bài học đầu tiên</h2>
          <p class="mb-4" style="color:var(--text-muted);">Để lại email, chúng tôi gửi tài khoản học thử 7 ngày kèm 1 buổi luyện phát âm cùng AI gia sư.</p>
          <form id="trialForm" class="row g-2 justify-content-center" novalidate>
            <div class="col-12 col-sm-7">
              <label for="trialEmail" class="visually-hidden">Địa chỉ email</label>
              <input type="email" class="form-control form-control-lg" id="trialEmail" placeholder="ban@email.com" required style="border-radius:999px; border:1.5px solid var(--border);">
              <div class="invalid-feedback text-start ps-3">Vui lòng nhập email hợp lệ.</div>
            </div>
            <div class="col-12 col-sm-auto">
              <button type="submit" class="btn-brand btn-lg w-100" id="trialSubmitBtn">
                <span id="trialBtnText">Đăng ký học thử</span>
                <span id="trialSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
              </button>
            </div>
          </form>
          <p class="form-note mt-3 mb-0" id="trialFormNote">Không cần thẻ tín dụng. Hủy bất cứ lúc nào.</p>
        </div>
      </div>
    </div>
  </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const trialForm = document.getElementById('trialForm');
  if(trialForm) {
    trialForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const emailInput = document.getElementById('trialEmail');
      const email = emailInput.value.trim();
      
      if(!email) {
        emailInput.classList.add('is-invalid');
        return;
      }
      
      // Basic email regex
      if(!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        emailInput.classList.add('is-invalid');
        return;
      }
      
      emailInput.classList.remove('is-invalid');
      
      const submitBtn = document.getElementById('trialSubmitBtn');
      const btnText = document.getElementById('trialBtnText');
      const spinner = document.getElementById('trialSpinner');
      const note = document.getElementById('trialFormNote');
      
      // Loading state
      submitBtn.disabled = true;
      spinner.classList.remove('d-none');
      
      fetch('{{ route("frontend.dangkyhocthu") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'Accept': 'application/json'
        },
        body: JSON.stringify({ email: email })
      })
      .then(response => response.json())
      .then(data => {
        submitBtn.disabled = false;
        spinner.classList.add('d-none');
        
        if(data.status === 'success') {
          // Toast or simple text update
          if (typeof Swal !== 'undefined') {
            Swal.fire({
              icon: 'success',
              title: 'Thành công!',
              text: data.message,
              confirmButtonColor: 'var(--hb-primary)'
            });
          } else {
            alert(data.message);
          }
          emailInput.value = '';
          note.innerHTML = '<span class="text-success fw-bold">' + data.message + '</span>';
        } else {
          if (typeof Swal !== 'undefined') {
            Swal.fire({
              icon: 'error',
              title: 'Lỗi',
              text: data.message || 'Đã có lỗi xảy ra',
              confirmButtonColor: 'var(--hb-primary)'
            });
          } else {
            alert(data.message || 'Đã có lỗi xảy ra');
          }
          note.innerHTML = '<span class="text-danger">' + (data.message || 'Lỗi') + '</span>';
        }
      })
      .catch(err => {
        submitBtn.disabled = false;
        spinner.classList.add('d-none');
        alert('Lỗi kết nối. Vui lòng thử lại.');
      });
    });
  }
});
</script>
@endpush
