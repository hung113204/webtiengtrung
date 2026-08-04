<!-- ================= TESTIMONIALS ================= -->
<section id="testimonials" class="section-pad">
  <div class="container">
    <div class="text-center mb-5 reveal">
      <span class="eyebrow">Học viên nói gì</span>
      <h2 class="font-head fw-bold mt-2">Câu chuyện từ người học thật</h2>
    </div>
    <div id="testimonialCarousel" class="carousel slide reveal" data-bs-ride="carousel" data-bs-interval="4000">
      <div class="carousel-inner pb-4">
        @if(isset($testimonials) && $testimonials->count() > 0)
            @foreach($testimonials->chunk(3) as $index => $chunk)
            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
              <div class="row g-4 justify-content-center">
                @foreach($chunk as $item)
                <div class="col-md-4">
                  <div class="testimonial-card text-center h-100 d-flex flex-column">
                    <p class="fs-6 mb-4" style="color:var(--text); min-height: 72px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">"{{ $item->noi_dung }}"</p>
                    <div class="d-flex align-items-center justify-content-center gap-2 mt-auto">
                      <div class="avatar-circle">{{ $item->avatar_chu_cai }}</div>
                      <div class="text-start">
                        <div class="fw-semibold small">{{ optional($item->nguoiDung)->ho_ten ?? 'Học viên ẩn danh' }}</div>
                        <div class="small" style="color:var(--text-muted); max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ optional($item->khoaHoc)->ten_khoa_hoc ?? 'Học viên' }}</div>
                      </div>
                    </div>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
            @endforeach
        @else
            <div class="carousel-item active">
                <p class="text-center text-muted py-5">Hiện chưa có đánh giá nào.</p>
            </div>
        @endif
      </div>
      <div class="d-flex justify-content-center gap-2 mt-3">
        <button class="btn btn-sm btn-outline-brand" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev" aria-label="Đánh giá trước">‹</button>
        <button class="btn btn-sm btn-outline-brand" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next" aria-label="Đánh giá kế tiếp">›</button>
      </div>
      <div class="text-center mt-5">
        <a href="testimonials.html" class="btn-outline-brand">Xem thêm hàng ngàn đánh giá khác</a>
      </div>
    </div>
  </div>
</section>
