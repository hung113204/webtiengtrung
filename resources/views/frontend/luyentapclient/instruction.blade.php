@extends('frontend.layouts.exam')

@section('title', 'Thông tin đề thi — Hányǔ Bàn')

@push('styles')
<link href="{{ asset('frontend/asset/css/hsk-exam.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div style="position: absolute; top: 1.5rem; left: 1.5rem;">
  <a href="{{ route('frontend.dashboard.luyentap') }}" class="btn btn-outline-secondary rounded-pill d-flex align-items-center gap-2" style="font-weight: 600; padding: 0.5rem 1rem; background: var(--card);">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <path d="M19 12H5M12 19l-7-7 7-7"/>
    </svg>
    Quay lại
  </a>
</div>

<div class="instruction-container" style="padding-top: 2rem;">

  <div class="instruction-card">
    <div class="instruction-header">
      <h1 class="font-head fw-bold fs-3 mb-2">{{ $deThi->ten_de_thi }}</h1>
      <p class="mb-0 opacity-75">Mức độ: {{ $deThi->mucDo->ten_muc_do ?? 'HSK' }}</p>
    </div>
    
    <div class="instruction-body">
      <div class="row g-3 mb-4">
        <div class="col-md-6">
          <div class="info-item">
            <div class="info-icon">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
              </svg>
            </div>
            <div>
              <div class="fw-semibold text-muted small">Thời gian làm bài</div>
              <div class="fw-bold fs-5">{{ $deThi->thoi_gian_lam }} phút</div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="info-item">
            <div class="info-icon">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
              </svg>
            </div>
            <div>
              <div class="fw-semibold text-muted small">Số lượng câu hỏi</div>
              <div class="fw-bold fs-5">{{ $deThi->so_cau ?? $deThi->cauHois->count() }} câu</div>
            </div>
          </div>
        </div>
      </div>

      <h3 class="font-head fs-5 fw-bold mb-3">Quy chế làm bài</h3>
      <div class="p-3 mb-4" style="background: rgba(239, 68, 68, 0.05); border-left: 4px solid var(--danger); border-radius: 4px;">
        <ul class="rules-list mb-0">
          <li>Bài thi sẽ tự động nộp khi hết thời gian. Hãy chú ý đồng hồ đếm ngược.</li>
          <li>Bạn có thể xem lại kết quả và đáp án chi tiết sau khi nộp bài.</li>
          <li>Tuyệt đối không tải lại (refresh) trang trong quá trình thi để tránh mất dữ liệu.</li>
          <li>Trang làm bài thi sẽ hiển thị toàn màn hình để giúp bạn tập trung cao nhất.</li>
        </ul>
      </div>

      <div class="text-center mt-5">
        <a href="{{ route('frontend.dashboard.luyentap.exam', $deThi->id) }}" class="btn btn-primary rounded-pill px-5 py-3 fs-5 fw-bold shadow" style="background: var(--primary); border: none;">
          Bắt đầu làm bài
        </a>
      </div>
    </div>
  </div>
</div>
@endsection
