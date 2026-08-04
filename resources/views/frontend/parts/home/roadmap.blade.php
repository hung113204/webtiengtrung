<!-- ================= ROADMAP ================= -->
<section id="roadmap" class="section-pad" style="background:color-mix(in srgb, var(--primary) 3%, var(--bg));">
  <div class="container">
    @if(isset($loTrinh) && $loTrinh->giaiDoans->count() > 0)
    <div class="text-center mb-5 reveal">
      <span class="eyebrow">{{ $loTrinh->ten_lo_trinh }}</span>
      <h2 class="font-head fw-bold mt-2">{{ $loTrinh->mo_ta_ngan }}</h2>
    </div>
    <div class="position-relative reveal">
      <div class="roadmap-line d-none d-md-block"></div>
      <div class="row g-4 text-center justify-content-center">
        @foreach($loTrinh->giaiDoans as $gd)
        <div class="col-6 col-md-2">
          @php
            $isChinese = preg_match('/\p{Han}/u', $gd->icon_text);
            $isFilled = $loop->iteration <= 2 ? 'filled' : '';
          @endphp
          <div class="roadmap-node {{ $isFilled }} {{ $isChinese ? 'zh' : '' }}">{{ $gd->icon_text }}</div>
          <p class="small fw-semibold mt-2 mb-0">{{ $gd->ten_giai_doan }}</p>
          <p class="small" style="color:var(--text-muted);">{{ $gd->mo_ta }}</p>
        </div>
        @endforeach
      </div>
    </div>
    <div class="text-center mt-5 reveal">
      <a href="#" class="btn-outline-brand">Xem chi tiết Lộ trình học tập</a>
    </div>
    @endif
  </div>
</section>
