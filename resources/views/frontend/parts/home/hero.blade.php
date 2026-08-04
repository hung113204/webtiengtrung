<!-- ================= HERO ================= -->
<section class="hero">
  <div class="hero-bg-seal"></div>
  <div class="container position-relative">
    <div class="row align-items-center gy-5" style="padding-top:4.5rem;">
      <div class="col-lg-6">
        <span class="hero-eyebrow-badge">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.4 7.4H22l-6.2 4.5 2.4 7.4L12 16.8l-6.2 4.5 2.4-7.4L2 9.4h7.6z"/></svg>
          Dành riêng cho người Việt học tiếng Trung
        </span>
        <style>
          .scan-effect-wrap {
            display: inline-block;
            position: relative;
            white-space: nowrap;
          }
          .scan-effect-text {
            display: inline-block;
            clip-path: inset(-10px 0 -10px 0); /* Nới rộng trên dưới để không cắt dấu */
            animation: scanClip 2s ease-in-out infinite alternate;
          }
          .scan-effect-cursor {
            position: absolute;
            top: 2px;
            bottom: 2px;
            right: 0;
            width: 3px;
            background-color: #dc2626;
            animation: scanCursor 2s ease-in-out infinite alternate, blinkCursor 1s steps(2, start) infinite;
          }
          @keyframes scanClip {
            0%, 20% { clip-path: inset(-10px 0 -10px 0); }
            80%, 100% { clip-path: inset(-10px 100% -10px 0); }
          }
          @keyframes scanCursor {
            0%, 20% { right: 0%; }
            80%, 100% { right: calc(100% - 3px); }
          }
          @keyframes blinkCursor {
            0%, 100% { opacity: 1; }
            50% { opacity: 0; }
          }
          .seal-badge {
            animation: sealFloat 3s ease-in-out infinite alternate;
          }
          @keyframes sealFloat {
            0% { translate: 0 0; }
            100% { translate: 0 -15px; }
          }
        </style>
        <h1 class="font-head mt-3 mb-3">Học tiếng Trung theo <span class="scan-effect-wrap"><span class="text-primary-brand scan-effect-text">cách bạn thật sự nhớ</span><span class="scan-effect-cursor"></span></span></h1>
        <p class="pinyin-tag mb-2">xué zhōngwén, jì zhù měi yīgè zì — học tiếng Trung, nhớ từng con chữ</p>
        <p class="fs-6 mb-4" style="color:var(--text-muted); max-width:520px;">
          Từ vựng, ngữ pháp, luyện viết chữ Hán theo đúng nét bút, luyện nghe – nói – đọc, và ôn thi HSK — cùng một AI gia sư đồng hành mỗi ngày.
        </p>
        <div class="d-flex flex-wrap gap-3 mb-4">
          <a href="#trial" class="btn-brand btn-lg">Bắt đầu học miễn phí</a>
          <a href="#courses" class="btn-outline-brand btn-lg">Xem khóa học</a>
        </div>
        <div class="d-flex flex-wrap gap-4 gap-md-5">
          <div class="hero-stat"><div class="num" data-count="{{ $stats['students'] ?? 120000 }}">0</div><div class="lbl">Học viên</div></div>
          <div class="hero-stat"><div class="num" data-count="{{ $stats['lessons'] ?? 4500 }}">0</div><div class="lbl">Bài học</div></div>
          <div class="hero-stat"><div class="num" data-count="{{ $stats['satisfaction'] ?? 98 }}">0</div><div class="lbl">% hài lòng</div></div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="hero-visual">
          <div class="hero-grid-3">
            <div class="tianzige" id="hz-ni"></div>
            <div class="tianzige">
              <svg viewBox="0 0 60 60" width="46" height="46">
                <path class="stroke-anim" d="M12 15 H48 M30 8 V52 M18 30 Q30 46 42 30" stroke="var(--primary)" stroke-width="4" fill="none" stroke-linecap="round"/>
              </svg>
            </div>
            <div class="tianzige" id="hz-hao"></div>
            <div class="tianzige"><span class="char zh" style="font-size:1.5rem;">HSK</span></div>
            <div class="tianzige" id="hz-xue"></div>
            <div class="tianzige" id="hz-zhong"></div>
          </div>
          <div class="seal-badge zh">印</div>
        </div>
      </div>
    </div>
  </div>
</section>
