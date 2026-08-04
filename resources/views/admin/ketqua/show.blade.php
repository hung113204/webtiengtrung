@extends('admin.layouts.main')

@section('title', 'Chi tiết bài thi #' . sprintf('%04d', $attempt->id) . ' — Hányǔ Admin')

@section('content')
<div class="mb-4">
  <a href="{{ route('admin.ketqua.index') }}" class="btn btn-sm btn-light border d-inline-flex align-items-center gap-2 px-3 py-2 rounded-3 text-secondary fw-medium">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
    Quay lại danh sách
  </a>
</div>

<!-- Header Detail Panel -->
<div class="row g-4 mb-4 animate-fade-in delay-1">
  <!-- Left Side: Result Card -->
  <div class="col-lg-8">
    <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
      <div class="card-body p-4">
        <div class="d-flex align-items-start justify-content-between mb-4">
          <div>
            <span class="badge rounded-pill px-3 py-2 fw-semibold mb-2" style="background: rgba(220, 38, 38, 0.08); color: var(--admin-primary);">
              {{ $attempt->deThi->loai_de ?? 'Đề thi' }}
            </span>
            <h2 class="fs-4 fw-bold mb-1 text-dark">{{ $attempt->deThi->ten_de_thi ?? 'N/A' }}</h2>
            <p class="text-muted small mb-0">Học viên: <strong class="text-dark">{{ $attempt->nguoiDung->ho_ten ?? 'N/A' }}</strong> ({{ $attempt->nguoiDung->email ?? 'N/A' }})</p>
          </div>
          <div>
            @if($attempt->trang_thai === 'Hoàn thành')
              <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-2 fw-semibold">Hoàn thành</span>
            @elseif($attempt->trang_thai === 'Đang làm')
              <span class="badge rounded-pill bg-warning bg-opacity-10 text-warning px-3 py-2 fw-semibold">Đang làm</span>
            @else
              <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger px-3 py-2 fw-semibold">Hết thời gian</span>
            @endif
          </div>
        </div>

        <div class="row g-4 text-center border-top pt-4">
          <div class="col-6 col-sm-3 border-end">
            <div class="text-muted small mb-1">Thời gian làm</div>
            <div class="fs-5 fw-bold text-dark">
              @if($duration !== null)
                {{ $duration }} <span class="fs-6 fw-normal text-muted">phút</span>
              @else
                --
              @endif
            </div>
          </div>
          <div class="col-6 col-sm-3 border-end">
            <div class="text-muted small mb-1">Số câu đúng</div>
            <div class="fs-5 fw-bold text-success">
              {{ $attempt->so_cau_dung }} <span class="fs-6 fw-normal text-muted">/ {{ $attempt->chiTietLuyenThis->count() }}</span>
            </div>
          </div>
          <div class="col-6 col-sm-3 border-end">
            <div class="text-muted small mb-1">Số câu sai</div>
            <div class="fs-5 fw-bold text-danger">
              {{ $attempt->so_cau_sai }}
            </div>
          </div>
          <div class="col-6 col-sm-3">
            <div class="text-muted small mb-1">Tỷ lệ chính xác</div>
            <div class="fs-5 fw-bold text-primary">
              @if($attempt->chiTietLuyenThis->count() > 0)
                {{ round(($attempt->so_cau_dung / $attempt->chiTietLuyenThis->count()) * 100) }}%
              @else
                0%
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Right Side: Score Card -->
  <div class="col-lg-4">
    <div class="card border-0 shadow-sm rounded-4 h-100 bg-white d-flex align-items-center justify-content-center text-center p-4">
      <div class="card-body py-4 w-100">
        <div class="text-muted small fw-semibold text-uppercase mb-2" style="letter-spacing: 0.05em;">Điểm đạt được</div>
        <div class="display-3 fw-black text-dark mb-2" style="letter-spacing: -0.03em;">
          {{ floatval($attempt->tong_diem) }}<span class="fs-4 text-muted fw-normal">đ</span>
        </div>
        
        @if($attempt->deThi && $attempt->deThi->diem_dat > 0)
          @if($attempt->tong_diem >= $attempt->deThi->diem_dat)
            <div class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-2 fw-medium mb-3">ĐẠT CHUẨN</div>
          @else
            <div class="badge rounded-pill bg-danger bg-opacity-10 text-danger px-3 py-2 fw-medium mb-3">KHÔNG ĐẠT CHUẨN</div>
          @endif
          <div class="small text-muted border-top pt-3">Điểm chuẩn tối thiểu: {{ $attempt->deThi->diem_dat }}đ</div>
        @else
          <div class="text-muted small border-top pt-3">Không quy định điểm chuẩn</div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Detailed Review Section -->
<div class="card border-0 shadow-sm rounded-4 bg-white animate-fade-in delay-2 mb-4">
  <div class="card-header bg-transparent border-light py-3 px-4">
    <h5 class="card-title fw-bold mb-0 text-dark">Đáp án chi tiết của học viên</h5>
  </div>
  <div class="card-body p-4">
    @forelse($attempt->chiTietLuyenThis as $index => $detail)
      @php
        $question = $detail->cauHoi;
        $isListening = str_contains(strtolower($question->loaiCauHoi->slug ?? ''), 'nghe') || str_contains(strtolower($question->loaiCauHoi->ten_loai ?? ''), 'nghe');
        $isWriting = str_contains(strtolower($question->loaiCauHoi->slug ?? ''), 'viet') || str_contains(strtolower($question->loaiCauHoi->ten_loai ?? ''), 'viết') || str_contains(strtolower($question->loaiCauHoi->ten_loai ?? ''), 'sắp xếp');
      @endphp
      <div class="p-4 mb-4 rounded-3 border @if($detail->dung) border-success bg-success bg-opacity-10 @else border-danger bg-danger bg-opacity-10 @endif" style="transition: all 0.2s;">
        <!-- Question Header Info -->
        <div class="d-flex justify-content-between align-items-start mb-3">
          <div class="d-flex align-items-center gap-2">
            <span class="badge @if($detail->dung) bg-success @else bg-danger @endif text-white rounded-1 fw-bold px-2 py-1" style="font-size: 0.8rem;">
              Câu {{ $index + 1 }}
            </span>
            <span class="badge bg-secondary rounded-1 px-2 py-1" style="font-size: 0.75rem;">
              {{ $question->loaiCauHoi->ten_loai ?? 'N/A' }}
            </span>
            <span class="badge bg-light text-muted border rounded-1 px-2 py-1" style="font-size: 0.75rem;">
              Mã: Q-{{ $question->id }}
            </span>
          </div>
          <div>
            @if($detail->dung)
              <span class="text-success fw-semibold d-flex align-items-center gap-1 small">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                Đúng
              </span>
            @else
              <span class="text-danger fw-semibold d-flex align-items-center gap-1 small">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                Sai
              </span>
            @endif
          </div>
        </div>

        <!-- Question Content -->
        <div class="mb-3">
          <h5 class="fw-bold text-dark mb-1" style="font-family: 'Noto Sans SC', sans-serif;">{{ $question->noi_dung }}</h5>
          @if($question->pinyin)
            <div class="text-muted small mb-1" style="font-family: 'Outfit', sans-serif;">{{ $question->pinyin }}</div>
          @endif
          @if($question->dich_nghia)
            <div class="text-muted small fst-italic">{{ $question->dich_nghia }}</div>
          @endif
        </div>

        <!-- Audio Player if Listening Question -->
        @if($isListening && $question->am_thanh)
          <div class="mb-3 p-2 bg-white rounded-3 border d-inline-flex align-items-center gap-2">
            <audio src="{{ asset($question->am_thanh) }}" controls class="w-100" style="height: 32px; max-width: 320px;"></audio>
          </div>
        @endif

        <!-- Image if Question has Image -->
        @if($question->hinh_anh)
          <div class="mb-3">
            <img src="{{ asset($question->hinh_anh) }}" class="img-fluid rounded-3 border" style="max-height: 180px; object-fit: contain;" alt="Hình ảnh câu hỏi">
          </div>
        @endif

        <!-- Options for Multiple Choice Questions -->
        @if($question->dapAns->count() > 0)
          <div class="row g-2 mt-2">
            @foreach($question->dapAns as $option)
              @php
                $isCorrectOption = $option->dung;
                $isUserSelected = $detail->id_dap_an == $option->id;
                
                // Color codes
                $bgColor = 'bg-white';
                $borderColor = 'border-light-subtle';
                $badgeText = '';
                
                if ($isCorrectOption) {
                    $bgColor = 'bg-success bg-opacity-25';
                    $borderColor = 'border-success';
                    $badgeText = '<span class="badge bg-success text-white rounded-pill px-2 py-1 ms-auto" style="font-size:0.7rem;">Đáp án đúng</span>';
                }
                
                if ($isUserSelected) {
                    if ($detail->dung) {
                        // User chose correct
                        $bgColor = 'bg-success bg-opacity-20';
                        $borderColor = 'border-success';
                    } else {
                        // User chose incorrect
                        $bgColor = 'bg-danger bg-opacity-20';
                        $borderColor = 'border-danger';
                        $badgeText .= '<span class="badge bg-danger text-white rounded-pill px-2 py-1 ms-2" style="font-size:0.7rem;">Bạn chọn</span>';
                    }
                }
              @endphp
              <div class="col-12">
                <div class="d-flex align-items-center gap-3 p-3 rounded-3 border {{ $bgColor }} {{ $borderColor }}" style="transition: all 0.2s;">
                  <div class="d-flex align-items-center justify-content-center border rounded-circle" style="width: 24px; height: 24px; font-weight: bold; font-size: 0.85rem; background: #fff;">
                    @if($isUserSelected)
                      <span class="@if($detail->dung) text-success @else text-danger @endif">●</span>
                    @else
                      <span></span>
                    @endif
                  </div>
                  <div>
                    <div class="fw-medium text-dark" style="font-family: 'Noto Sans SC', sans-serif;">{{ $option->noi_dung }}</div>
                    @if($option->pinyin)
                      <div class="text-muted small" style="font-size: 0.75rem;">{{ $option->pinyin }}</div>
                    @endif
                  </div>
                  {!! $badgeText !!}
                </div>
              </div>
            @endforeach
          </div>
        @else
          <!-- Essay or Short Answer -->
          <div class="mt-3">
            <div class="small fw-semibold text-dark mb-1">Câu trả lời tự luận của học viên:</div>
            <div class="p-3 bg-white rounded-3 border text-dark font-monospace" style="white-space: pre-wrap;">{{ $detail->dap_an_tu_luan ?: '(Không có câu trả lời)' }}</div>
          </div>
        @endif

        <!-- Explanation Block if incorrect or has explanation -->
        @if($question->giai_thich || $question->am_thanh_giai_thich)
          <div class="mt-4 pt-3 border-top text-muted small">
            <div class="fw-semibold text-dark mb-1">Giải thích đáp án:</div>
            @if($question->giai_thich)
              <div class="text-secondary" style="white-space: pre-wrap;">{{ $question->giai_thich }}</div>
            @endif
            @if($question->am_thanh_giai_thich)
              <div class="mt-2">
                <audio src="{{ asset($question->am_thanh_giai_thich) }}" controls style="height: 28px; max-width: 280px;"></audio>
              </div>
            @endif
          </div>
        @endif
      </div>
    @empty
      <div class="text-center py-5 text-muted">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mb-3 text-secondary"><line x1="9" y1="18" x2="15" y2="18"></line><line x1="10" y1="14" x2="14" y2="14"></line><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
        <div>Phiên luyện thi này không ghi nhận chi tiết đáp án câu hỏi nào.</div>
      </div>
    @endforelse
  </div>
</div>
@endsection
