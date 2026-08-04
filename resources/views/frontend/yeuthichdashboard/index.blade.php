@extends('frontend.layouts.dashboard')

@section('title', 'Khóa học yêu thích — Hányǔ Bàn')

@push('styles')
    <link href="{{ asset('frontend/asset/css/chinesecourses.css') }}" rel="stylesheet" />
@endpush

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h1 class="font-head fw-bold fs-3 mb-1">
        Khóa học yêu thích
        <span class="zh" style="color: var(--primary)">收藏课程</span>
      </h1>
      <p class="mb-0" style="color: var(--text-muted)">
        Danh sách các khóa học bạn đã đánh dấu yêu thích để đăng ký sau.
      </p>
    </div>
  </div>

  @if($khoaHocYeuThichs->isEmpty())
    <div class="brand-card p-5 text-center">
      <div style="font-size: 3rem; color: var(--text-muted); margin-bottom: 1rem;">📭</div>
      <h3 class="font-head fw-bold fs-5">Chưa có khóa học yêu thích nào</h3>
      <p class="text-muted mb-4">Hãy khám phá các khóa học và bấm vào biểu tượng Trái tim để lưu lại nhé!</p>
      <a href="{{ route('khoahoc.index') }}" class="btn btn-primary rounded-pill px-4" style="background: var(--primary); border: none;">Khám phá khóa học ngay</a>
    </div>
  @else
    <div class="row g-3">
      @foreach($khoaHocYeuThichs as $khoaHoc)
        <div class="col-sm-6 col-md-4 course-item">
          <div class="course-card-dash">
            <div class="course-cover">
              @if(!empty($khoaHoc->anh_bia))
                <img src="{{ asset('storage/' . $khoaHoc->anh_bia) }}" alt="{{ $khoaHoc->ten_khoa_hoc }}">
              @else
                <span class="zh-placeholder">{{ mb_substr($khoaHoc->ten_khoa_hoc ?? 'KH', 0, 2) }}</span>
              @endif
              <span class="level-badge">
                {{ $khoaHoc->capDoHsk->ten_cap_do ?? 'Sơ cấp' }}
              </span>
              
              <!-- Nút Yêu thích đã kích hoạt sẵn màu đỏ ở trang này -->
              <button class="save-btn btn-favorite" data-id="{{ $khoaHoc->id }}" aria-label="Bỏ yêu thích" style="color: red;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="red" stroke="currentColor" stroke-width="2">
                  <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                </svg>
              </button>
            </div>
            
            <div class="course-body">
              <div class="d-flex justify-content-between align-items-center">
                  <span class="badge-soft bg-soft-primary">{{ $khoaHoc->tong_bai_hoc ?? 0 }} bài học</span>
                  <span class="fw-bold" style="color: var(--primary); font-size: 0.9rem;">
                      {{ $khoaHoc->gia > 0 ? number_format($khoaHoc->gia) . 'đ' : 'Miễn phí' }}
                  </span>
              </div>
              
              <div class="course-title">
                {{ $khoaHoc->ten_khoa_hoc ?? 'Khóa học' }}
              </div>
              
              <div class="course-meta">
                <span style="display: flex; align-items: center; gap: 0.3rem;"><span class="rating-stars" style="color: #fbbf24;">★★★★★</span></span>
                <span style="display: flex; align-items: center; gap: 0.3rem;">· {{ $khoaHoc->giaoViens->first()?->nguoiDung->ho_ten ?? 'Giảng viên' }}</span>
              </div>
              
              <div class="course-footer">
                <a href="{{ route('khoahoc.show', ['slug' => $khoaHoc->slug ?? '#']) }}" class="btn w-100 text-decoration-none text-center" style="background: var(--primary); color: #fff; border: none; border-radius: 999px; padding: 0.4rem; font-size: 0.85rem; font-weight: 700;">
                  Xem chi tiết
                </a>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <!-- Pagination -->
    @if($khoaHocYeuThichs->hasPages())
    <div class="mt-4">
      {{ $khoaHocYeuThichs->links('pagination::bootstrap-5') }}
    </div>
    @endif
  @endif

@endsection

@push('scripts')
<script>
  document.addEventListener("DOMContentLoaded", function () {
    /* ---------- Favorite Button AJAX ---------- */
    document.querySelectorAll('.btn-favorite').forEach(btn => {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        const courseId = this.getAttribute('data-id');
        const courseCard = this.closest('.course-item');
        
        // Lấy CSRF token
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!token) return;

        fetch(`/khoa-hoc/${courseId}/yeu-thich`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
          }
        })
        .then(res => res.json())
        .then(data => {
          if (data.success && data.status === 'removed') {
             // Ẩn/xóa card khóa học khỏi danh sách với hiệu ứng mờ dần
             courseCard.style.transition = "opacity 0.3s ease, transform 0.3s ease";
             courseCard.style.opacity = "0";
             courseCard.style.transform = "scale(0.9)";
             setTimeout(() => {
                 courseCard.remove();
                 // Tự reload nếu xóa hết thẻ trong DOM (không hoàn hảo vì phân trang nhưng dùng tạm)
                 if (document.querySelectorAll('.course-item').length === 0) {
                     location.reload();
                 }
             }, 300);
          }
        })
        .catch(err => console.error(err));
      });
    });
  });
</script>
@endpush
