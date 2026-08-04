@extends('frontend.layouts.exam')

@section('title', 'Luyện thi HSK — Hányǔ Bàn')

@push('styles')
<link href="{{ asset('frontend/asset/css/hsk-exam.css') }}" rel="stylesheet" />
@endpush

@section('content')
<!-- Header: Exam Info & Timer -->
<div class="exam-header d-flex align-items-center justify-content-between flex-wrap gap-3">
  <div class="d-flex align-items-center gap-3">
    <a href="{{ route('frontend.dashboard.luyentap.show', $deThi->id) }}" class="btn btn-outline-secondary rounded-pill d-flex align-items-center gap-2" style="font-weight: 600; padding: 0.5rem 1rem;">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M19 12H5M12 19l-7-7 7-7"/>
      </svg>
      Thoát
    </a>
    <div class="exam-info m-0">
      <h2 class="mb-1" style="font-size: 1.25rem;">{{ $deThi->ten_de_thi }}</h2>
      <p class="m-0 text-muted" style="font-size: 0.85rem;">Tổng số câu: {{ $deThi->so_cau ?? $deThi->cauHois->count() }} câu | Thời gian làm bài: {{ $deThi->thoi_gian_lam }} phút</p>
    </div>
  </div>
  <div class="timer-box">
    <div class="timer" id="examTimer">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="12" cy="12" r="10"/>
        <polyline points="12 6 12 12 16 14"/>
      </svg>
      89:59
    </div>
    <button class="btn btn-outline-danger d-md-none" data-bs-toggle="modal" data-bs-target="#submitModal">Nộp bài</button>
  </div>
</div>

<!-- Layout Container -->
<div class="exam-layout">
  @php
      $listeningQs = $deThi->cauHois->filter(fn($q) => $q->getPart() === 'listening')->values();
      $readingQs = $deThi->cauHois->filter(fn($q) => $q->getPart() === 'reading')->values();
      $writingQs = $deThi->cauHois->filter(fn($q) => $q->getPart() === 'writing')->values();
      
      $totalQs = $deThi->cauHois->count();
      
      // Merge để thứ tự câu hỏi ở phần Main Content khớp 100% với thứ tự ở menu bên trái
      $orderedCauHois = collect()->merge($listeningQs)->merge($readingQs)->merge($writingQs);
  @endphp
  <!-- Nav Panel (Grid) -->
  <div class="exam-nav-panel">
    @if($listeningQs->count() > 0)
    <div class="section-title">
      <span>Phần Nghe hiểu</span>
      <span class="score-preview">0/{{ $listeningQs->count() }}</span>
    </div>
    <div class="q-grid">
      @foreach($listeningQs as $index => $q)
      @php $globalIndex = $index + 1; @endphp
      <div class="q-btn {{ $globalIndex == 1 ? 'active' : '' }}" data-index="{{ $globalIndex }}">{{ $globalIndex }}</div>
      @endforeach
    </div>
    @endif

    @if($readingQs->count() > 0)
    <div class="section-title">
      <span>Phần Đọc hiểu</span>
      <span class="score-preview">0/{{ $readingQs->count() }}</span>
    </div>
    <div class="q-grid">
      @foreach($readingQs as $index => $q)
      @php $globalIndex = $listeningQs->count() + $index + 1; @endphp
      <div class="q-btn" data-index="{{ $globalIndex }}">{{ $globalIndex }}</div>
      @endforeach
    </div>
    @endif
    
    @if($writingQs->count() > 0)
    <div class="section-title">
      <span>Phần Viết</span>
      <span class="score-preview">0/{{ $writingQs->count() }}</span>
    </div>
    <div class="q-grid">
      @foreach($writingQs as $index => $q)
      @php $globalIndex = $listeningQs->count() + $readingQs->count() + $index + 1; @endphp
      <div class="q-btn" data-index="{{ $globalIndex }}">{{ $globalIndex }}</div>
      @endforeach
    </div>
    @endif

    <div class="d-grid mt-4 d-none d-md-block">
      <button class="btn-submit w-100 justify-content-center" data-bs-toggle="modal" data-bs-target="#submitModal">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
          <polyline points="22 4 12 14.01 9 11.01"/>
        </svg>
        Nộp bài thi
      </button>
    </div>
  </div>

  <!-- Main Content -->
  <div class="exam-content-panel">
    
    @foreach($orderedCauHois as $index => $q)
    <div class="question-container {{ $index === 0 ? '' : 'd-none' }}" id="question-{{ $index + 1 }}" data-id="{{ $q->id }}">
      <div class="q-header">
        <div class="q-type-badge">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14"/></svg>
          @if($q->getPart() == 'listening') Phần Nghe Hiểu
          @elseif($q->getPart() == 'reading') Phần Đọc Hiểu
          @else Phần Viết @endif
        </div>
        <div class="q-number">Câu {{ $index + 1 }}</div>
      </div>
      <div class="q-body">
        @if(!str_contains($q->noi_dung, '/'))
        <div class="q-text">{{ $q->noi_dung ?? 'Chưa có nội dung câu hỏi' }}</div>
        @else
        <div class="q-text text-muted" style="font-size: 0.95rem;">Sắp xếp các từ sau thành câu hoàn chỉnh:</div>
        @endif
        
        @if($q->am_thanh)
        <div class="audio-box mt-3 mb-2" id="audioBox-{{ $q->id }}">
          <button class="audio-btn" type="button" onclick="toggleAudio('{{ $q->id }}')" id="playBtn-{{ $q->id }}" aria-label="Phát audio">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>
          </button>
          <div class="audio-wave">
            <div class="wave-bar"></div><div class="wave-bar"></div>
            <div class="wave-bar"></div><div class="wave-bar"></div>
            <div class="wave-bar"></div><div class="wave-bar"></div>
            <div class="wave-bar"></div><div class="wave-bar"></div>
          </div>
          <div class="fw-bold" style="color:var(--text-muted); font-size:0.9rem;" id="timeDisplay-{{ $q->id }}">00:00</div>
          
          <!-- Hidden Audio Element -->
          <audio id="audioEl-{{ $q->id }}" src="{{ asset('storage/' . $q->am_thanh) }}" ontimeupdate="updateAudioTime('{{ $q->id }}')" onended="resetAudioState('{{ $q->id }}')"></audio>
        </div>
        @endif

        @if($q->hinh_anh)
        <div class="text-center mt-3">
          <img src="{{ asset('storage/' . $q->hinh_anh) }}" alt="Hình ảnh câu hỏi" style="max-height: 200px; border-radius: 8px;">
        </div>
        @endif
      </div>

      @if($q->dapAns && $q->dapAns->count() > 1)
      <div class="opt-list grid-2">
        @foreach($q->dapAns as $key => $dapAn)
        <div class="opt-item" onclick="selectOption(this, {{ $index + 1 }})" data-ans="{{ $dapAn->id }}">
          <div class="opt-label">{{ chr(65 + $key) }}</div>
          <div class="opt-text">{{ $dapAn->noi_dung }}</div>
        </div>
        @endforeach
      </div>
      @else
      <div class="mt-4 position-relative">
        @php
           $correct = $q->dapAns->first();
           $pinyinStr = $correct ? $correct->pinyin : '';
           $hanziStr = $correct ? $correct->noi_dung : '';
           $isSortQuestion = str_contains($q->noi_dung, '/');
        @endphp
        
        @if($isSortQuestion)
        <!-- Giao diện Sắp xếp câu (Click to Sort) -->
        <div class="sort-question-container">
            @php
               $words = array_map('trim', explode('/', $q->noi_dung));
               shuffle($words); // Đảo lộn vị trí các từ vựng
            @endphp
            <div class="answer-zone d-flex flex-wrap gap-2 p-3 mb-3 shadow-sm" style="min-height: 60px; border-bottom: 2px solid var(--border); background: #f8fafc; border-radius: 8px;">
                <!-- Các từ được chọn sẽ bay lên đây -->
            </div>
            
            <div class="word-bank d-flex flex-wrap gap-2 p-2 justify-content-center">
                @foreach($words as $wordKey => $word)
                    <button type="button" class="btn btn-outline-secondary sort-word-btn" style="border-radius: 12px; font-size: 1.3rem; font-weight: 500; padding: 10px 20px; transition: all 0.2s;" data-word="{{ $word }}" onclick="toggleSortWord(this, {{ $index + 1 }})">{{ $word }}</button>
                @endforeach
            </div>
            
            <!-- Textarea ẩn để lưu đáp án và chấm điểm -->
            <textarea class="d-none sort-hidden-input" id="sortInput-{{ $index + 1 }}" oninput="inputText(this, {{ $index + 1 }})"></textarea>
        </div>
        @else
        <!-- Giao diện nhập liệu bình thường cho Điền từ/Viết đoạn văn -->
        <div class="position-relative">
            <textarea class="form-control p-3 answer-input" 
                      rows="3" 
                      placeholder="Nhập câu trả lời (Hỗ trợ gõ Pinyin không dấu...)" 
                      oninput="inputText(this, {{ $index + 1 }})" 
                      data-pinyin="{{ $pinyinStr }}" 
                      data-hanzi="{{ $hanziStr }}"
                      style="border-radius: 12px; border-color: var(--border); background: #f8fafc; font-size: 1.1rem;"></textarea>
                      
            <!-- Pinyin Suggestion Box (Mini IME) -->
            <div class="pinyin-suggestion d-none shadow-sm rounded bg-white border p-2 position-absolute" style="top: -55px; left: 10px; z-index: 10; animation: fadeIn 0.2s;">
                <span class="badge bg-light text-muted border me-2">Phím 1-5 / Space</span>
                <div class="imeCandidates d-inline-flex gap-2"></div>
            </div>
        </div>
        @endif
      </div>
      @endif
    </div>
    @endforeach

    <div class="exam-footer">
      <button class="btn-nav" id="btn-prev">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="15 18 9 12 15 6"/>
        </svg>
        Câu trước
      </button>
      <button class="btn-nav" id="btn-next" style="background: var(--primary); color:#fff; border-color:var(--primary);">
        Câu sau
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="9 18 15 12 9 6"/>
        </svg>
      </button>
    </div>

  </div>

</div>

<!-- Submit Modal -->
<div class="modal fade" id="submitModal" tabindex="-1" aria-labelledby="submitModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
      <div class="modal-body text-center p-5">
        <div class="mb-4 d-flex justify-content-center">
            <svg id="submitModalIcon" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-warning">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
        </div>
        <h4 class="fw-bold mb-3" id="submitModalTitle" style="color: var(--text-dark);">Xác nhận nộp bài</h4>
        <p class="text-muted mb-4 fs-5" id="submitModalMessage">Bạn đã hoàn thành 0/{{ $totalQs ?? 40 }} câu hỏi.</p>
        
        <div class="d-flex justify-content-center gap-3 mt-4">
            <button type="button" class="btn btn-light px-4 py-2 border" data-bs-dismiss="modal" style="border-radius: 10px; font-weight: 600; width: 45%;">Hủy bỏ</button>
            <button type="button" class="btn btn-danger px-4 py-2 d-flex align-items-center justify-content-center" id="confirmSubmitBtn" onclick="executeSubmit()" style="border-radius: 10px; font-weight: 600; width: 45%;">
                <span class="spinner-border spinner-border-sm me-2 d-none" id="submitSpinner" role="status" aria-hidden="true"></span>
                <span id="submitBtnText">Đồng ý nộp</span>
            </button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  /* ---------- Custom Audio Player Logic ---------- */
  window.currentAudioPlaying = null;
  
  window.toggleAudio = function(id) {
    const audioEl = document.getElementById('audioEl-' + id);
    const audioBox = document.getElementById('audioBox-' + id);
    const playBtn = document.getElementById('playBtn-' + id);

    if(!audioEl) return;

    if (audioEl.paused) {
      // Pause any other playing audio
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

  /* ---------- Option Selection ---------- */
  window.selectOption = function(el, qIndex) {
    const parent = el.closest('.opt-list');
    parent.querySelectorAll('.opt-item').forEach(item => item.classList.remove('selected'));
    el.classList.add('selected');
    
    // Update grid answered state
    const gridBtn = document.querySelector(`.q-btn[data-index="${qIndex}"]`);
    if (gridBtn) gridBtn.classList.add('answered');
  };

  /* ---------- Sort Word Logic ---------- */
  window.toggleSortWord = function(btn, qIndex) {
    const container = btn.closest('.sort-question-container');
    const answerZone = container.querySelector('.answer-zone');
    const wordBank = container.querySelector('.word-bank');
    const hiddenInput = container.querySelector('.sort-hidden-input');

    if (btn.parentElement.classList.contains('word-bank')) {
      // Đang ở Word Bank -> Bay lên Answer Zone
      answerZone.appendChild(btn);
      btn.classList.remove('btn-outline-secondary');
      btn.classList.add('btn-primary', 'shadow-sm');
    } else {
      // Đang ở Answer Zone -> Bay về Word Bank
      wordBank.appendChild(btn);
      btn.classList.remove('btn-primary', 'shadow-sm');
      btn.classList.add('btn-outline-secondary');
    }

    // Cập nhật textarea ẩn để Grid ghi nhận điểm
    const selectedWords = Array.from(answerZone.querySelectorAll('.sort-word-btn')).map(b => b.getAttribute('data-word'));
    hiddenInput.value = selectedWords.join('');
    hiddenInput.dispatchEvent(new Event('input')); // Gọi logic đổi màu
  };

  /* ---------- Text Input & Pinyin Helper ---------- */
  function removeTones(pinyin) {
    if(!pinyin) return '';
    return pinyin.normalize("NFD").replace(/[\u0300-\u036f]/g, "").replace(/v/g, "u").toLowerCase().trim();
  }

  window.inputText = function(el, qIndex) {
    const gridBtn = document.querySelector(`.q-btn[data-index="${qIndex}"]`);
    if (gridBtn) {
      if (el.value.trim().length > 0) {
        gridBtn.classList.add('answered');
      } else {
        gridBtn.classList.remove('answered');
      }
    }
  };

  document.querySelectorAll('.answer-input').forEach(input => {
    const suggestionBox = input.parentElement.querySelector('.pinyin-suggestion');
    const candidatesContainer = suggestionBox ? suggestionBox.querySelector('.imeCandidates') : null;
    
    let currentSuggestions = [];
    let composingMatch = null;
    let composingPinyin = '';
    let debounceTimer = null;
    
    function hideSuggestions() {
        if(suggestionBox) suggestionBox.classList.add('d-none');
        currentSuggestions = [];
        composingMatch = null;
        composingPinyin = '';
    }

    function insertSuggestion(text) {
        if (!composingMatch) return;
        const val = input.value;
        const prefix = val.substring(0, composingMatch.index);
        const suffix = val.substring(composingMatch.index + composingPinyin.length);
        
        input.value = prefix + text + suffix;
        hideSuggestions();
        input.dispatchEvent(new Event('input')); // trigger oninput
        input.focus();
    }

    function renderSuggestions() {
        if (currentSuggestions.length === 0 || !candidatesContainer) {
            hideSuggestions();
            return;
        }
        let html = '';
        currentSuggestions.forEach((text, i) => {
            html += `<span class="ime-candidate" data-index="${i}" style="cursor:pointer; padding: 2px 5px; border-radius: 4px; transition: background 0.1s;">
                        <small class="text-muted">${i+1}.</small> <span class="fw-bold fs-5 text-primary">${text}</span>
                     </span>`;
        });
        candidatesContainer.innerHTML = html;
        suggestionBox.classList.remove('d-none');
        
        candidatesContainer.querySelectorAll('.ime-candidate').forEach(el => {
            el.addEventListener('click', function() {
                const index = this.getAttribute('data-index');
                insertSuggestion(currentSuggestions[index]);
            });
            el.addEventListener('mouseenter', function() {
                this.style.backgroundColor = 'var(--bs-light)';
            });
            el.addEventListener('mouseleave', function() {
                this.style.backgroundColor = 'transparent';
            });
        });
    }
    
    input.addEventListener('input', function(e) {
        const val = this.value;
        const match = val.match(/[a-zA-Z']+$/);
        
        if (match) {
            composingMatch = match;
            composingPinyin = match[0];
            
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                fetch(`https://inputtools.google.com/request?text=${composingPinyin}&itc=zh-t-i0-pinyin&num=5&cp=0&cs=1&ie=utf-8&oe=utf-8`)
                    .then(res => res.json())
                    .then(data => {
                        if (data[0] === 'SUCCESS' && data[1] && data[1][0] && data[1][0][1]) {
                            currentSuggestions = data[1][0][1];
                            renderSuggestions();
                        }
                    }).catch(err => console.error(err));
            }, 200);
        } else {
            hideSuggestions();
        }
    });
    
    input.addEventListener('keydown', function(e) {
        if (suggestionBox && !suggestionBox.classList.contains('d-none') && currentSuggestions.length > 0) {
            if (['1', '2', '3', '4', '5'].includes(e.key)) {
                e.preventDefault();
                const index = parseInt(e.key) - 1;
                if (currentSuggestions[index]) {
                    insertSuggestion(currentSuggestions[index]);
                }
                return;
            } 
            else if (e.key === ' ') {
                e.preventDefault();
                insertSuggestion(currentSuggestions[0]);
                return;
            }
            else if (e.key === 'Enter') {
                e.preventDefault();
                hideSuggestions();
                return; // Giữ nguyên chữ tiếng anh
            }
        }
    });
  });

  /* ---------- Dynamic Navigation ---------- */
  let currentQuestion = 1;
  const totalQuestions = {{ $totalQs ?? 1 }};

  window.showQuestion = function(index) {
    document.querySelectorAll('.question-container').forEach(el => el.classList.add('d-none'));
    const target = document.getElementById('question-' + index);
    if(target) target.classList.remove('d-none');
    
    document.querySelectorAll('.q-btn').forEach(b => b.classList.remove('active'));
    const gridBtn = document.querySelector(`.q-btn[data-index="${index}"]`);
    if(gridBtn) gridBtn.classList.add('active');

    currentQuestion = index;
  };

  const btnPrev = document.getElementById('btn-prev');
  const btnNext = document.getElementById('btn-next');

  if(btnPrev) {
    btnPrev.addEventListener('click', () => {
      if(currentQuestion > 1) showQuestion(currentQuestion - 1);
    });
  }

  if(btnNext) {
    btnNext.addEventListener('click', () => {
      if(currentQuestion < totalQuestions) showQuestion(currentQuestion + 1);
    });
  }

  document.querySelectorAll('.q-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      showQuestion(parseInt(this.getAttribute('data-index')));
    });
  });

  /* ---------- Submit Logic Hook ---------- */
  const submitModalEl = document.getElementById('submitModal');
  if(submitModalEl) {
      submitModalEl.addEventListener('show.bs.modal', function (event) {
        const answeredCount = document.querySelectorAll('.q-btn.answered').length;
        
        const msg = document.getElementById('submitModalMessage');
        const icon = document.getElementById('submitModalIcon');
        const title = document.getElementById('submitModalTitle');
        const btn = document.getElementById('confirmSubmitBtn');
        
        if (answeredCount < totalQuestions) {
          icon.innerHTML = '<circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line>';
          icon.classList.remove('text-success');
          icon.classList.add('text-warning');
          title.textContent = 'Chưa hoàn thành hết!';
          title.classList.remove('text-success');
          title.classList.add('text-danger');
          msg.innerHTML = `Bạn mới làm được <b>${answeredCount}/${totalQuestions}</b> câu. Bạn có chắc chắn muốn nộp bài sớm không?`;
          btn.classList.remove('btn-primary', 'btn-success');
          btn.classList.add('btn-danger');
        } else {
          icon.innerHTML = '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline>';
          icon.classList.remove('text-warning');
          icon.classList.add('text-success');
          title.textContent = 'Hoàn thành xuất sắc!';
          title.classList.remove('text-danger');
          title.classList.add('text-success');
          msg.innerHTML = `Bạn đã làm đủ <b>${totalQuestions}/${totalQuestions}</b> câu. Chúc mừng bạn! Nhấn nộp bài để nộp nhé.`;
          btn.classList.remove('btn-danger', 'btn-success');
          btn.classList.add('btn-primary');
        }
      });
  }

  window.executeSubmit = function() {
    const btn = document.getElementById('confirmSubmitBtn');
    const spinner = document.getElementById('submitSpinner');
    const text = document.getElementById('submitBtnText');
    const cancelBtn = btn.previousElementSibling;
    
    // Giao diện loading
    btn.disabled = true;
    cancelBtn.disabled = true;
    spinner.classList.remove('d-none');
    text.textContent = 'Đang xử lý...';
    
    // Thu thập đáp án
    const answers = {};
    document.querySelectorAll('.question-container').forEach(q => {
      const qIdMatch = q.id.match(/question-(\d+)/);
      if(!qIdMatch) return;
      const index = qIdMatch[1];
      
      // Lấy id câu hỏi thật từ attribute (cần thêm data-id vào question-container)
      const realQId = q.getAttribute('data-id');
      if(!realQId) return;

      // Trắc nghiệm
      const selectedOpt = q.querySelector('.opt-item.selected');
      if(selectedOpt) {
          answers[realQId] = selectedOpt.getAttribute('onclick').match(/selectOption\(this, \d+, (\d+)\)/)?.[1] || selectedOpt.getAttribute('data-ans'); 
      }
      // Tự luận (Điền từ)
      const textInput = q.querySelector('.answer-input');
      if(textInput && textInput.value.trim() !== '') {
          answers[realQId] = textInput.value.trim();
      }
      // Sắp xếp
      const sortInput = q.querySelector('.sort-hidden-input');
      if(sortInput && sortInput.value.trim() !== '') {
          answers[realQId] = sortInput.value.trim();
      }
    });

    const submitUrl = "{{ route('frontend.dashboard.luyentap.submit', $deThi->id) }}";
    
    // Tính thời gian đã làm
    let timeSpent = 0;
    if (window.totalTime && window.timeLeft !== undefined) {
        timeSpent = window.totalTime - window.timeLeft;
    }

    fetch(submitUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ answers: answers, time_spent: timeSpent })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            document.getElementById('submitModalIcon').innerHTML = '<circle cx="12" cy="12" r="10" fill="#198754" stroke="none"></circle><polyline points="8 12 11 15 16 9" stroke="#fff" stroke-width="2"></polyline>';
            document.getElementById('submitModalTitle').textContent = 'Nộp bài thành công!';
            document.getElementById('submitModalMessage').textContent = 'Đang chuyển hướng sang trang kết quả...';
            
            spinner.classList.add('d-none');
            text.textContent = 'Thành công!';
            btn.classList.remove('btn-danger', 'btn-primary');
            btn.classList.add('btn-success');
            
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 1000);
        }
    })
    .catch(err => {
        console.error(err);
        alert('Có lỗi xảy ra khi nộp bài. Vui lòng thử lại!');
        btn.disabled = false;
        cancelBtn.disabled = false;
        spinner.classList.add('d-none');
        text.textContent = 'Đồng ý nộp';
    });
  };

  /* ---------- Timer Countdown Demo ---------- */
  window.totalTime = {{ $deThi->thoi_gian_lam ?? 90 }} * 60; // Convert minutes to seconds
  window.timeLeft = window.totalTime;
  const timerEl = document.getElementById('examTimer');
  if (timerEl) {
    setInterval(() => {
      if(window.timeLeft <= 0) return;
      window.timeLeft--;
      const m = Math.floor(window.timeLeft / 60).toString().padStart(2, '0');
      const s = (window.timeLeft % 60).toString().padStart(2, '0');
      // keep icon
      const icon = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>';
      timerEl.innerHTML = `${icon} ${m}:${s}`;
      
      // warning below 5 minutes
      if(window.timeLeft < 300) {
        timerEl.classList.add('warning');
      }
    }, 1000);
  }
});
</script>
@endpush
