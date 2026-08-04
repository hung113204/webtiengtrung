<!-- ================= FOOTER ================= -->
<footer class="py-5">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-4">
        <a class="navbar-brand d-flex align-items-center gap-2 mb-3" href="#main">
          @php
              $logoUrl = \App\Models\CauHinh::getByKey('website_logo');
              $websiteName = \App\Models\CauHinh::getByKey('website_name', 'Hányǔ Bàn');
          @endphp
          @if($logoUrl)
              <img src="{{ Storage::url($logoUrl) }}" alt="{{ $websiteName }}" style="height: 36px; object-fit: contain; border-radius: 8px;">
          @else
              <span class="brand-mark zh">汉</span>
          @endif
          <span class="font-head fw-bold fs-5">{{ $websiteName }}</span>
        </a>
        <p class="small" style="color:var(--text-muted); max-width:320px;">Nền tảng học tiếng Trung toàn diện dành cho người Việt: từ vựng, ngữ pháp, luyện viết, luyện thi HSK và AI gia sư đồng hành.</p>
      </div>
      <div class="col-6 col-lg-2">
        <div class="footer-heading mb-3">Học tập</div>
        <ul class="list-unstyled d-grid gap-2">
          <li><a class="footer-link" href="#courses">Khóa học</a></li>
          <li><a class="footer-link" href="#roadmap">Lộ trình</a></li>
          <li><a class="footer-link" href="#">Luyện thi HSK</a></li>
          <li><a class="footer-link" href="#">Từ điển</a></li>
        </ul>
      </div>
      <div class="col-6 col-lg-2">
        <div class="footer-heading mb-3">Công ty</div>
        <ul class="list-unstyled d-grid gap-2">
          <li><a class="footer-link" href="#">Về chúng tôi</a></li>
          <li><a class="footer-link" href="blog.html">Blog</a></li>
          <li><a class="footer-link" href="#">Tuyển dụng</a></li>
          <li><a class="footer-link" href="#">Liên hệ</a></li>
        </ul>
      </div>
      <div class="col-6 col-lg-2">
        <div class="footer-heading mb-3">Hỗ trợ</div>
        <ul class="list-unstyled d-grid gap-2">
          <li><a class="footer-link" href="#faq">FAQ</a></li>
          <li><a class="footer-link" href="terms.html">Điều khoản</a></li>
          <li><a class="footer-link" href="#">Bảo mật</a></li>
        </ul>
      </div>
      <div class="col-6 col-lg-2">
        <div class="footer-heading mb-3">Kết nối</div>
        <ul class="list-unstyled d-grid gap-2">
          <li><a class="footer-link" href="#">Facebook</a></li>
          <li><a class="footer-link" href="#">Zalo</a></li>
          <li><a class="footer-link" href="#">YouTube</a></li>
        </ul>
      </div>
    </div>
    <hr style="border-color:var(--border);" class="my-4">
    <div class="d-flex flex-wrap justify-content-between small" style="color:var(--text-muted);">
      <span>© 2026 Hányǔ Bàn. Đã đăng ký bản quyền.</span>
      <span>Made with 心 for người Việt học tiếng Trung.</span>
    </div>
  </div>
</footer>
