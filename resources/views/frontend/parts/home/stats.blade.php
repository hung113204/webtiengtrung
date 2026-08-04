<!-- ================= STATS (seal style) ================= -->
<section class="section-pad">
  <div class="container">
    <div class="row g-4">
      <div class="col-6 col-lg-3 reveal"><div class="stat-seal"><div class="num" data-count="{{ $stats['students'] ?? 120000 }}">0</div><div class="lbl">Học viên đang học</div></div></div>
      <div class="col-6 col-lg-3 reveal"><div class="stat-seal"><div class="num" data-count="{{ $stats['lessons'] ?? 4500 }}">0</div><div class="lbl">Bài học tương tác</div></div></div>
      <div class="col-6 col-lg-3 reveal"><div class="stat-seal"><div class="num" data-count="{{ $stats['exams'] ?? 850 }}">0</div><div class="lbl">Đề thi thử HSK</div></div></div>
      <div class="col-6 col-lg-3 reveal"><div class="stat-seal"><div class="num" data-count="{{ $stats['satisfaction'] ?? 98 }}">0</div><div class="lbl">% học viên hài lòng</div></div></div>
    </div>
  </div>
</section>
