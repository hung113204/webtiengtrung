@extends('frontend.layouts.dashboard')

@section('title', 'Phòng Luyện Thi HSK — Hányǔ Bàn')

@section('content')
<div class="row g-3 mb-4">
  <div class="col-12">
    <h1 class="font-head fw-bold fs-3 mb-1">
      Phòng Luyện Thi HSK
      <span class="zh" style="color: var(--primary)">考试</span>
    </h1>
    <p class="mb-0" style="color: var(--text-muted)">
      Kiểm tra trình độ của bạn qua các bài thi thử.
    </p>
  </div>
</div>

<div class="row mb-4 align-items-center">
  <div class="col-md-6 text-md-end">
    <div class="d-flex gap-2">
      <select class="form-select bg-white filter-select" id="levelFilter" style="width: auto; min-width: 180px; border-color: var(--border); box-shadow: none;">
        <option value="" style="padding: 10px 0; font-weight: 500;">Cấp độ HSK (Tất cả)</option>
        @foreach($mucDos as $mucDo)
          <option value="{{ $mucDo->id }}" style="padding: 10px 0;" {{ request('level') == $mucDo->id ? 'selected' : '' }}>{{ $mucDo->ten_muc_do }}</option>
        @endforeach
      </select>
    </div>
  </div>
</div>

<div class="row g-4">
  @forelse($deThis as $deThi)
  <!-- Exam Card -->
  <div class="col-md-6 col-lg-4">
    <div class="brand-card h-100 d-flex flex-column hover-lift">
      <div class="p-4 flex-grow-1">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <span class="badge" style="background: rgba(220, 38, 38, 0.1); color: var(--primary);">
            {{ $deThi->mucDo->ten_muc_do ?? 'HSK' }}
          </span>
          <span class="small text-muted">{{ $deThi->thoi_gian_lam }} Phút</span>
        </div>
        <h3 class="font-head fs-5 fw-bold mb-2">{{ $deThi->ten_de_thi }}</h3>
        <p class="small mb-3" style="color:var(--text-muted);">
          {{ $deThi->so_cau }} câu hỏi. {{ $deThi->mo_ta }}
        </p>
        <div class="d-flex flex-wrap gap-2 mb-3">
          <span class="badge bg-light text-dark border">Nghe</span>
          <span class="badge bg-light text-dark border">Đọc</span>
          <span class="badge bg-light text-dark border">Viết</span>
        </div>
      </div>
      <div class="p-3 border-top text-center" style="border-color: var(--border) !important;">
        <a href="{{ route('frontend.dashboard.luyentap.show', $deThi->id) }}" class="btn-brand w-100">Bắt đầu làm bài</a>
      </div>
    </div>
  </div>
  @empty
  <div class="col-12 text-center text-muted py-5">
      <p>Chưa có đề thi nào trong hệ thống.</p>
  </div>
  @endforelse
</div>
@endsection

@push('scripts')
<style>
.hover-lift {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.05);
}
</style>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const levelFilter = document.getElementById('levelFilter');
    if (levelFilter) {
        levelFilter.addEventListener('change', function() {
            const url = new URL(window.location.href);
            if (this.value) {
                url.searchParams.set('level', this.value);
            } else {
                url.searchParams.delete('level');
            }
            window.location.href = url.toString();
        });
    }
});
</script>
@endpush
