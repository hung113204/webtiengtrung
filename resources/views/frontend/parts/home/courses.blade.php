<!-- ================= FEATURED COURSES ================= -->
<section id="courses" class="section-pad" style="background:color-mix(in srgb, var(--primary) 3%, var(--bg));">
  <div class="container">
    <div class="d-flex flex-wrap justify-content-between align-items-end mb-5 reveal">
      <div>
        <span class="eyebrow">Khóa học nổi bật</span>
        <h2 class="font-head fw-bold mt-2 mb-0">Chọn lộ trình phù hợp với bạn</h2>
      </div>
      <a href="{{ route('khoahoc.index') }}" class="btn-outline-brand mt-3 mt-md-0">Xem tất cả khóa học</a>
    </div>
    <div class="row g-4">
      @forelse($featuredCourses as $course)
      <div class="col-md-6 col-lg-3 reveal">
        <a href="{{ route('khoahoc.show', $course->slug) }}" class="d-block text-decoration-none text-reset h-100">
          <div class="brand-card course-card overflow-hidden h-100">
            <div class="cover" style="{{ $course->anh_bia ? 'background-image:url('.asset('storage/'.$course->anh_bia).');background-size:cover;' : '' }}">
              @if(!$course->anh_bia)<span class="zh">汉</span>@endif
              <span class="level-badge">{{ optional($course->capDoHSK)->ten_cap_do ?? 'Cơ bản' }}</span>
            </div>
            <div class="p-3">
              <h3 class="font-head fs-6 fw-bold mb-1">{{ $course->ten_khoa_hoc }}</h3>
              @php
                  $soBaiHoc = max($course->bai_hocs_count ?? 0, $course->tong_bai_hoc ?? 0);
                  $giaoVienTen = optional(optional($course->giaoViens->first())->nguoiDung)->ho_ten ?? 'Giáo viên Hányǔ Bàn';
              @endphp
              <p class="small mb-2" style="color:var(--text-muted);">{{ $soBaiHoc }} bài · {{ $giaoVienTen }}</p>
              <div class="d-flex justify-content-between align-items-center">
                @php
                  $rating = $course->danh_gias_avg_so_sao ? number_format($course->danh_gias_avg_so_sao, 1) : '5.0';
                  $ratingCount = $course->danh_gias_count ?? 0;
                @endphp
                <span class="rating-stars" style="color: #ffc107;">★★★★★ <span style="color:var(--text-muted);">{{ $rating }} ({{ $ratingCount }})</span></span>
                <div class="text-end" style="line-height: 1.2;">
                  @if($course->gia_giam > 0 && $course->gia_giam < $course->gia)
                    <span class="fw-bold text-primary-brand d-block">{{ number_format($course->gia_giam, 0, ',', '.') }}đ</span>
                    <span class="small text-muted text-decoration-line-through">{{ number_format($course->gia, 0, ',', '.') }}đ</span>
                  @else
                    <span class="fw-bold text-primary-brand">{{ number_format($course->gia, 0, ',', '.') }}đ</span>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
      @empty
      <div class="col-12 text-center py-5">
        <p class="text-muted">Chưa có khóa học nổi bật nào được thiết lập.</p>
      </div>
      @endforelse
    </div>
  </div>
</section>
