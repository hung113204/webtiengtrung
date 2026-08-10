@extends('frontend.layouts.main')

@section('title', 'Khám phá Khóa học — Hányǔ Bàn')

@push('styles')
<link href="{{ asset('frontend/asset/css/chinesecourses.css') }}" rel="stylesheet" />
<style>
/* ===== PAGE: COURSES ===== */
body { background: #f8fafc; }
body::before {
  content:''; position:fixed; top:-15%; left:-10%;
  width:600px; height:600px; border-radius:50%;
  background:radial-gradient(circle,rgba(239,68,68,.10) 0%,transparent 70%);
  z-index:-1; pointer-events:none;
}
body::after {
  content:''; position:fixed; bottom:-15%; right:-10%;
  width:700px; height:700px; border-radius:50%;
  background:radial-gradient(circle,rgba(245,158,11,.10) 0%,transparent 70%);
  z-index:-1; pointer-events:none;
}

/* Hero */
.courses-hero { text-align:center; padding: 0 0 3rem; }
.courses-hero h1 {
  font-family: var(--font-head); font-weight:800; font-size:clamp(1.8rem,4vw,3rem);
  background:linear-gradient(135deg,#1e293b,#ef4444); -webkit-background-clip:text;
  -webkit-text-fill-color:transparent; margin-bottom:1rem;
}
.courses-hero p { color:var(--text-muted); font-size:1.1rem; max-width:600px; margin:0 auto 2rem; }
.search-bar-wrapper {
  display:flex; align-items:center; background:rgba(255,255,255,.85);
  backdrop-filter:blur(12px); border:1px solid rgba(255,255,255,.9);
  border-radius:50px; box-shadow:0 8px 30px rgba(0,0,0,.08);
  padding:.5rem .5rem .5rem 1rem; margin:0 auto; max-width:520px; gap:.5rem;
}
.search-bar-wrapper input {
  flex:1; border:none; background:transparent; outline:none;
  font-size:.95rem; color:var(--text);
}
.btn-search {
  background:linear-gradient(135deg,#ef4444,#f59e0b); color:#fff;
  border:none; border-radius:50px; padding:.5rem 1.4rem;
  font-weight:600; cursor:pointer; white-space:nowrap;
  transition:box-shadow .3s ease;
}
.btn-search:hover { box-shadow:0 4px 15px rgba(239,68,68,.35); }

/* Filter chips */
.course-filters {
  display:flex; flex-wrap:wrap; gap:.6rem; justify-content:center; margin-bottom:2.5rem;
}
.filter-btn {
  padding:.45rem 1.1rem; border-radius:50px; border:1px solid var(--border);
  background:rgba(255,255,255,.7); backdrop-filter:blur(6px);
  color:var(--text); font-size:.875rem; cursor:pointer;
  transition:all .3s ease;
}
.filter-btn:hover { transform:translateY(-2px); box-shadow:0 4px 12px rgba(0,0,0,.06); }
.filter-btn.active {
  background:linear-gradient(135deg,#ef4444,#f59e0b); color:#fff;
  border-color:transparent; box-shadow:0 6px 20px rgba(239,68,68,.3);
}
.filter-btn-child {
  font-size:.8rem;
  padding:.3rem .85rem;
  opacity:.88;
}

/* Course card styles removed - using global .brand-card .course-card instead */

/* Dark mode */
[data-theme="dark"] body { background:#0f172a; }
[data-theme="dark"] .course-card {
  background:rgba(30,41,59,.7); border-color:rgba(255,255,255,.08);
}
[data-theme="dark"] .filter-btn {
  background:rgba(30,41,59,.7); border-color:rgba(255,255,255,.1);
}
[data-theme="dark"] .search-bar-wrapper {
  background:rgba(30,41,59,.8); border-color:rgba(255,255,255,.1);
}
[data-theme="dark"] .search-bar-wrapper input { color:#f8fafc; }
</style>
@endpush

@section('content')
<div class="container" style="padding-top:5rem; padding-bottom:5rem;">

  {{-- Hero --}}
  <div class="courses-hero">
    <h1>{{ \App\Models\CauHinh::getByKey('khoahoc_page_title', 'Khám phá lộ trình học tiếng Trung của bạn') }}</h1>
    <p>{{ \App\Models\CauHinh::getByKey('khoahoc_page_description', 'Hàng chục khóa học chất lượng cao, từ cơ bản đến nâng cao, giao tiếp thực tế và luyện thi HSK chứng chỉ quốc tế.') }}</p>
    <div class="search-bar-wrapper">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.3-4.3"/></svg>
      <input type="text" id="courseSearch" placeholder="Bạn muốn học gì hôm nay?">
      <button class="btn-search">Tìm kiếm</button>
    </div>
  </div>

  {{-- Filter chips --}}
  @php
    $requestedSlug = request('danh_muc');
    $navItems = collect($navDanhMuc ?? []);
    $currentRoot = $navItems->first(function ($dm) use ($requestedSlug) {
        return $dm->slug === $requestedSlug || $dm->children->contains('slug', $requestedSlug);
    });
    $currentRoot ??= $navItems->firstWhere('slug', 'khoa-hoc');
    $filterDanhMucs = $currentRoot?->children ?? $navItems;
  @endphp
  <div class="course-filters" id="filterBar">
    <button class="filter-btn active" data-cat="all">Tất cả</button>
    @foreach($filterDanhMucs as $dm)
      <button class="filter-btn" data-cat="{{ $dm->slug }}">{{ $dm->ten_danh_muc }}</button>
    @endforeach
    @unless($currentRoot)
    <button class="filter-btn" data-cat="free">Miễn phí</button>
    @endunless
  </div>



  {{-- Course grid --}}
  <div class="row g-4" id="courseGrid">

    @forelse($khoaHocs as $kh)
      @php
        $gv        = $kh->giaoViens->first();
        $gvTen     = $gv?->ho_ten ?? $gv?->nguoiDung?->ho_ten ?? 'Giảng viên';
        $gvAvatar  = $gv?->anh_dai_dien ?? null;
        $isFree    = ($kh->gia == 0 || $kh->gia === null);
        $price     = $kh->gia_giam ?? $kh->gia;
        $cat       = $kh->danhMucKhoaHoc;
        $catSlugs  = collect([
            $cat?->slug,
            $cat?->parent?->slug,
            $isFree ? 'free' : null,
        ])->filter()->unique()->implode(' ');
        $capDo     = $kh->capDoHSK?->ten_cap_do ?? null;
      @endphp
      <div class="col-md-6 col-lg-3 course-item" data-cat="{{ $catSlugs }}">
        <a href="{{ route('khoahoc.show', $kh->slug) }}" class="d-block text-decoration-none text-reset h-100">
          <div class="brand-card course-card overflow-hidden h-100">
            <div class="cover" style="{{ $kh->anh_bia ? 'background-image:url('.asset('storage/'.$kh->anh_bia).');background-size:cover;background-position:center;' : '' }}">
              @if(!$kh->anh_bia)<span class="zh">汉</span>@endif
              <span class="level-badge">{{ $capDo ?? 'Cơ bản' }}</span>
              
              {{-- Nút Yêu thích --}}
              <button class="btn-favorite-course shadow-sm" data-id="{{ $kh->id }}" aria-label="Yêu thích khóa học" style="position: absolute; top: 12px; right: 12px; z-index: 10; background: rgba(255,255,255,0.9); border: none; border-radius: 50%; width: 34px; height: 34px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: transform 0.2s;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="{{ in_array($kh->id, $likedCourseIds ?? []) ? 'red' : 'none' }}" stroke="{{ in_array($kh->id, $likedCourseIds ?? []) ? 'red' : 'currentColor' }}" stroke-width="2">
                  <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                </svg>
              </button>
            </div>
            <div class="p-3 d-flex flex-column" style="height:calc(100% - 150px);">
              <h3 class="font-head fs-6 fw-bold mb-1">{{ $kh->ten_khoa_hoc }}</h3>
              @php
                  $soBaiHoc = max($kh->bai_hocs_count ?? 0, $kh->tong_bai_hoc ?? 0);
              @endphp
              <p class="small mb-auto" style="color:var(--text-muted);">{{ $soBaiHoc }} bài · {{ $gvTen }}</p>
              <div class="d-flex justify-content-between align-items-end mt-3">
                @php
                  $rating = $kh->danh_gias_avg_so_sao ? number_format($kh->danh_gias_avg_so_sao, 1) : '5.0';
                  $ratingCount = $kh->danh_gias_count ?? 0;
                @endphp
                <span class="rating-stars" style="color: #ffc107;">★★★★★ <span style="color:var(--text-muted);">{{ $rating }} ({{ $ratingCount }})</span></span>
                <div class="text-end" style="line-height: 1.2;">
                  @if($kh->gia_giam > 0 && $kh->gia_giam < $kh->gia)
                    <span class="fw-bold text-primary-brand d-block">{{ number_format($kh->gia_giam, 0, ',', '.') }}đ</span>
                    <span class="small text-muted text-decoration-line-through">{{ number_format($kh->gia, 0, ',', '.') }}đ</span>
                  @elseif($kh->gia > 0)
                    <span class="fw-bold text-primary-brand">{{ number_format($kh->gia, 0, ',', '.') }}đ</span>
                  @else
                    <span class="fw-bold text-success">Miễn phí</span>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    @empty
      <div class="col-12 text-center py-5">
        <span style="font-size:3rem;">找</span>
        <h3 class="font-head fw-bold mt-2">Chưa có khóa học nào</h3>
        <p class="text-muted">Vui lòng quay lại sau.</p>
      </div>
    @endforelse

  </div>{{-- /row --}}

  {{-- Empty state (JS filter) --}}
  <div class="text-center py-5 d-none" id="emptyState">
    <span style="font-size:3rem;">找</span>
    <h3 class="font-head fw-bold mt-2">Không tìm thấy khóa học nào</h3>
    <p class="text-muted">Hãy thử từ khóa hoặc danh mục khác.</p>
  </div>


</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const items = Array.from(document.querySelectorAll('.course-item'));
  const grid  = document.getElementById('courseGrid');
  const empty = document.getElementById('emptyState');
  const searchInput = document.getElementById('courseSearch');
  let activeCat = 'all';

  function applyFilters() {
    const q = (searchInput.value || '').trim().toLowerCase();
    let visible = 0;
    items.forEach(function(item) {
      const cats = (item.dataset.cat || '').split(/\s+/).filter(Boolean);
      const catOk = activeCat === 'all' || cats.includes(activeCat);
      const titleEl = item.querySelector('.cc-title');
      const title   = titleEl ? titleEl.textContent.toLowerCase() : '';
      const show    = catOk && (!q || title.includes(q));
      item.classList.toggle('d-none', !show);
      if (show) visible++;
    });
    empty.classList.toggle('d-none', visible > 0);
    grid.classList.toggle('d-none', visible === 0);
  }

  // Filter chips
  document.getElementById('filterBar').addEventListener('click', function(e) {
    const btn = e.target.closest('.filter-btn');
    if (!btn) return;
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    activeCat = btn.dataset.cat;
    applyFilters();
  });

  // Search
  searchInput.addEventListener('input', applyFilters);
  document.querySelector('.btn-search').addEventListener('click', applyFilters);

  // Favorite AJAX
  document.querySelectorAll('.btn-favorite-course').forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation(); // prevent clicking through to the course link
      
      const courseId = this.getAttribute('data-id');
      const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
      
      if (!token) {
        alert('Vui lòng đăng nhập để yêu thích khóa học.');
        window.location.href = '/login';
        return;
      }

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
          const svg = this.querySelector('svg');
          if (data.status === 'added') {
            svg.setAttribute('fill', 'red');
            svg.setAttribute('stroke', 'red');
            this.style.transform = 'scale(1.2)';
            setTimeout(() => this.style.transform = 'scale(1)', 200);
          } else {
            svg.setAttribute('fill', 'none');
            svg.setAttribute('stroke', 'currentColor');
          }
        } else {
          alert(data.message || 'Vui lòng đăng nhập để thực hiện.');
          if(data.message === 'Vui lòng đăng nhập để thực hiện.') window.location.href = '/login';
        }
      })
      .catch(err => {
        console.error(err);
        alert('Vui lòng đăng nhập để thực hiện.');
        window.location.href = '/login';
      });
    });
  });
});
</script>
@endpush
