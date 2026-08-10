@extends('frontend.layouts.dashboard')

@section('title', 'Bảng điều khiển — Hányǔ Bàn')

@section('content')
        <!-- Welcome + streak/xp/level -->
        <div class="row g-3 mb-4">
          <div class="col-12">
            <h1 class="font-head fw-bold fs-3 mb-1">
              Chào buổi sáng, {{ $user->ho_ten ?? $user->ten_dang_nhap ?? 'bạn' }}!
              <span class="zh" style="color: var(--primary)">早上好</span>
            </h1>
            <p class="mb-0" style="color: var(--text-muted)">
              Bạn đã học liên tục {{ auth()->check() ? auth()->user()->streak_thuc_te : 0 }} ngày. Tiếp tục duy trì streak nào!
            </p>
          </div>
        </div>

        <div class="row g-3 mb-4">
          <div class="col-6 col-lg-3">
            <div class="brand-card stat-pill">
              <div class="icon-wrap bg-soft-warning">
                <svg
                  width="22"
                  height="22"
                  viewBox="0 0 24 24"
                  fill="currentColor"
                >
                  <path
                    d="M12 2c2 4-1 5-1 8a3 3 0 006 0c2 3 1 7-2 9a7 7 0 01-9-11c1-2 3-3 3-3s1-2 3-3z"
                  />
                </svg>
              </div>
              <div>
                <div class="num">{{ auth()->check() ? auth()->user()->streak_thuc_te : 0 }}</div>
                <div class="lbl">Ngày streak
                  @if(auth()->check() && auth()->user()->dong_bang_chuoi > 0)
                    <span class="badge bg-info rounded-pill ms-1 d-inline-flex align-items-center" style="font-size: 0.7rem; padding: 0.2em 0.5em; transform: translateY(-2px);" title="Lượt đóng băng chuỗi">
                      <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1">
                        <path d="M12 2v20M2 12h20M19 5l-14 14M5 5l14 14M16 3l-4 4-4-4M3 8l4 4-4 4M21 8l-4 4 4 4M8 21l4-4 4 4" />
                      </svg>
                      {{ auth()->user()->dong_bang_chuoi }}
                    </span>
                  @endif
                </div>
              </div>
            </div>
          </div>
          <div class="col-6 col-lg-3">
            <div class="brand-card stat-pill">
              <div class="icon-wrap bg-soft-primary">
                <svg
                  width="22"
                  height="22"
                  viewBox="0 0 24 24"
                  fill="currentColor"
                >
                  <path
                    d="M12 2l2.4 7.4H22l-6.2 4.5 2.4 7.4L12 16.8l-6.2 4.5 2.4-7.4L2 9.4h7.6z"
                  />
                </svg>
              </div>
              <div>
                <div class="num">{{ auth()->check() ? number_format(auth()->user()->diem_xp) : 0 }}</div>
                <div class="lbl">Tổng điểm XP</div>
              </div>
            </div>
          </div>
          <div class="col-6 col-lg-3">
            <div class="brand-card stat-pill">
              <div class="icon-wrap bg-soft-success">
                <span
                  class="zh fw-bold"
                  style="color: var(--success); font-size: 1.2rem"
                  >级</span
                >
              </div>
              <div>
                <div class="num">Level {{ $hoSo->trinh_do_hien_tai ?? 'Cơ bản' }}</div>
                <div class="lbl">Mục tiêu: HSK {{ $hoSo->muc_tieu_hsk ?? 'N/A' }}</div>
              </div>
            </div>
          </div>
          <div class="col-6 col-lg-3">
            <div class="brand-card stat-pill">
              <div
                class="icon-wrap"
                style="
                  background: color-mix(
                    in srgb,
                    var(--primary) 10%,
                    transparent
                  );
                  color: var(--primary);
                "
              >
                <svg
                  width="22"
                  height="22"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                >
                  <path d="M4 19.5A2.5 2.5 0 016.5 17H20" />
                  <path
                    d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"
                  />
                </svg>
              </div>
              <div>
                <div class="num">{{ $soKhoaHoc }}</div>
                <div class="lbl">Khóa học đang học</div>
              </div>
            </div>
          </div>
        </div>

        <div class="row g-3">
          <!-- Left column -->
          <div class="col-lg-8">
            <!-- Progress chart -->
            <div class="brand-card p-4 mb-3">
              <div
                class="d-flex justify-content-between align-items-center mb-3"
              >
                <h2 class="font-head fs-6 fw-bold mb-0">
                  Tiến độ học trong tuần
                </h2>
                <span class="badge-soft {{ $percentChange >= 0 ? 'bg-soft-success' : 'bg-soft-danger' }}" style="{{ $percentChange < 0 ? 'color: var(--danger);' : '' }}"
                  >{{ $percentChange > 0 ? '+' : '' }}{{ $percentChange }}% so với tuần trước</span
                >
              </div>
              <svg
                viewBox="0 0 560 200"
                width="100%"
                height="200"
                role="img"
                aria-label="Biểu đồ tiến độ học trong tuần"
                id="progressChart"
              >
                <!-- grid lines -->
                <line
                  x1="0"
                  y1="40"
                  x2="560"
                  y2="40"
                  stroke="var(--border)"
                  stroke-width="1"
                />
                <line
                  x1="0"
                  y1="90"
                  x2="560"
                  y2="90"
                  stroke="var(--border)"
                  stroke-width="1"
                />
                <line
                  x1="0"
                  y1="140"
                  x2="560"
                  y2="140"
                  stroke="var(--border)"
                  stroke-width="1"
                />
                <!-- bars -->
                <g id="chartBars"></g>
              </svg>
              <div class="d-flex justify-content-between mt-1 px-1">
                <span class="small" style="color: var(--text-muted)">T2</span>
                <span class="small" style="color: var(--text-muted)">T3</span>
                <span class="small" style="color: var(--text-muted)">T4</span>
                <span class="small" style="color: var(--text-muted)">T5</span>
                <span class="small" style="color: var(--text-muted)">T6</span>
                <span class="small" style="color: var(--text-muted)">T7</span>
                <span class="small" style="color: var(--text-muted)">CN</span>
              </div>
            </div>

            <!-- Continue learning -->
            <div class="brand-card p-4 mb-3">
              <div
                class="d-flex justify-content-between align-items-center mb-3"
              >
                <h2 class="font-head fs-6 fw-bold mb-0">Khóa học đang học</h2>
                <a
                  href="{{ route('frontend.dashboard.khoahoc') }}"
                  class="small fw-semibold text-decoration-none"
                  style="color: var(--primary)"
                  >Xem tất cả</a
                >
              </div>
              <div class="row g-3">
                @forelse($khoaHocDangKys as $dk)
                @php
                  $progress = $dk->phan_tram_hoan_thanh ?? 0;
                  $status = $progress >= 100 ? 'completed' : 'in-progress';
                @endphp
                <div class="col-md-6 col-lg-6">
                  <div class="course-card-dash">
                    <div class="course-cover">
                      @if(!empty($dk->khoaHoc->anh_bia))
                        <img src="{{ asset('storage/' . $dk->khoaHoc->anh_bia) }}" alt="{{ $dk->khoaHoc->ten_khoa_hoc }}">
                      @else
                        <span class="zh-placeholder">{{ mb_substr($dk->khoaHoc->ten_khoa_hoc ?? 'KH', 0, 2) }}</span>
                      @endif
                      <span class="level-badge">
                        {{ $dk->khoaHoc->capDoHsk->ten_cap_do ?? 'Cơ bản' }}
                      </span>
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
                      
                      <div class="course-footer" style="margin-top: 1rem;">
                        <a href="{{ route('frontend.dashboard.khoahoc.resume', ['courseSlug' => $dk->khoaHoc->slug ?? '#']) }}" class="btn w-100 text-decoration-none text-center" style="background: var(--primary); color: #fff; border: none; border-radius: 999px; padding: 0.4rem; font-size: 0.85rem; font-weight: 700;">
                          {{ $status == 'completed' ? 'Xem lại' : 'Tiếp tục' }}
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
                @empty
                <div class="col-12">
                  <p class="text-muted small">Bạn chưa đăng ký khóa học nào.</p>
                </div>
                @endforelse
              </div>

              <!-- Pagination -->
              @if($khoaHocDangKys->hasPages())
              <div class="mt-4">
                {{ $khoaHocDangKys->links('pagination::bootstrap-5') }}
              </div>
              @endif
            </div>

            <!-- Today's plan -->
            <div class="brand-card p-4">
              <h2 class="font-head fs-6 fw-bold mb-3">Đề xuất học hôm nay</h2>
              <div class="plan-item">
                <div class="plan-icon bg-soft-primary">
                  <svg
                    width="18"
                    height="18"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                  >
                    <path d="M4 19.5A2.5 2.5 0 016.5 17H20" />
                    <path
                      d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"
                    />
                  </svg>
                </div>
                <div class="flex-fill">
                  <div class="fw-semibold small">
                    Ôn 20 từ vựng bài "Gia đình"
                  </div>
                  <div class="small" style="color: var(--text-muted)">
                    Spaced Repetition · 5 phút
                  </div>
                </div>
                <span class="badge-soft bg-soft-warning">Ưu tiên</span>
              </div>
              <div class="plan-item">
                <div class="plan-icon bg-soft-success">
                  <svg
                    width="18"
                    height="18"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                  >
                    <path d="M12 19l7-7 3 3-7 7-3-3z" />
                    <path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z" />
                  </svg>
                </div>
                <div class="flex-fill">
                  <div class="fw-semibold small">Luyện viết 5 chữ Hán mới</div>
                  <div class="small" style="color: var(--text-muted)">
                    Stroke Order · 10 phút
                  </div>
                </div>
              </div>
              <div class="plan-item mb-0">
                <div
                  class="plan-icon"
                  style="
                    background: color-mix(
                      in srgb,
                      var(--primary) 10%,
                      transparent
                    );
                    color: var(--primary);
                  "
                >
                  <svg
                    width="18"
                    height="18"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                  >
                    <circle cx="12" cy="12" r="10" />
                    <path d="M12 6v6l4 2" />
                  </svg>
                </div>
                <div class="flex-fill">
                  <div class="fw-semibold small">
                    Làm đề thi thử HSK 3 — Đề số 4
                  </div>
                  <div class="small" style="color: var(--text-muted)">
                    45 phút
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Right column -->
          <div class="col-lg-4">
            <!-- Level ring -->
            <div class="brand-card p-4 mb-3 text-center">
              <h2 class="font-head fs-6 fw-bold mb-3">Mục tiêu tuần này</h2>
              <div class="ring-wrap">
                <svg width="120" height="120" viewBox="0 0 120 120">
                  <circle
                    cx="60"
                    cy="60"
                    r="52"
                    fill="none"
                    stroke="var(--border)"
                    stroke-width="10"
                  />
                  <circle
                    id="ringProgress"
                    cx="60"
                    cy="60"
                    r="52"
                    fill="none"
                    stroke="var(--primary)"
                    stroke-width="10"
                    stroke-linecap="round"
                    stroke-dasharray="327"
                    stroke-dashoffset="327"
                    transform="rotate(-90 60 60)"
                  />
                </svg>
                <div class="ring-label">
                  <span class="pct">72%</span>
                  <span class="txt">5/7 ngày</span>
                </div>
              </div>
              <p class="small mt-3 mb-0" style="color: var(--text-muted)">
                Học thêm 2 ngày nữa để nhận huy hiệu tuần này!
              </p>
            </div>

            <!-- Mini calendar / schedule -->
            <div class="brand-card p-4 mb-3">
              <h2 class="font-head fs-6 fw-bold mb-3">Lịch học tháng 7</h2>
              <div class="mini-cal mb-2">
                <span class="day-lbl">T2</span><span class="day-lbl">T3</span
                ><span class="day-lbl">T4</span><span class="day-lbl">T5</span
                ><span class="day-lbl">T6</span><span class="day-lbl">T7</span
                ><span class="day-lbl">CN</span>
              </div>
              <div class="mini-cal" id="miniCalDays"></div>
            </div>

            <!-- Recent activity -->
            <div class="brand-card p-4">
              <h2 class="font-head fs-6 fw-bold mb-2">Hoạt động gần đây</h2>
              @forelse($hoatDongs as $hd)
              <div class="activity-item">
                <div class="activity-dot"></div>
                <div>
                  <div class="small fw-semibold">
                    Học bài: {{ $hd->baiHoc->tieu_de ?? 'Bài học' }}
                  </div>
                  <div class="small" style="color: var(--text-muted)">
                    {{ $hd->lan_hoc_cuoi ? \Carbon\Carbon::parse($hd->lan_hoc_cuoi)->diffForHumans() : 'Vừa xong' }} 
                    · {{ $hd->trang_thai == 'completed' ? 'Đã hoàn thành' : 'Đang học' }}
                  </div>
                </div>
              </div>
              @empty
              <p class="text-muted small">Chưa có hoạt động nào gần đây.</p>
              @endforelse
            </div>
          </div>
        </div>
@endsection

@push('scripts')
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        // Theme toggle and sidebar toggle logic have been moved to layouts/dashboard.blade.php

        /* ---------- Bar chart (SVG, drawn via JS) ---------- */
        const data = @json($chartData); // % values for 7 days
        const barsGroup = document.getElementById("chartBars");
        const chartW = 560,
          chartH = 180,
          gap = 18;
        const barW = (chartW - gap * (data.length + 1)) / data.length;
        data.forEach(function (val, i) {
          const barH = (val / 100) * (chartH - 20);
          const x = gap + i * (barW + gap);
          const y = chartH - barH;
          const rect = document.createElementNS(
            "http://www.w3.org/2000/svg",
            "rect",
          );
          rect.setAttribute("x", x);
          rect.setAttribute("y", chartH);
          rect.setAttribute("width", barW);
          rect.setAttribute("height", 0);
          rect.setAttribute("rx", 8);
          rect.setAttribute(
            "fill",
            i === 5
              ? "var(--primary)"
              : "color-mix(in srgb, var(--primary) 35%, var(--card))",
          );
          barsGroup.appendChild(rect);
          // animate
          requestAnimationFrame(function () {
            rect.style.transition =
              "y .6s ease " + i * 0.06 + "s, height .6s ease " + i * 0.06 + "s";
            rect.setAttribute("y", y);
            rect.setAttribute("height", barH);
          });
        });

        /* ---------- Ring progress animation ---------- */
        const ring = document.getElementById("ringProgress");
        const circumference = 2 * Math.PI * 52;
        ring.setAttribute("stroke-dasharray", circumference);
        ring.setAttribute("stroke-dashoffset", circumference);
        setTimeout(function () {
          ring.style.transition = "stroke-dashoffset 1s ease";
          ring.setAttribute("stroke-dashoffset", circumference * (1 - 0.72));
        }, 200);

        /* ---------- Mini calendar ---------- */
        const calContainer = document.getElementById("miniCalDays");
        const daysInMonth = 31;
        const startOffset = 2; // July 1 2026 = Wednesday -> index 2 (Mon=0)
        const today = 3;
        const eventDays = [3, 8, 15, 22, 27];
        for (let i = 0; i < startOffset; i++) {
          const empty = document.createElement("span");
          calContainer.appendChild(empty);
        }
        for (let d = 1; d <= daysInMonth; d++) {
          const cell = document.createElement("span");
          cell.className = "day-cell";
          cell.textContent = d;
          if (d === today) cell.classList.add("today");
          else if (eventDays.includes(d)) cell.classList.add("has-event");
          calContainer.appendChild(cell);
        }

        /* ---------- Ajax: fetch dashboard summary on load ---------- */
        const toastEl = document.getElementById("ajaxToast");
        const toastBody = document.getElementById("ajaxToastBody");
        const toast = new bootstrap.Toast(toastEl, { delay: 2500 });

        fetch("https://jsonplaceholder.typicode.com/users/1")
          .then(function (res) {
            if (!res.ok) throw new Error("fetch_failed");
            return res.json();
          })
          .then(function (user) {
            toastBody.textContent = "Đã đồng bộ tiến độ học tập mới nhất.";
            toast.show();
          })
          .catch(function () {
            toastEl.classList.remove("text-bg-primary");
            toastEl.classList.add("text-bg-danger");
            toastBody.textContent =
              "Không thể đồng bộ dữ liệu. Đang hiển thị dữ liệu đã lưu.";
            toast.show();
          });

        /* ---------- Favorite Button AJAX ---------- */
        document.querySelectorAll('.btn-favorite-course').forEach(btn => {
          btn.addEventListener('click', function(e) {
            e.preventDefault();
            const courseId = this.getAttribute('data-id');
            const svg = this.querySelector('svg');
            
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
                  toastBody.textContent = 'Đã thêm vào danh sách yêu thích!';
                } else {
                  svg.setAttribute('fill', 'none');
                  svg.setAttribute('stroke', 'currentColor');
                  toastBody.textContent = 'Đã bỏ yêu thích khóa học!';
                }
                toastEl.classList.remove("text-bg-danger");
                toastEl.classList.add("text-bg-primary");
                toast.show();
              }
            })
            .catch(err => {
              console.error(err);
              toastBody.textContent = 'Có lỗi xảy ra. Vui lòng thử lại!';
              toastEl.classList.remove("text-bg-primary");
              toastEl.classList.add("text-bg-danger");
              toast.show();
            });
          });
        });

        /* ---------- Notification bell demo ---------- */
        document
          .getElementById("notifBtn")
          .addEventListener("click", function () {
            toastEl.classList.remove("text-bg-danger");
            toastEl.classList.add("text-bg-primary");
            toastBody.textContent =
              "Bạn có 3 thông báo mới: nhắc học, huy hiệu mới, bình luận diễn đàn.";
            toast.show();
          });
      });
    </script>
@endpush
