@extends('frontend.layouts.dashboard')

@section('title', 'Từ vựng — Hányǔ Bàn')

@push('styles')
<link href="{{ asset('frontend/asset/css/flashcard.css') }}" rel="stylesheet" />
<link href="{{ asset('frontend/asset/css/tuvung.css') }}" rel="stylesheet" />
<link href="{{ asset('frontend/asset/css/empty-state.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="mb-4">
    <h1 class="font-head fw-bold fs-3 mb-1">Từ vựng của bạn</h1>
    <p class="mb-0" style="color: var(--text-muted)">Theo dõi tiến độ và ôn tập bằng Flashcard (Spaced Repetition).</p>
</div>

<!-- 1. Stats -->
<div class="row g-3 mb-5">
  <div class="col-6 col-lg-3">
    <div class="stat-pill-new">
      <div class="stat-icon icon-orange"><svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2c2 4-1 5-1 8a3 3 0 006 0c2 3 1 7-2 9a7 7 0 01-9-11c1-2 3-3 3-3s1-2 3-3z"/></svg></div>
      <div class="stat-info"><div class="num">{{ auth()->check() ? auth()->user()->streak_thuc_te : 0 }}</div><div class="lbl">Ngày streak</div></div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="stat-pill-new">
      <div class="stat-icon icon-green"><span class="zh fw-bold">词</span></div>
      <div class="stat-info"><div class="num" id="statLearned">–</div><div class="lbl">Từ đã thuộc</div></div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="stat-pill-new">
      <div class="stat-icon icon-red"><svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.4 7.4H22l-6.2 4.5 2.4 7.4L12 16.8l-6.2 4.5 2.4-7.4L2 9.4h7.6z"/></svg></div>
      <div class="stat-info"><div class="num" id="statRate">–</div><div class="lbl">Tỉ lệ nhớ</div></div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="stat-pill-new">
      <div class="stat-icon icon-pink"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg></div>
      <div class="stat-info"><div class="num text-danger" id="statToReview">–</div><div class="lbl">Thẻ cần ôn</div></div>
    </div>
  </div>
</div>

<!-- 2. Decks -->
<h2 class="font-head fs-5 fw-bold mb-4">Bộ từ vựng</h2>
<div class="row g-4 mb-5" id="decksContainer">
  <div class="col-12 text-muted">Đang tải dữ liệu...</div>
</div>

<!-- 3. Vocabulary List -->
<div class="vocab-list-header">
  <h2 class="font-head fs-5 fw-bold mb-0">Danh sách từ vựng</h2>
  
  <div class="search-input-wrap">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8" /><path d="M21 21l-4.35-4.35" /></svg>
    <input type="text" class="search-input" placeholder="Tìm kiếm từ vựng..." id="vocabSearchInput" />
  </div>

  <div class="cat-chips-new" id="catChips">
    <!-- Chips will be generated dynamically -->
  </div>
</div>

<div id="listMode">
  <div id="vocabList" class="text-muted p-4">Đang tải dữ liệu...</div>
</div>

<!-- ================= FLASHCARD OVERLAY (Learning Mode) ================= -->
<div class="flashcard-overlay" id="flashcardOverlay" style="z-index: 2050;">
  <div class="fc-header">
    <button class="btn btn-sm btn-light" id="closeFlashcardBtn" style="border-radius: 999px; border: 1px solid var(--border);">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg> Quay lại
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
      <p style="color: var(--text-muted)" id="completeSummary">Bạn đã ôn xong các thẻ cho phiên này.</p>
      <button class="btn btn-primary mt-3" style="border-radius: 999px;" id="restartSessionBtn">Đóng</button>
    </div>

    <div class="flashcard-zone mx-auto w-100" style="max-width: 500px;" id="flashcardZone">
      <div class="card-scene" id="cardScene">
        <div class="flashcard" id="flashcard">
          <div class="card-face card-front" style="cursor: default;">
            <span class="card-level-tag" id="fcLevel">HSK 1</span>
            <button class="card-bookmark" id="fcBookmark"><svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2z" /></svg></button>
            <div class="card-front-content w-100 px-3 text-center">
              <div style="font-size: 1.8rem; font-weight: 700; color: var(--text); margin-bottom: 0.8rem;" id="fcMeaningFront">bạn / anh / chị</div>
              <button class="card-audio-btn mb-3 mx-auto" id="fcAudioFront" style="position: static; display: flex; width: 50px; height: 50px;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 5L6 9H2v6h4l5 4V5z"/><path d="M15.5 8.5a5 5 0 010 7"/></svg>
              </button>
              
              <div class="typing-zone">
                <div class="ime-suggestions d-none" id="imeSuggestionsBox"></div>
                <input type="text" class="typing-input" id="fcTypingInput" placeholder="Nhập Pinyin (nihao) hoặc Hán tự (你好)..." autocomplete="off">
                <div class="d-flex justify-content-center gap-2 mt-3">
                  <button class="btn btn-primary fw-bold" style="border-radius: 99px; padding: 6px 20px;" id="fcCheckBtn">Kiểm tra</button>
                  <button class="btn btn-light text-muted fw-bold" style="border-radius: 99px; padding: 6px 20px; border: 1px solid var(--border);" id="fcSkipBtn">Xem đáp án</button>
                </div>
              </div>
            </div>
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
            <div id="fcNoteBox" class="mt-2 p-2 w-100 d-none" style="background: #fef3c7; border: 1px solid #fde68a; border-radius: 10px; font-size: 0.85rem; text-align: left; color: #92400e;">
              <strong>Ghi chú:</strong> <span id="fcNoteContent"></span>
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

<!-- Note Modal -->
<div class="modal fade" id="noteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
      <div class="modal-header" style="border-bottom: 1px solid var(--border);">
        <h5 class="modal-title fw-bold font-head">Ghi chú từ vựng</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>
      <div class="modal-body">
        <textarea id="vocabNoteInput" class="form-control" rows="4" placeholder="Nhập mẹo nhớ chữ Hán, câu ví dụ cá nhân..."></textarea>
        <input type="hidden" id="vocabNoteId">
      </div>
      <div class="modal-footer" style="border-top: 1px solid var(--border);">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 99px;">Hủy</button>
        <button type="button" class="btn btn-primary" id="saveNoteBtn" style="border-radius: 99px;">Lưu Ghi chú</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
  /* ---------- Vocabulary Data (Fetched from Backend) ---------- */
  let VOCAB = [];
  let activeCat = "all";

  // Fetch from API
  function fetchVocab() {
      fetch('/api/tu-vung')
          .then(res => {
              if (!res.ok) throw new Error('Lỗi tải dữ liệu: ' + res.status);
              return res.json();
          })
          .then(data => {
              VOCAB = data;
              renderDecksAndChips();
              renderList();
              updateHeaderStats();
          })
          .catch(err => {
              console.error("Error fetching vocab:", err);
              const decksContainer = document.getElementById("decksContainer");
              const vocabList = document.getElementById("vocabList");
              const errorHtml = '<div class="col-12 text-center text-muted p-4">Không tải được dữ liệu từ vựng. <button class="btn btn-sm btn-outline-danger ms-2" onclick="fetchVocab()">Thử lại</button></div>';
              if (decksContainer) decksContainer.innerHTML = errorHtml;
              if (vocabList) vocabList.innerHTML = errorHtml;
          });
  }
  window.fetchVocab = fetchVocab; // để nút "Thử lại" ở trên gọi lại được

  /* ---------- Cập nhật 3 ô thống kê đầu trang từ dữ liệu thật ---------- */
  function updateHeaderStats() {
      const total = VOCAB.length;
      const learned = VOCAB.filter(v => v.learned).length;
      const rate = total > 0 ? Math.round((learned / total) * 100) : 0;
      const toReview = VOCAB.filter(v => !v.learned && (!v.next_review_at || new Date(v.next_review_at) <= new Date())).length;

      const elLearned = document.getElementById("statLearned");
      const elRate = document.getElementById("statRate");
      const elToReview = document.getElementById("statToReview");
      if (elLearned) elLearned.textContent = learned;
      if (elRate) elRate.textContent = rate + "%";
      if (elToReview) elToReview.textContent = toReview;
  }

  /* ---------- Generate Decks & Chips dynamically ---------- */
  function renderDecksAndChips() {
      const decksContainer = document.getElementById("decksContainer");
      const chipsContainer = document.getElementById("catChips");
      if(!decksContainer || !chipsContainer) return;
      
      const decksMap = {};
      VOCAB.forEach(v => {
          if (!decksMap[v.cat]) {
              decksMap[v.cat] = {
                  id: v.cat,
                  title: v.level || ("Bài " + v.cat),
                  total: 0,
                  toReview: 0,
                  char: v.level ? v.level.charAt(0).toUpperCase() : 'B'
              };
          }
          decksMap[v.cat].total++;
          
          const needsReview = !v.learned && (!v.next_review_at || new Date(v.next_review_at) <= new Date());
          if (needsReview) decksMap[v.cat].toReview++;
      });
      
      const decks = Object.values(decksMap);
      
      // Render Chips
      let chipsHtml = `<button class="cat-chip-new active" data-cat="all">Tất cả</button>`;
      decks.forEach(deck => {
          chipsHtml += `<button class="cat-chip-new" data-cat="${deck.id}">${deck.title}</button>`;
      });
      chipsContainer.innerHTML = chipsHtml;
      
      // Render Decks
      if (decks.length === 0) {
          decksContainer.innerHTML = `
          <div class="col-12">
            <div class="empty-state-card theme-primary mx-auto">
              <div class="empty-icon-wrap position-relative">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
              </div>
              <h4 class="empty-state-title">Chưa có bộ Flashcard nào</h4>
              <p class="empty-state-text">
                Hệ thống Flashcard thông minh sẽ tự động tạo bộ từ vựng khi bạn bắt đầu học các bài học.
              </p>
              <a href="/dashboard/khoa-hoc-cua-toi" class="empty-state-btn btn-primary-theme">Đến bài học ngay</a>
            </div>
          </div>`;
          return;
      }
      
      let decksHtml = '';
      decks.forEach(deck => {
          const btnText = deck.toReview > 0 ? "Ôn tập" : "Học ngay";
          decksHtml += `
          <div class="col-lg-4 col-md-6">
            <div class="deck-clean">
              <div class="deck-char">${deck.char}</div>
              <div class="deck-info">
                <div class="deck-title" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:180px;">${deck.title}</div>
                <div class="deck-meta">${deck.total} từ • ${deck.toReview} thẻ cần ôn</div>
              </div>
              <button class="btn-pill-red" data-open-deck="${deck.id}">${btnText}</button>
            </div>
          </div>
          `;
      });
      decksContainer.innerHTML = decksHtml;
      // Dùng event delegation thay vì onclick="...(${deck.id})" để tránh lỗi cú pháp JS
      // khi deck.id là chuỗi (ví dụ slug "gia-dinh") thay vì số.
      decksContainer.querySelectorAll('[data-open-deck]').forEach(function (btn) {
          btn.addEventListener('click', function () {
              openFlashcardSession(this.dataset.openDeck);
          });
      });
  }

  /* ---------- List Mode Render ---------- */
  let currentPage = 1;
  const itemsPerPage = 20;

  function renderList(resetPage = false) {
    if (resetPage) currentPage = 1;

    const container = document.getElementById("vocabList");
    if(!container) return;
    
    const term = document.getElementById("vocabSearchInput") ? document.getElementById("vocabSearchInput").value.toLowerCase() : "";
    let list = activeCat === "all" ? VOCAB : VOCAB.filter(v => v.cat == activeCat); // Loose equal for cat ID string vs int
    if (term) {
      list = list.filter(v => (v.hanzi && v.hanzi.includes(term)) || (v.pinyin && v.pinyin.toLowerCase().includes(term)) || (v.meaning && v.meaning.toLowerCase().includes(term)));
    }
    container.innerHTML = "";
    
    if (list.length === 0) {
        container.innerHTML = `
        <div class="empty-state-card theme-success mx-auto">
            <div class="empty-icon-wrap position-relative">
                <span class="zh fw-bold" style="font-size: 2rem;">词</span>
            </div>
            <h4 class="empty-state-title">Chưa có từ vựng nào</h4>
            <p class="empty-state-text mb-0">
                Bạn chưa thêm hoặc lưu bất kỳ từ vựng nào. Hãy chăm chỉ học tập để làm phong phú vốn từ của mình nhé!
            </p>
        </div>`;
        return;
    }

    const totalPages = Math.ceil(list.length / itemsPerPage);
    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const paginatedList = list.slice(start, end);

    paginatedList.forEach(function (item, index) {
      const row = document.createElement("div");
      row.className = "vocab-item-new";
      
      const hanziChar = item.hanzi ? item.hanzi.slice(0, 1) : '?';
      
      row.innerHTML = `
        <div class="vocab-thumb-new zh">${hanziChar}</div>
        <div class="vocab-info-new" style="flex: 1">
          <div style="display: flex; align-items: baseline; margin-bottom: 0.25rem;">
            <span class="zh fw-bold fs-5">${item.hanzi}</span>
            <span class="vocab-pinyin-red">${item.pinyin}</span>
          </div>
          <div class="vocab-meaning" style="color: var(--text-muted); font-size: 0.9rem;">${item.meaning}</div>
        </div>
        <div class="vocab-actions">
          <span class="vocab-status-new ${item.learned ? "status-learned-new" : "status-new-new"}">${item.learned ? "Đã học" : "Mới"}</span>
          <button class="btn-icon-light" aria-label="Ghi chú" onclick="openNoteModal(${item.id})">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
            ${item.note ? '<span style="position:absolute; top:-2px; right:-2px; width:8px; height:8px; background:var(--danger); border-radius:50%;"></span>' : ''}
          </button>
          <button class="btn-icon-light" aria-label="Nghe phát âm" onclick="speak('${item.hanzi}')">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 5L6 9H2v6h4l5 4V5z"/><path d="M15.5 8.5a5 5 0 010 7"/></svg>
          </button>
          <button class="btn-icon-light ${item.bookmarked ? "bookmarked" : ""}" aria-label="Đánh dấu" onclick="toggleBookmark(${item.id}, this)">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2z"/></svg>
          </button>
        </div>
      `;
      // Note badge position fix
      const noteBtn = row.querySelector("button[aria-label='Ghi chú']");
      if (noteBtn) noteBtn.style.position = 'relative';
      
      container.appendChild(row);
    });

    if (totalPages > 1) {
        let paginationHtml = '<div class="d-flex justify-content-center gap-2 mt-4 mb-2">';
        paginationHtml += `<button class="btn btn-sm ${currentPage === 1 ? 'btn-light disabled' : 'btn-outline-primary'}" style="border-radius:99px;" onclick="changePage(${currentPage - 1})">Trước</button>`;
        
        let startPage = Math.max(1, currentPage - 2);
        let endPage = Math.min(totalPages, currentPage + 2);
        
        for (let i = startPage; i <= endPage; i++) {
            paginationHtml += `<button class="btn btn-sm ${i === currentPage ? 'btn-primary' : 'btn-outline-primary'}" style="border-radius:99px; min-width:32px;" onclick="changePage(${i})">${i}</button>`;
        }
        
        paginationHtml += `<button class="btn btn-sm ${currentPage === totalPages ? 'btn-light disabled' : 'btn-outline-primary'}" style="border-radius:99px;" onclick="changePage(${currentPage + 1})">Sau</button>`;
        paginationHtml += '</div>';
        
        container.insertAdjacentHTML('beforeend', paginationHtml);
    }
  }

  window.changePage = function(page) {
      currentPage = page;
      renderList(false);
      document.querySelector('.vocab-list-header').scrollIntoView({ behavior: 'smooth' });
  }

  // Initial fetch
  fetchVocab();

  window.speak = function(text) {
    if (!("speechSynthesis" in window)) return;
    const utter = new SpeechSynthesisUtterance(text);
    utter.lang = "zh-CN"; utter.rate = 0.85;
    window.speechSynthesis.cancel();
    window.speechSynthesis.speak(utter);
  }

  // Toggle Bookmark API
  window.toggleBookmark = function(id, btn) {
      // Optimistic update
      const item = VOCAB.find(v => v.id == id);
      if(item) {
          item.bookmarked = !item.bookmarked;
          btn.classList.toggle("bookmarked", item.bookmarked);
      }
      
      fetch('/api/tu-vung/bookmark', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
          },
          body: JSON.stringify({ id_tu_vung: id })
      }).catch(err => console.error("Error toggling bookmark:", err));
  }

  // Note Modal Logic
  window.openNoteModal = function(id) {
      const item = VOCAB.find(v => v.id == id);
      if(!item) return;
      document.getElementById('vocabNoteId').value = id;
      document.getElementById('vocabNoteInput').value = item.note || '';
      var noteModal = new bootstrap.Modal(document.getElementById('noteModal'));
      noteModal.show();
  }

  document.getElementById('saveNoteBtn').addEventListener('click', function() {
      const id = document.getElementById('vocabNoteId').value;
      const note = document.getElementById('vocabNoteInput').value;
      
      // Update local array
      const item = VOCAB.find(v => v.id == id);
      if(item) item.note = note;
      
      // Close modal & re-render to show red dot
      bootstrap.Modal.getInstance(document.getElementById('noteModal')).hide();
      renderList();
      
      // API call
      fetch('/api/tu-vung/ghi-chu', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
          },
          body: JSON.stringify({ id_tu_vung: id, note: note })
      }).catch(err => console.error("Error saving note:", err));
  });

  // List Category Filters
  const catChips = document.getElementById("catChips");
  if(catChips) {
      catChips.addEventListener("click", function (e) {
        const chip = e.target.closest(".cat-chip-new");
        if (!chip) return;
        document.querySelectorAll(".cat-chip-new").forEach(c => c.classList.remove("active"));
        chip.classList.add("active");
        activeCat = chip.dataset.cat;
        renderList(true);
      });
  }
  
  const searchInput = document.getElementById("vocabSearchInput");
  if(searchInput) searchInput.addEventListener("input", () => renderList(true));

  /* ---------- Flashcard Overlay Session ---------- */
  let queue = [];
  let currentIndex = 0;
  const flashcard = document.getElementById("flashcard");
  const overlay = document.getElementById("flashcardOverlay");
  const controls = document.getElementById("fcControls");

  window.openFlashcardSession = function(cat) {
    queue = VOCAB.filter(v => v.cat == cat || cat === 'all');
    if (queue.length === 0) return alert("Không có từ vựng nào trong bộ này.");
    currentIndex = 0;
    
    document.getElementById("completePanel").classList.add("d-none");
    document.getElementById("flashcardZone").classList.remove("d-none");
    overlay.classList.add("active");
    renderCard();
  }

  document.getElementById("closeFlashcardBtn")?.addEventListener("click", function() {
    overlay.classList.remove("active");
  });
  document.getElementById("restartSessionBtn")?.addEventListener("click", function() {
    overlay.classList.remove("active");
  });

  function renderCard() {
    if (!queue[currentIndex]) return;
    const item = queue[currentIndex];
    flashcard.classList.remove("flipped");
    controls.style.opacity = 0;
    controls.style.pointerEvents = "none";
    
    // Front side elements
    document.getElementById("fcLevel").textContent = item.level;
    document.getElementById("fcMeaningFront").textContent = item.meaning;
    
    // Reset typing input
    const input = document.getElementById("fcTypingInput");
    input.value = "";
    input.classList.remove("error");
    input.disabled = false;
    document.getElementById("imeSuggestionsBox").classList.add("d-none");
    input.focus();
    
    // Back side elements
    document.getElementById("fcPinyin").textContent = item.pinyin;
    document.getElementById("fcMeaning").textContent = item.meaning;
    document.getElementById("fcExZh").textContent = item.exZh;
    document.getElementById("fcExVi").textContent = item.exVi;
    
    const fcBookmark = document.getElementById("fcBookmark");
    if(fcBookmark) {
        fcBookmark.classList.toggle("active", item.bookmarked);
        fcBookmark.onclick = (e) => {
            e.stopPropagation();
            toggleBookmark(item.id, fcBookmark);
        };
    }
    
    const noteBox = document.getElementById("fcNoteBox");
    const noteContent = document.getElementById("fcNoteContent");
    if (noteBox && noteContent) {
        if (item.note) {
            noteContent.textContent = item.note;
            noteBox.classList.remove("d-none");
        } else {
            noteBox.classList.add("d-none");
        }
    }
    
    const pct = ((currentIndex + 1) / queue.length) * 100;
    document.getElementById("sessionProgress").style.width = pct + "%";
    document.getElementById("sessionCountText").textContent = (currentIndex + 1) + "/" + queue.length;
  }

  if(flashcard) {
      flashcard.addEventListener("click", function (e) {
        // Không lật nếu bấm vào khu vực nhập liệu hoặc nút loa
        if(e.target.closest('.typing-zone') || e.target.closest('.card-audio-btn')) return;
        
        if(!flashcard.classList.contains("flipped")){
            flashcard.classList.add("flipped");
            controls.style.opacity = 1;
            controls.style.pointerEvents = "auto";
            document.getElementById("fcTypingInput").disabled = true;
            document.getElementById("imeSuggestionsBox").classList.add("d-none");
        } else {
            // Lật ngược lại mặt trước
            flashcard.classList.remove("flipped");
        }
      });
  }

  const audioFront = document.getElementById("fcAudioFront");
  const audioBack = document.getElementById("fcAudioBack");
  if(audioFront) audioFront.addEventListener("click", (e) => { e.stopPropagation(); speak(queue[currentIndex].hanzi); });
  if(audioBack) audioBack.addEventListener("click", (e) => { e.stopPropagation(); speak(queue[currentIndex].hanzi); });

  // Function to flip card and show rating buttons
  function revealAnswerAndRate() {
      flashcard.classList.add("flipped");
      controls.style.opacity = 1;
      controls.style.pointerEvents = "auto";
      document.getElementById("fcTypingInput").disabled = true;
      document.getElementById("imeSuggestionsBox").classList.add("d-none");
  }

  // --- Typing Check Logic ---
  function checkAnswer() {
      const input = document.getElementById("fcTypingInput");
      const val = input.value.trim().toLowerCase();
      if(!val) return;
      
      const item = queue[currentIndex];
      const correctHanzi = item.hanzi;
      // Remove tones from pinyin for loose checking
      const correctPinyin = item.pinyin ? item.pinyin.normalize("NFD").replace(/[\u0300-\u036f]/g, "").replace(/\s+/g, "").toLowerCase() : "";
      const userPinyin = val.normalize("NFD").replace(/[\u0300-\u036f]/g, "").replace(/\s+/g, "");
      
      if (val === correctHanzi || userPinyin === correctPinyin) {
          // Correct! 
          input.classList.add("success");
          input.disabled = true;
          
          if (typeof confetti === 'function') {
              confetti({
                  particleCount: 150,
                  spread: 80,
                  origin: { y: 0.6 }
              });
          }
          
          // Delay before flipping the card to show the effect
          setTimeout(() => {
              input.classList.remove("success");
              revealAnswerAndRate();
          }, 800);
      } else {
          // Wrong
          input.classList.add("error");
          setTimeout(() => input.classList.remove("error"), 400);
      }
  }

  document.getElementById("fcCheckBtn")?.addEventListener("click", checkAnswer);
  document.getElementById("fcSkipBtn")?.addEventListener("click", revealAnswerAndRate);
  
  // --- Mini IME Logic for Input ---
  const typingInput = document.getElementById("fcTypingInput");
  const suggestionsBox = document.getElementById("imeSuggestionsBox");
  let currentSuggestions = [];
  let composingMatch = null;
  let composingPinyin = '';
  let debounceTimer = null;
  
  function hideSuggestions() {
      if(suggestionsBox) suggestionsBox.classList.add('d-none');
      currentSuggestions = [];
      composingMatch = null;
      composingPinyin = '';
  }

  function insertSuggestion(text) {
      if (!composingMatch) return;
      const val = typingInput.value;
      const prefix = val.substring(0, composingMatch.index);
      const suffix = val.substring(composingMatch.index + composingPinyin.length);
      
      typingInput.value = prefix + text + suffix;
      hideSuggestions();
      typingInput.focus();
  }

  function renderSuggestions() {
      if (currentSuggestions.length === 0 || !suggestionsBox) {
          hideSuggestions();
          return;
      }
      let html = '';
      currentSuggestions.forEach((text, i) => {
          html += `<span class="ime-candidate" data-index="${i}"><small class="text-muted" style="font-size:0.75rem;">${i+1}.</small> <span class="fw-bold text-primary">${text}</span></span>`;
      });
      suggestionsBox.innerHTML = html;
      suggestionsBox.classList.remove('d-none');
      
      suggestionsBox.querySelectorAll('.ime-candidate').forEach(el => {
          // Use mousedown and touchstart to prevent focus loss and ensure mobile responsiveness
          const selectHandler = function(e) {
              e.preventDefault();
              const index = this.getAttribute('data-index');
              insertSuggestion(currentSuggestions[index]);
          };
          el.addEventListener('mousedown', selectHandler);
          el.addEventListener('touchstart', selectHandler);
      });
  }

  if(typingInput) {
      typingInput.addEventListener('input', function(e) {
          let val = this.value;
          
          // Mobile keyboard fix: Catch Space or Numbers appended to the string
          if (suggestionsBox && !suggestionsBox.classList.contains('d-none') && currentSuggestions.length > 0) {
              const lastChar = val.slice(-1);
              if (lastChar === ' ') {
                  this.value = val.slice(0, -1);
                  insertSuggestion(currentSuggestions[0]);
                  return;
              } else if (['1', '2', '3', '4', '5'].includes(lastChar)) {
                  const index = parseInt(lastChar) - 1;
                  this.value = val.slice(0, -1);
                  if (currentSuggestions[index]) {
                      insertSuggestion(currentSuggestions[index]);
                      return;
                  }
              }
          }

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
      
      typingInput.addEventListener('keydown', function(e) {
          if (suggestionsBox && !suggestionsBox.classList.contains('d-none') && currentSuggestions.length > 0) {
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
          }
          
          if (e.key === 'Enter') {
              e.preventDefault();
              if (suggestionsBox && !suggestionsBox.classList.contains('d-none') && currentSuggestions.length > 0) {
                  // If suggestions are open, just hide them (keep english pinyin)
                  hideSuggestions();
              } else {
                  // Submit answer
                  checkAnswer();
              }
          }
      });
  }

  window.nextCard = function(qualityStr) {
    // Map string to SM-2 quality (0: forgot, 3: hard, 4: good, 5: easy)
    const qualityMap = { 'again': 0, 'hard': 3, 'good': 4, 'easy': 5 };
    const quality = qualityMap[qualityStr];
    
    const item = queue[currentIndex];
    
    // Sync to backend via API
    fetch('/api/tu-vung/srs-sync', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({ id_tu_vung: item.id, quality: quality })
    }).catch(err => console.error("Error syncing SRS:", err));

    flashcard.classList.add(qualityStr === 'again' || qualityStr === 'hard' ? "swipe-out-left" : "swipe-out-right");
    setTimeout(function () {
      flashcard.classList.remove("swipe-out-right", "swipe-out-left");
      currentIndex++;
      if (currentIndex >= queue.length) {
        document.getElementById("sessionProgress").style.width = "100%";
        document.getElementById("flashcardZone").classList.add("d-none");
        document.getElementById("completePanel").classList.remove("d-none");
        // Reload vocab data to update dashboard stats with saved progress
        fetchVocab();
      } else {
        renderCard();
      }
    }, 320);
  }

  // Also reload data if user closes the overlay mid-session
  document.getElementById("closeFlashcardBtn")?.addEventListener("click", function() {
    overlay.classList.remove("active");
    fetchVocab(); 
  });
  document.getElementById("restartSessionBtn")?.addEventListener("click", function() {
    overlay.classList.remove("active");
    // fetchVocab() already called when session completed
  });
});
</script>
@endpush