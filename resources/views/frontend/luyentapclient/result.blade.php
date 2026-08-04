@extends('frontend.layouts.dashboard')

@section('title', 'Kết quả thi HSK — Hányǔ Bàn')

@push('styles')
<link href="{{ asset('frontend/asset/css/hsk-exam.css') }}" rel="stylesheet" />
<style>
  /* ============================================================
     THIẾT KẾ LẠI: dùng chung biến CSS (--primary, --card, --border...)
     của dashboard.css thay vì màu hex cứng, để trang này tự động
     tương thích Dark Mode và đồng bộ với toàn bộ hệ thống.
     ============================================================ */

  /* ---------- Hero kết quả ---------- */
  .result-hero {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 26px;
    padding: 2.5rem 1.5rem;
    text-align: center;
    position: relative;
    overflow: hidden;
    margin-bottom: 1.5rem;
  }
  .result-hero::before {
    content: "级";
    position: absolute;
    font-family: var(--font-zh);
    font-weight: 700;
    font-size: 14rem;
    opacity: 0.04;
    right: -1.5rem;
    bottom: -3.5rem;
    line-height: 1;
    color: var(--primary);
  }
  .result-badge-tag {
    position: relative;
    z-index: 1;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-weight: 700;
    font-size: 0.82rem;
    padding: 0.4rem 1rem;
    border-radius: 999px;
    margin-bottom: 1.25rem;
  }
  .result-badge-tag.pass {
    background: color-mix(in srgb, var(--success) 15%, transparent);
    color: #15803d;
  }
  .result-badge-tag.fail {
    background: color-mix(in srgb, var(--danger) 12%, transparent);
    color: var(--danger);
  }
  [data-theme="dark"] .result-badge-tag.pass { color: #4ade80; }

  .score-ring-wrap {
    position: relative;
    width: 180px;
    height: 180px;
    margin: 0 auto 1.25rem;
    z-index: 1;
  }
  .score-ring-label {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
  }
  .score-ring-label .score-num {
    font-family: var(--font-head);
    font-weight: 800;
    font-size: 2.3rem;
    line-height: 1;
    color: var(--text);
  }
  .score-ring-label .score-sub {
    font-size: 0.8rem;
    color: var(--text-muted);
    margin-top: 0.2rem;
  }

  .result-title {
    position: relative;
    z-index: 1;
    font-family: var(--font-head);
    font-size: 1.5rem;
    font-weight: 800;
    margin: 0 0 0.4rem;
    color: var(--text);
  }
  .result-subtitle {
    position: relative;
    z-index: 1;
    color: var(--text-muted);
    font-size: 0.95rem;
    margin-bottom: 1.75rem;
  }

  /* ---------- Stat row ---------- */
  .result-stats-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    max-width: 900px;
    margin: 0 auto;
    position: relative;
    z-index: 1;
  }
  .result-stat-box {
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 1.1rem 1.2rem;
    text-align: left;
    transition: all 0.2s ease;
  }
  .result-stat-box:hover {
    border-color: var(--primary);
  }
  .stat-box-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-muted);
    font-size: 0.85rem;
    margin-bottom: 0.6rem;
  }
  .stat-box-value {
    font-family: var(--font-head);
    font-size: 1.6rem;
    font-weight: 800;
    color: var(--text);
    margin-bottom: 0.2rem;
  }
  .stat-box-desc { color: var(--text-muted); font-size: 0.8rem; }

  /* ---------- Section card chung ---------- */
  .result-section-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 22px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
  }
  .result-section-card h2 {
    font-family: var(--font-head);
    font-weight: 700;
    font-size: 1rem;
    margin-bottom: 1.1rem;
    color: var(--text);
  }

  /* ---------- Breakdown theo phần thi (Nghe/Đọc/Viết) ---------- */
  .breakdown-row {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.85rem 0;
    border-bottom: 1px solid var(--border);
  }
  .breakdown-row:last-child { border-bottom: none; }
  .breakdown-row .name {
    width: 90px;
    font-weight: 700;
    font-size: 0.88rem;
    color: var(--text);
    flex-shrink: 0;
  }
  .breakdown-track {
    flex: 1;
    height: 9px;
    border-radius: 999px;
    background: var(--border);
    overflow: hidden;
  }
  .breakdown-fill {
    height: 100%;
    border-radius: 999px;
    width: 0%;
    transition: width 1s ease;
  }
  .breakdown-score {
    width: 64px;
    text-align: right;
    font-weight: 700;
    font-size: 0.85rem;
    color: var(--text);
    flex-shrink: 0;
  }

  /* ---------- Tabs lọc câu hỏi ---------- */
  .review-tabs {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1.25rem;
    flex-wrap: wrap;
  }
  .review-tab {
    border: 1.5px solid var(--border);
    background: var(--card);
    color: var(--text-muted);
    font-weight: 600;
    font-size: 0.82rem;
    border-radius: 999px;
    padding: 0.45rem 1rem;
    cursor: pointer;
    transition: all 0.15s ease;
  }
  .review-tab.active {
    border-color: var(--primary);
    background: var(--primary);
    color: #fff;
  }

  /* ---------- Question container (đổi màu theo biến, giữ layout gốc từ hsk-exam.css) ---------- */
  .question-container {
    background: var(--card);
    border-radius: 16px;
    padding: 1.5rem;
  }
  .q-type-badge { color: var(--text-muted); font-size: 0.85rem; font-weight: 600; display: flex; align-items: center; }
  .q-number { font-weight: 700; color: var(--text); font-size: 0.85rem; }
  .q-text { color: var(--text); }

  /* Trắc nghiệm: đúng / sai — dùng biến thay vì hex cứng */
  .opt-item.is-correct {
    background: color-mix(in srgb, var(--success) 14%, var(--card));
    border-color: var(--success);
    color: var(--text);
  }
  .opt-item.is-correct .opt-circle,
  .opt-item.is-correct .opt-label {
    background: var(--success) !important;
    border-color: var(--success) !important;
    color: #fff !important;
  }
  .opt-item.is-wrong {
    background: color-mix(in srgb, var(--danger) 10%, var(--card));
    border-color: var(--danger);
    color: var(--text);
  }
  .opt-item.is-wrong .opt-circle,
  .opt-item.is-wrong .opt-label {
    background: var(--danger) !important;
    border-color: var(--danger) !important;
    color: #fff !important;
  }

  .writing-result-box {
    padding: 1rem;
    border-radius: 12px;
    background: var(--bg);
    border: 1px solid var(--border);
  }
  .writing-result-box.correct-box {
    background: color-mix(in srgb, var(--success) 8%, var(--card));
    border-color: color-mix(in srgb, var(--success) 35%, transparent);
  }

  /* ---------- CTA cuối trang ---------- */
  .result-cta-row {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    justify-content: center;
    margin-top: 0.5rem;
  }
</style>
@endpush

@section('content')
<div class="container pb-5">

  @php
      $totalQs = count($deThi->cauHois);
      $percent = $totalQs > 0 ? round(($phien->so_cau_dung / $totalQs) * 100) : 0;
      $isPass = $percent >= 60; // ngưỡng đạt — chỉnh lại nếu quy tắc tính đỗ HSK của bạn khác

      $title = 'Cần cố gắng thêm! 💪';
      if ($percent >= 80) $title = 'Xuất sắc! 🏆';
      elseif ($percent >= 60) $title = 'Khá tốt! 👏';

      $batDau = \Carbon\Carbon::parse($phien->thoi_gian_bat_dau);
      $ketThuc = \Carbon\Carbon::parse($phien->thoi_gian_ket_thuc);
      $diffInSeconds = $batDau->diffInSeconds($ketThuc);
      $m = floor($diffInSeconds / 60);
      $s = $diffInSeconds % 60;
      $timeDisplay = ($m > 0 ? $m . ' phút ' : '') . $s . ' giây';
  @endphp

  <!-- ============ HERO: điểm số tổng quan ============ -->
  <div class="result-hero">
    <span class="result-badge-tag {{ $isPass ? 'pass' : 'fail' }}">
      @if($isPass)
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/></svg>
        Đạt yêu cầu
      @else
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
        Chưa đạt yêu cầu
      @endif
    </span>

    <div class="score-ring-wrap">
      <svg width="180" height="180" viewBox="0 0 180 180">
        <circle cx="90" cy="90" r="78" fill="none" stroke="var(--border)" stroke-width="13"/>
        <circle id="scoreRing" cx="90" cy="90" r="78" fill="none" stroke="var(--primary)" stroke-width="13"
          stroke-linecap="round" stroke-dasharray="490" stroke-dashoffset="490"
          transform="rotate(-90 90 90)" data-percent="{{ $percent }}"/>
      </svg>
      <div class="score-ring-label">
        <span class="score-num" id="scoreNum">0</span>
        <span class="score-sub">/ 100 điểm</span>
      </div>
    </div>

    <h2 class="result-title">{{ $title }}</h2>
    <div class="result-subtitle">{{ $deThi->tieu_de }}</div>

    <div class="result-stats-row">
      <div class="result-stat-box">
        <div class="stat-box-title">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
          Điểm số
        </div>
        <div class="stat-box-value">{{ round($phien->tong_diem) }}/100</div>
        <div class="stat-box-desc">{{ $percent }}%</div>
      </div>

      <div class="result-stat-box">
        <div class="stat-box-title">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--success)" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
          Câu đúng
        </div>
        <div class="stat-box-value">{{ $phien->so_cau_dung }}/{{ $totalQs }}</div>
        <div class="stat-box-desc">{{ $percent }}% chính xác</div>
      </div>

      <div class="result-stat-box">
        <div class="stat-box-title">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--secondary)" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
          Câu chưa làm
        </div>
        <div class="stat-box-value">{{ $soCauChuaLam }}</div>
        <div class="stat-box-desc">trên tổng {{ $totalQs }} câu</div>
      </div>

      <div class="result-stat-box">
        <div class="stat-box-title">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0EA5E9" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          Thời gian
        </div>
        <div class="stat-box-value" style="font-size: 1.3rem;">{{ $timeDisplay }}</div>
        <div class="stat-box-desc">Hoàn thành</div>
      </div>
    </div>
  </div>

  <div class="row g-3">
    <div class="col-12 col-lg-10 mx-auto">

      <!-- ============ BREAKDOWN THEO KỸ NĂNG ============ -->
      <div class="result-section-card">
        <h2>Kết quả theo từng phần thi</h2>
        @foreach($partStats as $key => $stat)
          @php $pct = $stat['total'] > 0 ? round(($stat['correct'] / $stat['total']) * 100) : 0; @endphp
          <div class="breakdown-row">
            <span class="name d-flex align-items-center gap-2">
              @if($key === 'listening')
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 18v-6a9 9 0 0 1 18 0v6"></path><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"></path></svg>
              @elseif($key === 'reading')
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--secondary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
              @elseif($key === 'writing')
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0EA5E9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
              @endif
              {{ $stat['label'] }}
            </span>
            <div class="breakdown-track">
              <div class="breakdown-fill" data-pct="{{ $pct }}"
                style="background: {{ $key === 'listening' ? 'var(--primary)' : ($key === 'reading' ? 'var(--secondary)' : '#0EA5E9') }};"></div>
            </div>
            <span class="breakdown-score">{{ $stat['correct'] }}/{{ $stat['total'] }}</span>
          </div>
        @endforeach
      </div>

      <!-- ============ REVIEW: chi tiết từng câu ============ -->
      <div class="result-section-card">
        <h2>Chi tiết bài làm</h2>
        <div class="review-tabs" id="reviewTabs">
          <a href="{{ request()->fullUrlWithQuery(['filter' => 'all', 'page' => 1]) }}" class="review-tab text-decoration-none {{ request('filter', 'all') == 'all' ? 'active' : '' }}">Tất cả ({{ $totalQs }})</a>
          <a href="{{ request()->fullUrlWithQuery(['filter' => 'wrong', 'page' => 1]) }}" class="review-tab text-decoration-none {{ request('filter') == 'wrong' ? 'active' : '' }}">Câu sai</a>
          <a href="{{ request()->fullUrlWithQuery(['filter' => 'listening', 'page' => 1]) }}" class="review-tab text-decoration-none d-flex align-items-center gap-1 {{ request('filter') == 'listening' ? 'active' : '' }}">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 18v-6a9 9 0 0 1 18 0v6"></path><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"></path></svg> Nghe
          </a>
          <a href="{{ request()->fullUrlWithQuery(['filter' => 'reading', 'page' => 1]) }}" class="review-tab text-decoration-none d-flex align-items-center gap-1 {{ request('filter') == 'reading' ? 'active' : '' }}">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg> Đọc
          </a>
          <a href="{{ request()->fullUrlWithQuery(['filter' => 'writing', 'page' => 1]) }}" class="review-tab text-decoration-none d-flex align-items-center gap-1 {{ request('filter') == 'writing' ? 'active' : '' }}">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg> Viết
          </a>
        </div>

        <div class="exam-layout mt-0" style="display: block;" id="reviewList">
          @forelse($paginatedCauHois as $index => $q)
          @php
              $userAns = $userAnswers[$q->id] ?? null;
              $correctDapAn = $q->dapAns->first();

              $borderColor = 'var(--border)';
              $badgeBg = 'var(--bg)';
              $badgeText = 'Chưa làm';
              $statusFlag = 'unanswered';

              if ($userAns) {
                  if ($userAns->dung) {
                      $borderColor = 'var(--success)';
                      $badgeBg = 'color-mix(in srgb, var(--success) 16%, transparent)';
                      $badgeText = 'Đúng';
                      $statusFlag = 'correct';
                  } else {
                      $borderColor = 'var(--danger)';
                      $badgeBg = 'color-mix(in srgb, var(--danger) 12%, transparent)';
                      $badgeText = 'Sai';
                      $statusFlag = 'wrong';
                  }
              }
          @endphp

          <div class="question-container mb-3" data-part="{{ $q->getPart() }}" data-status="{{ $statusFlag }}">
            <div class="q-header">
              <div class="q-type-badge">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14"/></svg>
                @if($q->getPart() == 'listening') Phần Nghe Hiểu
                @elseif($q->getPart() == 'reading') Phần Đọc Hiểu
                @else Phần Viết @endif
              </div>
              <div class="d-flex align-items-center gap-2">
                  <span class="badge" style="background: {{ $badgeBg }}; color: {{ $borderColor }}; font-size: 0.85rem;">{{ $badgeText }}</span>
                  <div class="q-number">Câu {{ $q->global_index ?? ($index + 1) }}</div>
              </div>
            </div>

            <div class="q-body">
              @if(!str_contains($q->noi_dung, '/'))
              <div class="q-text">{{ $q->noi_dung ?? 'Chưa có nội dung câu hỏi' }}</div>
              @else
              <div class="q-text" style="color: var(--text-muted); font-size: 0.95rem;">Sắp xếp các từ sau thành câu hoàn chỉnh:</div>
              <div class="q-text mt-2">{{ str_replace('/', ' / ', $q->noi_dung) }}</div>
              @endif

              @if($q->am_thanh)
              <div class="audio-box mt-3 mb-2" id="audioBox-{{ $q->id }}">
                <button class="audio-btn" type="button" onclick="toggleAudio('{{ $q->id }}')" id="playBtn-{{ $q->id }}">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                </button>
                <div class="audio-wave">
                  <div class="wave-bar"></div><div class="wave-bar"></div>
                  <div class="wave-bar"></div><div class="wave-bar"></div>
                  <div class="wave-bar"></div><div class="wave-bar"></div>
                  <div class="wave-bar"></div><div class="wave-bar"></div>
                </div>
                <div class="fw-bold" style="color:var(--text-muted); font-size:0.9rem;" id="timeDisplay-{{ $q->id }}">00:00</div>

                <audio id="audioEl-{{ $q->id }}" src="{{ asset('storage/' . $q->am_thanh) }}" ontimeupdate="updateAudioTime('{{ $q->id }}')" onended="resetAudioState('{{ $q->id }}')"></audio>
              </div>
              @endif

              @if($q->hinh_anh)
              <div class="mt-3 mb-2">
                <img src="{{ asset('storage/' . $q->hinh_anh) }}" alt="Hình ảnh câu hỏi" style="max-height: 200px; border-radius: 8px;">
              </div>
              @endif
            </div>

            @if($q->dapAns && $q->dapAns->count() > 1)
            <!-- TRẮC NGHIỆM -->
            <div class="opt-list grid-2" style="pointer-events: none;">
              @foreach($q->dapAns as $key => $dapAn)
                  @php
                      $isCorrectAns = ($correctDapAn && $correctDapAn->id == $dapAn->id);
                      $isSelectedAns = ($userAns && $userAns->id_dap_an == $dapAn->id);

                      $itemClass = '';
                      if ($isCorrectAns) {
                          $itemClass = 'is-correct';
                      } elseif ($isSelectedAns && !$isCorrectAns) {
                          $itemClass = 'is-wrong';
                      }
                  @endphp

                  <div class="opt-item {{ $itemClass }}">
                    <div class="opt-label">{{ chr(65 + $key) }}</div>
                    <div class="opt-text">{{ $dapAn->noi_dung }}</div>

                    @if($isCorrectAns)
                        <svg width="20" height="20" class="ms-auto" viewBox="0 0 24 24" fill="none" stroke="var(--success)" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                    @elseif($isSelectedAns && !$isCorrectAns)
                        <svg width="20" height="20" class="ms-auto" viewBox="0 0 24 24" fill="none" stroke="var(--danger)" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    @endif
                  </div>
              @endforeach
            </div>
            @else
            <!-- TỰ LUẬN / SẮP XẾP -->
            <div class="mt-4">
              <div class="writing-result-box mb-3">
                  <div class="text-uppercase mb-1 fw-bold small" style="color: var(--text-muted);">Câu trả lời của bạn:</div>
                  <div class="fs-5" style="color: {{ $userAns && $userAns->dung ? 'var(--success)' : 'var(--danger)' }}; text-decoration: {{ $userAns && !$userAns->dung ? 'line-through' : 'none' }};">
                      {{ $userAns->dap_an_tu_luan ?? '(Không có câu trả lời)' }}
                  </div>
              </div>

              <div class="writing-result-box correct-box">
                  <div class="small text-uppercase mb-1 fw-bold" style="color: var(--success);">Đáp án chuẩn:</div>
                  <div class="fs-5 fw-bold" style="color: var(--text);">
                      {{ str_replace('/', '', $correctDapAn->noi_dung ?? '') }}
                  </div>
                  @if($correctDapAn && $correctDapAn->pinyin)
                  <div style="color: var(--text-muted);" class="mt-1">{{ $correctDapAn->pinyin }}</div>
                  @endif
              </div>
            </div>
            @endif

          </div>
          @empty
          <p class="small text-center py-3" id="reviewEmptyState" style="color: var(--text-muted);">Không có câu nào trong mục này.</p>
          @endforelse
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $paginatedCauHois->links('pagination::bootstrap-5') }}
        </div>
      </div>
    </div>

  </div>

</div>
@endsection

@push('scripts')
<script>
  /* ---------- Score ring + breakdown bar animation ---------- */
  document.addEventListener('DOMContentLoaded', function () {
    const ring = document.getElementById('scoreRing');
    if (ring) {
      const percent = parseFloat(ring.dataset.percent || '0');
      const circumference = 2 * Math.PI * 78;
      ring.setAttribute('stroke-dasharray', circumference);
      let displayed = 0;
      const scoreNumEl = document.getElementById('scoreNum');
      const interval = setInterval(function () {
        displayed += 2;
        if (displayed >= percent) { displayed = percent; clearInterval(interval); }
        scoreNumEl.textContent = Math.round(displayed);
        ring.setAttribute('stroke-dashoffset', circumference * (1 - displayed / 100));
      }, 18);
    }

      setTimeout(function () {
        document.querySelectorAll('.breakdown-fill').forEach(function (bar) {
          bar.style.width = bar.dataset.pct + '%';
        });
      }, 100);
    });

  /* ---------- Audio player (giữ nguyên logic gốc) ---------- */
  window.currentAudioPlaying = null;

  window.toggleAudio = function(id) {
    const audioEl = document.getElementById('audioEl-' + id);
    const audioBox = document.getElementById('audioBox-' + id);
    const playBtn = document.getElementById('playBtn-' + id);

    if(!audioEl) return;

    if (audioEl.paused) {
      if(window.currentAudioPlaying && window.currentAudioPlaying !== audioEl) {
        window.currentAudioPlaying.pause();
        const oldId = window.currentAudioPlaying.id.replace('audioEl-', '');
        resetAudioState(oldId);
      }

      audioEl.play();
      window.currentAudioPlaying = audioEl;
      audioBox.classList.add('playing');
      playBtn.innerHTML = '<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>';
    } else {
      audioEl.pause();
      audioBox.classList.remove('playing');
      playBtn.innerHTML = '<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>';
    }
  };

  window.updateAudioTime = function(id) {
    const audioEl = document.getElementById('audioEl-' + id);
    const timeDisplay = document.getElementById('timeDisplay-' + id);
    if(audioEl && timeDisplay) {
      const cur = audioEl.currentTime;
      const dur = audioEl.duration || 0;

      const formatTime = (time) => {
        const m = Math.floor(time / 60).toString().padStart(2, '0');
        const s = Math.floor(time % 60).toString().padStart(2, '0');
        return `${m}:${s}`;
      };

      timeDisplay.textContent = `${formatTime(cur)} / ${formatTime(dur)}`;
    }
  };

  window.resetAudioState = function(id) {
    const audioBox = document.getElementById('audioBox-' + id);
    const playBtn = document.getElementById('playBtn-' + id);
    if(audioBox) audioBox.classList.remove('playing');
    if(playBtn) playBtn.innerHTML = '<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>';
  };
</script>
@endpush