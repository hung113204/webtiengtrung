@extends('frontend.layouts.dashboard')

@section('title', 'Khóa học của tôi — Hányǔ Bàn')

@push('styles')
<link href="{{ asset('frontend/asset/css/chinesecourses.css') }}" rel="stylesheet" />
<link href="{{ asset('frontend/asset/css/empty-state.css') }}" rel="stylesheet" />
@endpush

@section('content')
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
          <div>
            <h1 class="font-head fw-bold fs-3 mb-1">Khóa học của tôi</h1>
            <p class="mb-0" style="color: var(--text-muted)">
              Quản lý tiến độ và khám phá thêm khóa học mới.
            </p>
          </div>
          <a href="{{ route('khoahoc.index') }}" class="btn-continue px-3 py-2">+ Khám phá khóa học</a>
        </div>

        <!-- Status tabs -->
        <div class="status-tabs" id="statusTabs">
          <button class="status-tab active" data-status="all">
            Tất cả <span class="count">{{ $tongSo }}</span>
          </button>
          <button class="status-tab" data-status="in-progress">
            Đang học <span class="count">{{ $soDangHoc }}</span>
          </button>
          <button class="status-tab" data-status="completed">
            Đã hoàn thành <span class="count">{{ $soHoanThanh }}</span>
          </button>
          <!-- Có thể thêm phần đã lưu nếu có bảng bookmark khóa học -->
        </div>

        <!-- Filter bar -->
        <div class="filter-bar">
          <div class="search-input-wrap">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="11" cy="11" r="8" />
              <path d="M21 21l-4.35-4.35" />
            </svg>
            <input type="text" class="search-input" id="courseSearch" placeholder="Tìm trong khóa học của tôi..." />
          </div>
          <button class="filter-chip active" data-cat="all">Tất cả</button>
          <select class="sort-select" id="sortSelect">
            <option value="recent">Học gần nhất</option>
            <option value="progress">Tiến độ cao nhất</option>
            <option value="az">Tên A–Z</option>
          </select>
        </div>

        <p class="results-count" id="resultsCount">Hiển thị {{ $tongSo }} khóa học</p>

        <!-- Course grid -->
        <div class="row g-3" id="courseGrid">
          @forelse($khoaHocDangKys as $dk)
          @php
            $progress = $dk->phan_tram_hoan_thanh ?? 0; 
            $status = $progress >= 100 ? 'completed' : 'in-progress';
          @endphp
          <div
            class="col-sm-6 col-lg-4 course-item"
            data-status="{{ $status }}"
            data-cat="all"
            data-title="{{ $dk->khoaHoc->ten_khoa_hoc ?? 'Khóa học' }}"
            data-progress="{{ $progress }}"
          >
            <div class="course-card-dash">
              <div class="course-cover">
                @if(!empty($dk->khoaHoc->anh_bia))
                  <img src="{{ asset('storage/' . $dk->khoaHoc->anh_bia) }}" alt="{{ $dk->khoaHoc->ten_khoa_hoc }}">
                @else
                  <span class="zh-placeholder">{{ mb_substr($dk->khoaHoc->ten_khoa_hoc ?? 'KH', 0, 2) }}</span>
                @endif
                <span class="level-badge">{{ $dk->khoaHoc->capDoHsk->ten_cap_do ?? 'Sơ cấp' }}</span>
                @php
                  $isFavorited = in_array($dk->id_khoa_hoc, $yeuThichIds ?? []);
                @endphp
                {{-- Nút Yêu thích --}}
                <button class="btn-favorite-course shadow-sm" data-id="{{ $dk->id_khoa_hoc }}" aria-label="Yêu thích khóa học" style="position: absolute; top: 12px; right: 12px; z-index: 10; background: rgba(255,255,255,0.9); border: none; border-radius: 50%; width: 34px; height: 34px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: transform 0.2s;">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="{{ $isFavorited ? 'red' : 'none' }}" stroke="{{ $isFavorited ? 'red' : 'currentColor' }}" stroke-width="2">
                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                  </svg>
                </button>
              </div>
              <div class="course-body">
                @if($status == 'completed')
                <span class="badge-soft bg-soft-warning align-self-start">Hoàn thành</span>
                @else
                <span class="badge-soft bg-soft-success align-self-start">Đang học</span>
                @endif
                
                <div class="course-title mt-2">
                  {{ $dk->khoaHoc->ten_khoa_hoc ?? 'Khóa học' }}
                </div>
                
                <div class="course-meta">
                  <span style="display: flex; align-items: center; gap: 0.3rem;"><span style="color: #fbbf24;">★★★★★</span></span>
                  <span style="display: flex; align-items: center; gap: 0.3rem;">· {{ $dk->khoaHoc->giaoViens->first()?->nguoiDung->ho_ten ?? 'Giảng viên' }}</span>
                </div>
                
                <div class="mt-auto pt-3">
                  <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-muted" style="font-size:0.75rem;">Tiến độ</span>
                    <span class="fw-bold" style="font-size:0.8rem; color:var(--primary);">{{ round($progress) }}%</span>
                  </div>
                  <div class="progress-thin">
                    <div class="fill" style="width: {{ $progress }}%;"></div>
                  </div>
                </div>

                <div class="course-footer">
                  <a href="{{ route('frontend.dashboard.khoahoc.resume', ['courseSlug' => $dk->khoaHoc->slug ?? 'slug']) }}" class="btn w-100 text-decoration-none text-center" style="background: var(--primary); color: #fff; border: none; border-radius: 999px; padding: 0.5rem; font-size: 0.9rem; font-weight: 700;">
                    {{ $status == 'completed' ? 'Xem lại' : 'Tiếp tục học' }}
                  </a>
                </div>
              </div>
            </div>
          </div>
          @empty
          <div class="col-12">
            <div class="empty-state-card theme-danger mx-auto">
              <div class="empty-icon-wrap position-relative">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                  <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                </svg>
                <div class="position-absolute" style="top: -5px; right: -5px;">
                  <span class="badge rounded-pill bg-warning text-dark" style="font-size: 0.7rem; padding: 0.35em 0.65em;">Mới</span>
                </div>
              </div>
              
              <h3 class="empty-state-title">Bạn chưa đăng ký khóa học nào!</h3>
              
              <p class="empty-state-text">
                Hành trình ngàn dặm bắt đầu từ một bước chân. Khám phá các khóa học Hán ngữ độc quyền và bắt đầu nâng cao trình độ của bạn ngay hôm nay.
              </p>
              
              <a href="{{ route('khoahoc.index') }}" class="empty-state-btn btn-danger-theme">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="11" cy="11" r="8"></circle>
                  <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                Khám phá khóa học ngay
              </a>
            </div>
          </div>
          @endforelse
        </div>

        <!-- Empty state (hidden by default) -->
        <div class="empty-state d-none" id="emptyState" style="width: 100%;">
          <div class="empty-state-card theme-neutral w-100">
            <div class="empty-icon-wrap">
              <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
              </svg>
            </div>
            <h4 class="empty-state-title">Không tìm thấy khóa học nào</h4>
            <p class="empty-state-text mb-4">Hãy thử từ khóa tìm kiếm khác hoặc chọn một danh mục khác.</p>
            <button class="empty-state-btn" id="resetFiltersBtn" style="background: #e9ecef; color: #495057; border: 1px solid #dee2e6;">
              Xóa bộ lọc
            </button>
          </div>
        </div>
@endsection

@push('scripts')
<script>
  document.addEventListener("DOMContentLoaded", function () {
    /* ---------- Favorite Button AJAX ---------- */
    document.querySelectorAll('.btn-favorite-course').forEach(btn => {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        const courseId = this.getAttribute('data-id');
        const svg = this.querySelector('svg');
        
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
          if (data.success) {
            if (data.status === 'added') {
              svg.setAttribute('fill', 'red');
              svg.setAttribute('stroke', 'red');
              this.style.transform = 'scale(1.2)';
              setTimeout(() => this.style.transform = 'scale(1)', 200);
            } else {
              svg.setAttribute('fill', 'none');
              svg.setAttribute('stroke', 'currentColor');
            }
          }
        })
        .catch(err => console.error(err));
      });
    });
  });
</script>
@endpush

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    /* ---------- Save / bookmark toggle ---------- */
    document.querySelectorAll(".save-btn").forEach(function (btn) {
        btn.addEventListener("click", function (e) {
        e.stopPropagation();
        btn.classList.toggle("active");
        });
    });

    /* ---------- Filtering logic ---------- */
    const items = Array.from(document.querySelectorAll(".course-item"));
    const grid = document.getElementById("courseGrid");
    const emptyState = document.getElementById("emptyState");
    const resultsCount = document.getElementById("resultsCount");
    const searchInput = document.getElementById("courseSearch");
    const sortSelect = document.getElementById("sortSelect");
    let activeStatus = "all";
    let activeCat = "all";

    function applyFilters() {
        const q = searchInput.value.trim().toLowerCase();
        let visibleCount = 0;

        items.forEach(function (item) {
        const matchesStatus = activeStatus === "all" || item.dataset.status === activeStatus;
        const matchesCat = activeCat === "all" || item.dataset.cat === activeCat;
        const matchesSearch = item.dataset.title.toLowerCase().includes(q);
        const show = matchesStatus && matchesCat && matchesSearch;
        item.classList.toggle("d-none", !show);
        if (show) visibleCount++;
        });

        resultsCount.textContent = "Hiển thị " + visibleCount + " khóa học";
        emptyState.classList.toggle("d-none", visibleCount !== 0);
        grid.classList.toggle("d-none", visibleCount === 0);

        applySort();
    }

    function applySort() {
        const sortBy = sortSelect.value;
        const visibleItems = items.filter((i) => !i.classList.contains("d-none"));
        visibleItems.sort(function (a, b) {
        if (sortBy === "progress") return parseInt(b.dataset.progress) - parseInt(a.dataset.progress);
        if (sortBy === "az") return a.dataset.title.localeCompare(b.dataset.title);
        return 0; // 'recent' keeps original order
        });
        visibleItems.forEach(function (item) { grid.appendChild(item); });
    }

    document.getElementById("statusTabs").addEventListener("click", function (e) {
        const btn = e.target.closest(".status-tab");
        if (!btn) return;
        document.querySelectorAll(".status-tab").forEach((t) => t.classList.remove("active"));
        btn.classList.add("active");
        activeStatus = btn.dataset.status;
        applyFilters();
    });

    document.querySelectorAll(".filter-chip").forEach(function (chip) {
        chip.addEventListener("click", function () {
        document.querySelectorAll(".filter-chip").forEach((c) => c.classList.remove("active"));
        chip.classList.add("active");
        activeCat = chip.dataset.cat;
        applyFilters();
        });
    });

    searchInput.addEventListener("input", applyFilters);
    sortSelect.addEventListener("change", applySort);

    document.getElementById("resetFiltersBtn").addEventListener("click", function () {
        activeStatus = "all";
        activeCat = "all";
        searchInput.value = "";
        document.querySelectorAll(".status-tab").forEach((t, i) => t.classList.toggle("active", i === 0));
        document.querySelectorAll(".filter-chip").forEach((c, i) => c.classList.toggle("active", i === 0));
        applyFilters();
    });
});
</script>
@endpush
