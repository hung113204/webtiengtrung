@extends('frontend.layouts.learning')

@section('title', $baiHoc->ten_bai_hoc . ' — Hányǔ Bàn')

@push('styles')
<link href="{{ asset('frontend/asset/css/frontend.css') }}" rel="stylesheet">
<link href="{{ asset('frontend/asset/css/learning.css') }}" rel="stylesheet">
<link href="{{ asset('frontend/asset/css/flashcard.css') }}" rel="stylesheet">
<style>
  .video-wrapper {
    position: relative;
    width: 100%;
    aspect-ratio: 16/9;
    background: #000;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
  }
  
  /* Quiz Styles */
  .tq-progress { font-weight: 600; color: var(--bs-primary); margin-bottom: 1rem; }
  .tq-question { font-size: 1.15rem; font-weight: 600; margin-bottom: 1.5rem; color: var(--bs-heading-color, #212529); }
  .tq-option { padding: 1rem; border: 2px solid var(--bs-border-color); border-radius: 8px; margin-bottom: 0.75rem; cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; background: #fff; }
  .tq-option:hover { border-color: var(--bs-primary); background: rgba(13, 110, 253, 0.05); }
  .tq-option.correct { border-color: var(--bs-success); background: rgba(25, 135, 84, 0.1); }
  .tq-option.wrong { border-color: var(--bs-danger); background: rgba(220, 53, 69, 0.1); }
  .tq-letter { width: 32px; height: 32px; border-radius: 50%; background: rgba(0,0,0,0.05); display: flex; align-items: center; justify-content: center; margin-right: 1rem; font-weight: 600; font-size: 0.9rem; }
  .tq-feedback { margin-top: 1.5rem; padding: 1rem; border-radius: 8px; font-weight: 500; display: none; }
  .tq-feedback.show { display: block; }
  .tq-result { text-align: center; padding: 2rem 0; }
</style>
@endpush

@section('content')
<!-- Navbar for Learning Mode -->
<header class="learning-navbar">
  <div class="d-flex align-items-center gap-3" style="flex: 1;">
    <a href="{{ route('frontend.dashboard.khoahoc') }}" class="back-btn" title="Trở về trang Khóa học của tôi">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
      <span class="d-none d-md-inline">Trở về</span>
    </a>
    <div style="width: 1px; height: 24px; background: var(--border);"></div>
    <h1 class="course-header-title">{{ $khoaHoc->ten_khoa_hoc }}</h1>
  </div>
  
  <div class="d-flex align-items-center gap-3">
    <!-- Theme Toggle -->
    <button class="theme-toggle" id="themeToggle" aria-label="Chuyển chế độ sáng/tối" type="button" style="width: 36px; height: 36px;">
      <svg id="iconMoon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
    </button>
    <div class="d-none d-md-flex align-items-center gap-2">
      <div class="progress-thin" style="width: 80px; margin: 0;">
        <div class="fill" id="progressTopFill" style="width: {{ $phanTramKhoaHoc }}%;"></div>
      </div>
      <span class="small fw-semibold" id="progressTopText" style="color: var(--text-muted);">{{ $phanTramKhoaHoc }}%</span>
    </div>
  </div>
</header>

<div class="learning-layout">
  
  <!-- Left Main Area (Video & Info) -->
  <main class="learning-main">
    <div class="container-fluid p-0" style="max-width: 1000px; margin: 0 auto;">
      
      <!-- Video Player or CTA -->
      <div class="video-wrapper" style="position: relative; width: 100%; background: {{ in_array($baiHoc->loai_dieu_kien, ['kiem_tra', 'phat_am_ai']) ? '#f8fafc' : '#000' }};">
        @if($baiHoc->loai_dieu_kien === 'phat_am_ai')
            <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 2rem; text-align: center;">
                <div class="mb-4">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color: var(--bs-primary);"><path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3z"></path><path d="M19 10v2a7 7 0 0 1-14 0v-2"></path><line x1="12" y1="19" x2="12" y2="22"></line></svg>
                </div>
                <h3 class="fw-bold mb-3 text-dark">Thực hành phát âm với AI</h3>
                @if($tienDo->da_hoan_thanh)
                    <p class="text-success fw-bold mb-4"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg> Bạn đã hoàn thành bài thực hành này!</p>
                @else
                    <p class="text-muted mb-4">AI sẽ lắng nghe và chấm điểm phát âm của bạn ngay lập tức.</p>
                    <a href="{{ route('frontend.dashboard.khoahoc.pronunciation', ['courseSlug' => $khoaHoc->slug, 'lessonSlug' => $baiHoc->slug]) }}" class="btn-brand px-4 py-2 fs-6 text-decoration-none d-inline-block">Bắt đầu luyện tập</a>
                @endif
            </div>
        @elseif($baiHoc->loai_dieu_kien === 'kiem_tra')
            <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 2rem; text-align: center;">
                <div class="mb-4">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color: var(--bs-primary);"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                </div>
                <h3 class="fw-bold mb-3 text-dark">Bài kiểm tra cuối bài</h3>
                @if($tienDo->da_hoan_thanh)
                    <p class="text-success fw-bold mb-4"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg> Bạn đã hoàn thành bài kiểm tra này!</p>
                @else
                    <p class="text-muted mb-4">Bài học này có <strong>{{ $baiHoc->cauHois->count() }}</strong> câu hỏi ôn tập.</p>
                    <a href="{{ route('frontend.dashboard.khoahoc.quiz', ['courseSlug' => $khoaHoc->slug, 'lessonSlug' => $baiHoc->slug]) }}" class="btn-brand px-4 py-2 fs-6 text-decoration-none d-inline-block">Bắt đầu làm bài kiểm tra</a>
                @endif
            </div>
        @elseif($baiHoc->video)
            @php
                $videoRaw = $baiHoc->video;
                $isExternalUrl = filter_var($videoRaw, FILTER_VALIDATE_URL) !== false;
                $embedUrl = null;
                
                if ($isExternalUrl) {
                    if (preg_match('#youtu\.be/([a-zA-Z0-9_-]+)#', $videoRaw, $m) ||
                        preg_match('#youtube\.com/watch\?v=([a-zA-Z0-9_-]+)#', $videoRaw, $m) ||
                        preg_match('#youtube\.com/embed/([a-zA-Z0-9_-]+)#', $videoRaw, $m)) {
                        $embedUrl = 'https://www.youtube.com/embed/' . $m[1] . '?origin=https://plyr.io&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1';
                    } elseif (preg_match('#vimeo\.com/(\d+)#', $videoRaw, $m)) {
                        $embedUrl = 'https://player.vimeo.com/video/' . $m[1];
                    }
                }
            @endphp
            
            @if($embedUrl)
                <div class="plyr__video-embed" id="player" style="width: 100%; height: 100%;">
                    <iframe src="{{ $embedUrl }}" frameborder="0" allowfullscreen allowtransparency allow="autoplay" style="width: 100%; height: 100%;"></iframe>
                </div>
            @else
                <video id="player" controls playsinline style="width: 100%; height: 100%; object-fit: contain; background-color: #000;" controlsList="nodownload">
                    @if($baiHoc->hls_path)
                        <source src="{{ asset('storage/' . $baiHoc->hls_path) }}" type="application/x-mpegURL">
                    @elseif($isExternalUrl)
                        <source src="{{ $videoRaw }}" type="video/mp4">
                    @else
                        <source src="{{ asset('storage/' . $videoRaw) }}" type="video/mp4">
                    @endif
                    
                    @if(isset($baiHoc->metadata['vtt_path']))
                        <track kind="captions" label="Tiếng Trung (AI)" src="{{ asset('storage/' . $baiHoc->metadata['vtt_path']) }}" srclang="zh" default>
                    @endif
                    
                    @if(isset($baiHoc->metadata['vtt_vn_path']))
                        <track kind="captions" label="Tiếng Việt (AI)" src="{{ asset('storage/' . $baiHoc->metadata['vtt_vn_path']) }}" srclang="vi">
                    @endif
                </video>
            @endif
        @else
            <img src="{{ $khoaHoc->anh_bia ? asset('storage/' . $khoaHoc->anh_bia) : 'https://images.unsplash.com/photo-1543269865-cbf427effbad?q=80&w=1200&auto=format&fit=crop' }}" style="position: absolute; width: 100%; height: 100%; object-fit: cover; opacity: 0.8;" alt="Video Thumbnail">
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 64px; height: 64px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 4px 15px rgba(220, 38, 38, 0.4);">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="#fff" style="margin-left: 4px;"><polygon points="5 3 19 12 5 21 5 3"/></svg>
            </div>
        @endif
      </div>

      <!-- Navigation & Title -->
      <div class="video-nav">
        <div class="lesson-info">
          <h2>{{ $baiHoc->ten_bai_hoc }}</h2>
          <p>{{ $baiHoc->chuongHoc->ten_chuong ?? '' }} • {{ $baiHoc->thoi_luong_giay ? floor($baiHoc->thoi_luong_giay / 60) . ':' . str_pad($baiHoc->thoi_luong_giay % 60, 2, '0', STR_PAD_LEFT) : '00:00' }}</p>
        </div>
        <div class="d-flex gap-2 align-items-center flex-shrink-0">
          <!-- Toggle Đánh dấu hoàn thành -->
          <!-- Progress Indicator & Toggle -->
          @php
              $isXemVideo = $baiHoc->loai_dieu_kien === 'xem_video';
              $isCompleted = $tienDo->da_hoan_thanh;
              $showCircularProgress = $isXemVideo && !$isCompleted;
              $tooltipTitle = $showCircularProgress ? 'Bạn cần xem ít nhất ' . $baiHoc->phan_tram_video . '% thời lượng video để hoàn thành' : 'Đánh dấu hoàn thành bài học';
          @endphp

          <!-- Vòng tròn tiến độ (chỉ hiện khi xem_video và chưa hoàn thành) -->
          <div id="videoProgressContainer" class="me-3 mb-0 d-none d-md-flex align-items-center" style="display: {{ $showCircularProgress ? 'flex !important' : 'none !important' }};" title="{{ $tooltipTitle }}">
            <div class="circular-progress" style="--progress: 0deg; width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: conic-gradient(var(--bs-primary, #0d6efd) var(--progress), #e2e8f0 0deg); position: relative; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <div style="width: 28px; height: 28px; border-radius: 50%; background-color: #fff; display: flex; align-items: center; justify-content: center;">
                    <span id="videoProgressText" style="font-size: 0.65rem; font-weight: 700; color: var(--bs-primary, #0d6efd);">0%</span>
                </div>
            </div>
            <span class="ms-2 small fw-semibold" style="color: var(--bs-primary, #0d6efd); padding-top: 2px;">Đang học...</span>
          </div>

          <!-- Toggle Đánh dấu hoàn thành (ẩn khi đang đếm tiến độ video) -->
          <div id="markCompletedContainer" class="form-check form-switch me-2 mb-0 d-none d-md-flex align-items-center" style="display: {{ $showCircularProgress ? 'none !important' : 'flex !important' }};">
            <input class="form-check-input mt-0" type="checkbox" role="switch" id="markCompletedSwitch" 
                   style="cursor: {{ $isCompleted ? 'not-allowed' : 'pointer' }};" 
                   {{ $isCompleted ? 'checked disabled' : '' }} 
                   data-url="{{ route('frontend.dashboard.khoahoc.progress', $baiHoc->id) }}" 
                   title="{{ $isCompleted ? 'Bạn đã hoàn thành bài học này' : 'Đánh dấu hoàn thành bài học' }}">
            <label class="form-check-label ms-2 small fw-semibold {{ $isCompleted ? 'text-success' : 'text-muted' }}" for="markCompletedSwitch" 
                   style="cursor: {{ $isCompleted ? 'not-allowed' : 'pointer' }}; padding-top: 2px;" 
                   title="{{ $isCompleted ? 'Bạn đã hoàn thành bài học này' : 'Đánh dấu hoàn thành bài học' }}">
                   {{ $isCompleted ? 'Đã hoàn thành' : 'Hoàn thành bài học' }}
            </label>
          </div>
          
          <a href="{{ $prevLesson ? route('frontend.dashboard.khoahoc.show', ['courseSlug' => $khoaHoc->slug, 'lessonSlug' => $prevLesson->slug]) : '#' }}" class="btn btn-outline-secondary text-nowrap d-flex align-items-center justify-content-center {{ $prevLesson ? '' : 'disabled' }}" style="padding: 8px 16px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
            <span class="d-none d-sm-inline ms-1">Bài trước</span>
          </a>
          <a href="{{ $nextLesson ? route('frontend.dashboard.khoahoc.show', ['courseSlug' => $khoaHoc->slug, 'lessonSlug' => $nextLesson->slug]) : '#' }}" class="btn-brand text-nowrap text-decoration-none {{ $nextLesson ? '' : 'disabled' }}" style="display: flex; align-items: center; justify-content: center; padding: 8px 16px;">
            <span class="d-none d-sm-inline me-1">Bài tiếp</span>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
          </a>
        </div>
      </div>

      <!-- Tabs -->
      <div class="learning-tabs">
        <button class="learning-tab active" data-target="#tab-overview">Tổng quan</button>
        <button class="learning-tab" data-target="#tab-vocab">Từ vựng ({{ $baiHoc->tuVungs->count() }})</button>
        <button class="learning-tab" data-target="#tab-resources">Tài liệu đính kèm (0)</button>
        <button class="learning-tab" data-target="#tab-qa">Hỏi đáp & Thảo luận ({{ $baiHoc->binhLuans->count() }})</button>
        @if($baiHoc->cauHois->count() > 0 && $baiHoc->loai_dieu_kien !== 'phat_am_ai')
        <button class="learning-tab" data-target="#tab-quiz">Bài kiểm tra ({{ $baiHoc->cauHois->count() }})</button>
        @endif
        @if($baiHoc->loai_dieu_kien === 'phat_am_ai')
        <button class="learning-tab" data-target="#tab-pronunciation">Luyện phát âm AI ({{ $baiHoc->cauHois->count() }})</button>
        @endif
      </div>

      <!-- Tab Content: Overview -->
      <div class="tab-pane active" id="tab-overview">
        <h4 class="font-head fw-bold fs-5 mb-3">Mô tả bài học</h4>
        <div class="lesson-content">
            @if(!empty($baiHoc->noi_dung))
                {!! $baiHoc->noi_dung !!}
            @else
                <p>Chào mừng bạn đến với bài học đầu tiên trong lộ trình giao tiếp tiếng Trung cơ bản. Trong bài này, chúng ta sẽ cùng tìm hiểu về hệ thống ngữ âm Pinyin (Bính âm), cấu tạo của một âm tiết trong tiếng Trung, và bắt đầu làm quen với hệ thống Vận mẫu đơn (nguyên âm).</p>
                <p><strong>Mục tiêu:</strong></p>
                <ul>
                <li>Hiểu được vai trò của Pinyin.</li>
                <li>Đọc chuẩn xác 6 vận mẫu đơn: a, o, e, i, u, ü.</li>
                <li>Thực hành khẩu hình miệng theo video hướng dẫn.</li>
                </ul>
            @endif
        </div>
      </div>

      <!-- Tab Content: Vocabulary -->
      <div class="tab-pane" id="tab-vocab">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h4 class="font-head fw-bold fs-5 mb-0">Từ vựng bài này</h4>
          <button class="btn btn-sm btn-brand" onclick="openFlashcardSession()">Học qua Flashcard</button>
        </div>
        <p class="small text-muted mb-4">Các từ vựng trọng tâm xuất hiện trong video bài học này.</p>
        <div id="lessonVocabList">
          <!-- Will be injected by JS -->
        </div>
      </div>

      <!-- Tab Content: Resources -->
      <div class="tab-pane" id="tab-resources">
        <h4 class="font-head fw-bold fs-5 mb-3">Tài liệu tham khảo</h4>
        
        <div class="resource-item">
          <div class="resource-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
          </div>
          <div class="flex-1" style="flex: 1;">
            <div class="fw-semibold">Slide bài giảng (PDF)</div>
            <div class="small" style="color: var(--text-muted);">1.2 MB • Bảng chữ cái Pinyin</div>
          </div>
          <button class="btn btn-sm btn-outline-brand">Tải xuống</button>
        </div>

        <div class="resource-item">
          <div class="resource-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 3H5a2 2 0 0 0-2 2v4m6-6h10a2 2 0 0 1 2 2v4M9 3v18m0 0h10a2 2 0 0 0 2-2V9M9 21H5a2 2 0 0 1-2-2V9m0 0h18"/></svg>
          </div>
          <div class="flex-1" style="flex: 1;">
            <div class="fw-semibold">Bài tập luyện khẩu hình (Audio)</div>
            <div class="small" style="color: var(--text-muted);">3.4 MB • MP3</div>
          </div>
          <button class="btn btn-sm btn-outline-brand">Tải xuống</button>
        </div>
      </div>

      <!-- Tab Content: Q&A -->
      <div class="tab-pane" id="tab-qa">
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        <form action="{{ route('frontend.dashboard.khoahoc.comment', $baiHoc->id) }}" method="POST" class="comment-box">
          @csrf
          <div class="avatar-sm">{{ strtoupper(substr(Auth::user()->ho_ten ?? 'U', 0, 1)) }}</div>
          <div class="comment-input-wrapper">
            <textarea name="noi_dung" class="comment-input" placeholder="Bạn có câu hỏi gì về bài học này không? Đặt câu hỏi để giáo viên hỗ trợ nhé..." required></textarea>
            <div class="d-flex justify-content-end">
              <button type="submit" class="btn-brand btn-sm">Gửi câu hỏi</button>
            </div>
          </div>
        </form>

        <h4 class="font-head fw-bold fs-6 border-bottom pb-2 mb-4" style="color: var(--text); border-color: var(--border);">{{ $baiHoc->binhLuans->count() }} Bình luận</h4>

        @forelse($baiHoc->binhLuans as $binhLuan)
        <div class="discussion-item">
          <div class="discussion-avatar">
            @if($binhLuan->nguoiDung->anh_dai_dien)
                <img src="{{ asset('storage/' . $binhLuan->nguoiDung->anh_dai_dien) }}" alt="Avatar">
            @else
                <div class="avatar-sm" style="width: 40px; height: 40px; border-radius: 50%; background: var(--primary); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                    {{ strtoupper(substr($binhLuan->nguoiDung->ho_ten ?? 'U', 0, 1)) }}
                </div>
            @endif
          </div>
          <div class="discussion-content w-100">
            <h4>{{ $binhLuan->nguoiDung->ho_ten ?? 'Người dùng ẩn danh' }}</h4>
            <div class="discussion-meta">{{ $binhLuan->created_at->diffForHumans() }}</div>
            <div class="discussion-text">{{ $binhLuan->noi_dung }}</div>
            <div class="discussion-actions">
              <button class="action-btn"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/></svg> 0</button>
              <button class="action-btn"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg> Phản hồi</button>
            </div>
            
            @foreach($binhLuan->replies as $reply)
            <!-- Reply -->
            <div class="discussion-item border-0 mt-3 pb-0">
              <div class="discussion-avatar">
                @if($reply->nguoiDung->anh_dai_dien)
                    <img src="{{ asset('storage/' . $reply->nguoiDung->anh_dai_dien) }}" alt="Avatar">
                @else
                    <div class="avatar-sm" style="width: 40px; height: 40px; border-radius: 50%; background: var(--secondary); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                        {{ strtoupper(substr($reply->nguoiDung->ho_ten ?? 'U', 0, 1)) }}
                    </div>
                @endif
              </div>
              <div class="discussion-content w-100">
                <h4>{{ $reply->nguoiDung->ho_ten ?? 'Người dùng ẩn danh' }}
                    @if($reply->nguoiDung->role == 'admin' || $reply->nguoiDung->role == 'teacher')
                        <span class="badge bg-primary ms-1" style="font-size: 0.65rem;">Giáo viên</span>
                    @endif
                </h4>
                <div class="discussion-meta">{{ $reply->created_at->diffForHumans() }}</div>
                <div class="discussion-text">{{ $reply->noi_dung }}</div>
                <div class="discussion-actions">
                  <button class="action-btn"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/></svg> 0</button>
                </div>
              </div>
            </div>
            @endforeach

          </div>
        </div>
        @empty
        <div class="text-center text-muted py-4">Chưa có câu hỏi nào. Hãy là người đầu tiên đặt câu hỏi!</div>
        @endforelse
      </div>

      <!-- Tab Content: Quiz -->
      @if($baiHoc->cauHois->count() > 0 && $baiHoc->loai_dieu_kien !== 'phat_am_ai')
      <div class="tab-pane" id="tab-quiz">
          <div class="card shadow-sm border-0 mb-4" style="background: var(--bg);">
              <div class="card-body p-5 text-center">
                  <div class="mb-4">
                      <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color: var(--bs-primary);"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                  </div>
                  <h3 class="fw-bold mb-3">Bài kiểm tra cuối bài</h3>
                  <p class="text-muted mb-4">Bài học này có <strong>{{ $baiHoc->cauHois->count() }}</strong> câu hỏi ôn tập. Bạn cần làm bài kiểm tra ở một giao diện riêng biệt để có trải nghiệm tốt nhất.</p>
                  <a href="{{ route('frontend.dashboard.khoahoc.quiz', ['courseSlug' => $khoaHoc->slug, 'lessonSlug' => $baiHoc->slug]) }}" class="btn-brand px-4 py-2 fs-6 text-decoration-none d-inline-block">Bắt đầu làm bài kiểm tra</a>
              </div>
          </div>
      </div>
      @endif

      <!-- Tab Content: Pronunciation -->
      @if($baiHoc->loai_dieu_kien === 'phat_am_ai')
      <div class="tab-pane" id="tab-pronunciation">
          <div class="card shadow-sm border-0 mb-4" style="background: var(--bg);">
              <div class="card-body p-5 text-center">
                  <div class="mb-4">
                      <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color: var(--bs-primary);"><path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3z"></path><path d="M19 10v2a7 7 0 0 1-14 0v-2"></path><line x1="12" y1="19" x2="12" y2="22"></line></svg>
                  </div>
                  <h3 class="fw-bold mb-3">Thực hành phát âm với AI</h3>
                  <p class="text-muted mb-4">Luyện nói tiếng Trung chuẩn như người bản xứ. AI sẽ lắng nghe và chấm điểm phát âm của bạn ngay lập tức.</p>
                  <a href="{{ route('frontend.dashboard.khoahoc.pronunciation', ['courseSlug' => $khoaHoc->slug, 'lessonSlug' => $baiHoc->slug]) }}" class="btn-brand px-4 py-2 fs-6 text-decoration-none d-inline-block">Bắt đầu luyện tập</a>
              </div>
          </div>
      </div>
      @endif

    </div>
  </main>

  <!-- Right Sidebar (Playlist) -->
  <aside class="learning-sidebar">
    <div class="sidebar-header">
      <h3>Nội dung khóa học</h3>
      <div class="progress-thin mt-2 mb-1">
        <div class="fill" id="progressSidebarFill" style="width: {{ $phanTramKhoaHoc }}%;"></div>
      </div>
      <div class="d-flex justify-content-between small" style="color: var(--text-muted);">
        <span id="progressSidebarCountText">Đã học {{ $soBaiDaHoc }}/{{ $tongSoBai }} bài</span>
        <span id="progressSidebarPercentText">{{ $phanTramKhoaHoc }}%</span>
      </div>
    </div>

    <div class="playlist-content">
      
      @foreach($khoaHoc->chuongHocs as $chuong)
      <div class="chapter-wrapper">
        <div class="chapter-header {{ $chuong->id == $baiHoc->id_chuong ? 'open' : '' }}">
          <div>
            <div class="chapter-title">{{ $chuong->ten_chuong }}</div>
            <div class="chapter-meta">{{ $chuong->baiHocs->count() }} bài học</div>
          </div>
          <svg class="chapter-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
        </div>
        <div class="lesson-list {{ $chuong->id == $baiHoc->id_chuong ? 'show' : '' }}">
          @foreach($chuong->baiHocs as $bh)
          @php $isCompleted = in_array($bh->id, $danhSachBaiDaHoc); @endphp
          <a href="{{ route('frontend.dashboard.khoahoc.show', ['courseSlug' => $khoaHoc->slug, 'lessonSlug' => $bh->slug]) }}" class="playlist-lesson {{ $bh->id == $baiHoc->id ? 'active' : '' }} {{ $isCompleted ? 'completed' : '' }}" {!! $bh->id == $baiHoc->id ? 'id="current-lesson-item"' : '' !!} style="text-decoration: none; color: inherit;">
            @if($isCompleted)
                <svg class="lesson-check" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            @else
                <svg class="lesson-check" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>
            @endif
            <div class="flex-1">
              <div class="playlist-lesson-title">{{ $bh->ten_bai_hoc }}</div>
              <div class="playlist-lesson-meta">
                @if($bh->loai_dieu_kien === 'kiem_tra' || $bh->loai_dieu_kien === 'phat_am_ai' || $bh->cau_hois_count > 0)
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> {{ $bh->cau_hois_count ?? 0 }} câu hỏi
                @else
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"/></svg> {{ $bh->thoi_luong_giay ? floor($bh->thoi_luong_giay / 60) . ':' . str_pad($bh->thoi_luong_giay % 60, 2, '0', STR_PAD_LEFT) : '00:00' }}
                @endif
              </div>
            </div>
          </a>
          @endforeach
        </div>
      </div>
      @endforeach

    </div>
  </aside>

</div>

<!-- ================= FLASHCARD OVERLAY (Learning Mode) ================= -->
<div class="flashcard-overlay" id="flashcardOverlay" style="z-index: 2050;">
  <div class="fc-header">
    <button class="btn btn-sm btn-light" id="closeFlashcardBtn" style="border-radius: 999px; border: 1px solid var(--border);">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg> Quay lại bài học
    </button>
    <div class="fc-progress-wrap mx-auto" style="flex:1; max-width: 400px; display:flex; align-items:center; gap:1rem;">
      <div style="flex:1; height:6px; background:var(--border); border-radius:3px; overflow:hidden;">
        <div id="sessionProgress" style="height:100%; background:var(--primary); width: 0%; transition: width 0.3s ease;"></div>
      </div>
      <span class="small fw-bold" id="sessionCountText" style="color: var(--text-muted);">1/10</span>
    </div>
    <button class="btn btn-sm btn-light" style="visibility: hidden;">Quay lại</button>
  </div>

  <div class="fc-body">
    <!-- Session complete panel -->
    <div class="complete-panel d-none" id="completePanel">
      <div class="complete-seal zh">好</div>
      <h2 class="font-head fw-bold mb-2">Hoàn thành phiên học!</h2>
      <p style="color: var(--text-muted)" id="completeSummary">Bạn đã ôn xong từ vựng của bài học này.</p>
      <button class="btn btn-primary mt-3" style="border-radius: 999px;" id="restartSessionBtn">Đóng</button>
    </div>

    <div class="flashcard-zone mx-auto w-100" style="max-width: 500px;" id="flashcardZone">
      <div class="card-scene" id="cardScene">
        <div class="flashcard" id="flashcard">
          <div class="card-face card-front">
            <span class="card-level-tag" id="fcLevel">HSK 1</span>
            <button class="card-bookmark" id="fcBookmark"><svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2z" /></svg></button>
            <div class="card-tzg">
              <span class="card-hanzi" id="fcHanzi">你</span>
            </div>
            <button class="card-audio-btn" id="fcAudioFront" onclick="event.stopPropagation()">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 5L6 9H2v6h4l5 4V5z"/><path d="M15.5 8.5a5 5 0 010 7"/></svg>
            </button>
            <div class="card-hint mt-2">Chạm để lật thẻ</div>
          </div>
          <div class="card-face card-back">
            <div class="pinyin" id="fcPinyin">nǐ</div>
            <div class="meaning" id="fcMeaning">bạn / anh / chị</div>
            <button class="card-audio-btn" id="fcAudioBack" onclick="event.stopPropagation()">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 5L6 9H2v6h4l5 4V5z"/><path d="M15.5 8.5a5 5 0 010 7"/></svg>
            </button>
            <div class="example-box">
              <div class="example-zh zh" id="fcExZh">你好吗？</div>
              <div class="example-vi" id="fcExVi">Bạn khỏe không?</div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Spaced Repetition Buttons (Hidden until flipped) -->
      <div class="fc-controls" id="fcControls" style="opacity: 0; pointer-events: none; transition: all 0.3s;">
        <button class="fc-btn again" onclick="nextCard('again')">
          <span class="fw-bold" style="font-size: 0.9rem;">Lại</span>
          <span style="font-size: 0.7rem; opacity: 0.8;">&lt; 1p</span>
        </button>
        <button class="fc-btn hard" onclick="nextCard('hard')">
          <span class="fw-bold" style="font-size: 0.9rem;">Khó</span>
          <span style="font-size: 0.7rem; opacity: 0.8;">6p</span>
        </button>
        <button class="fc-btn good" onclick="nextCard('good')">
          <span class="fw-bold" style="font-size: 0.9rem;">Tốt</span>
          <span style="font-size: 0.7rem; opacity: 0.8;">10p</span>
        </button>
        <button class="fc-btn easy" onclick="nextCard('easy')">
          <span class="fw-bold" style="font-size: 0.9rem;">Dễ</span>
          <span style="font-size: 0.7rem; opacity: 0.8;">4n</span>
        </button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const video = document.getElementById('player');
    
    if (video) {
        const sourceElement = video.querySelector('source[type="application/x-mpegURL"]');
        
        // Hỗ trợ HLS (m3u8) bằng Hls.js
        if (sourceElement && Hls.isSupported()) {
            const hlsUrl = sourceElement.src;
            
            const hls = new Hls();
            window.hls = hls;

            hls.loadSource(hlsUrl);
            hls.attachMedia(video);

            hls.on(Hls.Events.ERROR, function (event, data) {
                if (data.fatal) {
                    switch (data.type) {
                        case Hls.ErrorTypes.NETWORK_ERROR:
                            hls.startLoad();
                            break;
                        case Hls.ErrorTypes.MEDIA_ERROR:
                            hls.recoverMediaError();
                            break;
                        default:
                            hls.destroy();
                            break;
                    }
                }
            });
        }


    }

  /* Theme Toggle */
  const root = document.documentElement;
  function lsGet(k){ try{ return localStorage.getItem(k); }catch(e){ return null; } }
  function lsSet(k,v){ try{ localStorage.setItem(k,v); }catch(e){} }
  root.setAttribute('data-theme', lsGet('hb-theme') || 'light');
  document.getElementById('themeToggle').addEventListener('click', function () {
    const next = root.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
    root.setAttribute('data-theme', next);
    lsSet('hb-theme', next);
  });

  /* Tabs Navigation */
  const tabs = document.querySelectorAll('.learning-tab');
  const panes = document.querySelectorAll('.tab-pane');
  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      tabs.forEach(t => t.classList.remove('active'));
      panes.forEach(p => p.classList.remove('active'));
      tab.classList.add('active');
      document.querySelector(tab.dataset.target).classList.add('active');
    });
  });

  /* Playlist Accordion */
  const chapters = document.querySelectorAll('.chapter-header');
  chapters.forEach(ch => {
    ch.addEventListener('click', () => {
      ch.classList.toggle('open');
      const list = ch.nextElementSibling;
      list.classList.toggle('show');
    });
  });

  /* Progress Update Logic */
  const markCompletedSwitch = document.getElementById('markCompletedSwitch');
  const currentLessonItem = document.getElementById('current-lesson-item');
  const lessonCheckIcon = currentLessonItem ? currentLessonItem.querySelector('.lesson-check') : null;
  
  let currentSoBaiDaHoc = {{ $soBaiDaHoc }};
  const tongSoBai = {{ $tongSoBai }};
  const isOriginallyCompleted = {{ $tienDo->da_hoan_thanh ? 'true' : 'false' }};

  const progressTopFill = document.getElementById('progressTopFill');
  const progressTopText = document.getElementById('progressTopText');
  const progressSidebarFill = document.getElementById('progressSidebarFill');
  const progressSidebarCountText = document.getElementById('progressSidebarCountText');
  const progressSidebarPercentText = document.getElementById('progressSidebarPercentText');

  function updateProgressUI(isCompleted) {
    if (isOriginallyCompleted && !isCompleted) {
        currentSoBaiDaHoc = Math.max(0, {{ $soBaiDaHoc }} - 1);
    } else if (!isOriginallyCompleted && isCompleted) {
        currentSoBaiDaHoc = Math.min(tongSoBai, {{ $soBaiDaHoc }} + 1);
    } else {
        currentSoBaiDaHoc = {{ $soBaiDaHoc }};
    }

    const currentPercent = tongSoBai > 0 ? Math.round((currentSoBaiDaHoc / tongSoBai) * 100) : 0;

    if (progressTopFill) progressTopFill.style.width = currentPercent + '%';
    if (progressSidebarFill) progressSidebarFill.style.width = currentPercent + '%';
    if (progressTopText) progressTopText.innerText = currentPercent + '%';
    if (progressSidebarPercentText) progressSidebarPercentText.innerText = currentPercent + '%';
    if (progressSidebarCountText) progressSidebarCountText.innerText = `Đã học ${currentSoBaiDaHoc}/${tongSoBai} bài`;
  }

  markCompletedSwitch.addEventListener('change', function() {
    const isCompleted = this.checked;
    const url = this.dataset.url;

    fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({ da_hoan_thanh: isCompleted ? 1 : 0 })
    })
    .then(res => res.json())
    .then(data => {
      if(data.success) {
        if (isCompleted) {
          if (currentLessonItem) currentLessonItem.classList.add('completed');
          if (lessonCheckIcon) lessonCheckIcon.innerHTML = '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>';
          
          // Disable the toggle switch and update text to indicate completion
          const toggleInput = document.getElementById('markCompletedSwitch');
          if (toggleInput) {
              toggleInput.disabled = true;
              toggleInput.style.cursor = 'not-allowed';
              toggleInput.title = 'Bạn đã hoàn thành bài học này';
              
              const label = document.querySelector('label[for="markCompletedSwitch"]');
              if (label) {
                  label.style.cursor = 'not-allowed';
                  label.classList.remove('text-muted');
                  label.classList.add('text-success');
                  label.innerText = 'Đã hoàn thành';
                  label.title = 'Bạn đã hoàn thành bài học này';
              }
          }
        } else {
          if (currentLessonItem) currentLessonItem.classList.remove('completed');
          if (lessonCheckIcon) lessonCheckIcon.innerHTML = '<circle cx="12" cy="12" r="10"/>';
        }
        updateProgressUI(isCompleted);
      }
    })
    .catch(err => console.error('Lỗi lưu tiến độ:', err));
  });

  /* Auto-complete logic based on video duration */
  const loaiDieuKien = '{{ $baiHoc->loai_dieu_kien }}';
  const phanTramVideoYeuCau = {{ $baiHoc->phan_tram_video ?? 0 }};
  let daGuiHoanThanh = isOriginallyCompleted;

  const videoProgressContainer = document.getElementById('videoProgressContainer');
  const videoProgressCircle = videoProgressContainer ? videoProgressContainer.querySelector('.circular-progress') : null;
  const videoProgressText = document.getElementById('videoProgressText');
  const markCompletedContainer = document.getElementById('markCompletedContainer');

  if (video && video.tagName && video.tagName.toLowerCase() === 'video') {
      const videoId = '{{ $baiHoc->id }}';
      const storageKey = 'video_progress_' + videoId;
      const storageMaxKey = 'video_max_progress_' + videoId;
      
      let maxTimeWatched = parseFloat(localStorage.getItem(storageMaxKey)) || 0;

      // Khôi phục thời gian đã xem
      const restoreTime = () => {
          const savedTime = parseFloat(localStorage.getItem(storageKey));
          if (savedTime && savedTime > 0 && savedTime < video.duration) {
              video.currentTime = savedTime;
          }
      };

      if (video.readyState >= 1) {
          restoreTime();
      } else {
          video.addEventListener('loadedmetadata', restoreTime);
      }

      video.addEventListener('timeupdate', () => {
          if (!video.duration) return;
          
          // Lưu thời gian hiện tại để lần sau xem tiếp
          localStorage.setItem(storageKey, video.currentTime);
          
          // Lưu thời gian xem xa nhất (tránh việc tua lùi làm giảm % tiến độ)
          if (video.currentTime > maxTimeWatched) {
              maxTimeWatched = video.currentTime;
              localStorage.setItem(storageMaxKey, maxTimeWatched);
          }

          if (loaiDieuKien === 'xem_video' && !daGuiHoanThanh) {
              // Tính % dựa trên thời gian xem xa nhất
              const phanTramDaXem = Math.min(100, Math.max(0, (maxTimeWatched / video.duration) * 100));
              
              // Cập nhật hiệu ứng vòng tròn và text %
              if (videoProgressCircle) {
                  const currentDegree = (phanTramDaXem / 100) * 360;
                  videoProgressCircle.style.setProperty('--progress', currentDegree + 'deg');
              }
              if (videoProgressText) {
                  videoProgressText.innerText = Math.round(phanTramDaXem) + '%';
              }
              
              // Kiểm tra xem đạt ngưỡng yêu cầu chưa
              if (phanTramDaXem >= phanTramVideoYeuCau) {
                  daGuiHoanThanh = true;
                  
                  // Ẩn vòng tròn, hiện lại toggle switch
                  if (videoProgressContainer) {
                      videoProgressContainer.style.setProperty('display', 'none', 'important');
                  }
                  if (markCompletedContainer) {
                      markCompletedContainer.style.setProperty('display', 'flex', 'important');
                  }
                  
                  // Tích hoàn thành và gửi request
                  markCompletedSwitch.checked = true;
                  markCompletedSwitch.dispatchEvent(new Event('change'));
              }
          }
      });
  }

  /* ---------- Flashcard & Vocabulary Logic ---------- */
  @php
      $mappedVocab = $baiHoc->tuVungs->map(function($tv) {
          return [
              'id' => $tv->id,
              'hanzi' => $tv->tu_han,
              'pinyin' => $tv->phien_am,
              'meaning' => $tv->nghia_tieng_viet,
              'exZh' => $tv->vi_du,
              'exVi' => '',
              'level' => 'HSK', 
              'learned' => false, 
              'bookmarked' => false
          ];
      })->values()->all();
  @endphp
  const VOCAB = @json($mappedVocab);

  window.speak = function(text) {
    if (!("speechSynthesis" in window)) return;
    const utter = new SpeechSynthesisUtterance(text);
    utter.lang = "zh-CN"; utter.rate = 0.85;
    window.speechSynthesis.cancel();
    window.speechSynthesis.speak(utter);
  }

  // Render Lesson Vocabulary List
  const listContainer = document.getElementById("lessonVocabList");
  if(listContainer) {
    listContainer.innerHTML = "";
    VOCAB.forEach(function (item) {
      const row = document.createElement("div");
      row.className = "vocab-list-item";
      row.innerHTML = `
        <div class="vocab-thumb zh">${item.hanzi.slice(0, 1)}</div>
        <div class="vocab-info">
          <div class="vocab-hanzi-row">
            <span class="zh fw-bold fs-6">${item.hanzi}</span>
            <span class="vocab-pinyin">${item.pinyin}</span>
          </div>
          <div class="vocab-meaning">${item.meaning}</div>
        </div>
        <button class="list-audio-btn" aria-label="Nghe phát âm" onclick="speak('${item.hanzi}')">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 5L6 9H2v6h4l5 4V5z"/><path d="M15.5 8.5a5 5 0 010 7"/></svg>
        </button>
      `;
      listContainer.appendChild(row);
    });
  }

  /* Overlay State */
  let queue = [];
  let currentIndex = 0;
  const flashcard = document.getElementById("flashcard");
  const overlay = document.getElementById("flashcardOverlay");
  const controls = document.getElementById("fcControls");

  window.openFlashcardSession = function() {
    queue = VOCAB.slice();
    currentIndex = 0;
    
    const completePanel = document.getElementById("completePanel");
    const flashcardZone = document.getElementById("flashcardZone");
    if(completePanel) completePanel.classList.add("d-none");
    if(flashcardZone) flashcardZone.classList.remove("d-none");
    if(overlay) overlay.classList.add("active");
    renderCard();
  }

  const closeBtn = document.getElementById("closeFlashcardBtn");
  if(closeBtn) {
    closeBtn.addEventListener("click", function() {
      overlay.classList.remove("active");
    });
  }
  
  const restartBtn = document.getElementById("restartSessionBtn");
  if(restartBtn) {
    restartBtn.addEventListener("click", function() {
      overlay.classList.remove("active");
    });
  }

  function renderCard() {
    if(!queue[currentIndex]) return;
    const item = queue[currentIndex];
    if(flashcard) flashcard.classList.remove("flipped");
    if(controls) {
        controls.style.opacity = 0;
        controls.style.pointerEvents = "none";
    }
    
    document.getElementById("fcLevel").textContent = item.level;
    document.getElementById("fcHanzi").textContent = item.hanzi;
    document.getElementById("fcPinyin").textContent = item.pinyin;
    document.getElementById("fcMeaning").textContent = item.meaning;
    document.getElementById("fcExZh").textContent = item.exZh;
    document.getElementById("fcExVi").textContent = item.exVi;
    
    const pct = ((currentIndex) / queue.length) * 100;
    const sessionProgress = document.getElementById("sessionProgress");
    const sessionCountText = document.getElementById("sessionCountText");
    if(sessionProgress) sessionProgress.style.width = pct + "%";
    if(sessionCountText) sessionCountText.textContent = (currentIndex + 1) + "/" + queue.length;
  }

  if(flashcard) {
    flashcard.addEventListener("click", function () {
      if(!flashcard.classList.contains("flipped")){
          flashcard.classList.add("flipped");
          if(controls) {
              controls.style.opacity = 1;
              controls.style.pointerEvents = "auto";
          }
      }
    });
  }

  const fcAudioFront = document.getElementById("fcAudioFront");
  const fcAudioBack = document.getElementById("fcAudioBack");
  if(fcAudioFront) fcAudioFront.addEventListener("click", () => speak(queue[currentIndex].hanzi));
  if(fcAudioBack) fcAudioBack.addEventListener("click", () => speak(queue[currentIndex].hanzi));

  window.nextCard = function(result) {
    if(flashcard) flashcard.classList.add(result === 'again' || result === 'hard' ? "swipe-out-left" : "swipe-out-right");
    setTimeout(function () {
      if(flashcard) flashcard.classList.remove("swipe-out-right", "swipe-out-left");
      currentIndex++;
      if (currentIndex >= queue.length) {
        const sessionProgress = document.getElementById("sessionProgress");
        if(sessionProgress) sessionProgress.style.width = "100%";
        const flashcardZone = document.getElementById("flashcardZone");
        if(flashcardZone) flashcardZone.classList.add("d-none");
        const completePanel = document.getElementById("completePanel");
        if(completePanel) completePanel.classList.remove("d-none");
      } else {
        renderCard();
      }
    }, 320);
  }
});

</script>
@endpush