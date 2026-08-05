@extends('frontend.layouts.main')

@section('title', $khoaHoc->ten_khoa_hoc . ' — Hányǔ Bàn')
@section('description', $khoaHoc->mo_ta_ngan ?? 'Chi tiết khóa học ' . $khoaHoc->ten_khoa_hoc)

@push('styles')
<link href="{{ asset('frontend/asset/css/course-detail.css') }}" rel="stylesheet">
@endpush

@section('content')

@php
    $firstFreeVideo = null;
    $firstIsExternal = 0;

    // Ưu tiên video giới thiệu khóa học trước
    if (!empty($khoaHoc->video_url)) {
        $firstFreeVideo = $khoaHoc->video_url;
        $firstIsExternal = 1;
    } elseif (!empty($khoaHoc->video_id) && $khoaHoc->videoItem) {
        $firstFreeVideo = $khoaHoc->videoItem->file_path;
        $firstIsExternal = 0;
    } else {
        // Fallback: lấy video học thử đầu tiên
        if($khoaHoc->chuongHocs) {
            foreach($khoaHoc->chuongHocs as $chuong) {
                foreach($chuong->baiHocs as $bai) {
                    if($bai->mien_phi && $bai->video) {
                        $firstFreeVideo = $bai->video;
                        $firstIsExternal = preg_match('#^https?://#i', $bai->video) ? 1 : 0;
                        break 2;
                    }
                }
            }
        }
    }
@endphp

{{-- ================= HERO ================= --}}
<section class="cd-hero">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="cd-hero-content">
                    {{-- Breadcrumb --}}
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('khoahoc.index') }}">Khóa học</a></li>
                            @if($khoaHoc->danhMucKhoaHoc)
                                <li class="breadcrumb-item">
                                    <a href="{{ route('khoahoc.index', ['danh_muc' => $khoaHoc->danhMucKhoaHoc->slug]) }}">
                                        {{ $khoaHoc->danhMucKhoaHoc->ten_danh_muc }}
                                    </a>
                                </li>
                            @endif
                            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($khoaHoc->ten_khoa_hoc, 40) }}</li>
                        </ol>
                    </nav>

                    <h1 class="cd-title">{{ $khoaHoc->ten_khoa_hoc }}</h1>

                    @if($khoaHoc->mo_ta_ngan)
                        <p class="cd-subtitle">{{ $khoaHoc->mo_ta_ngan }}</p>
                    @endif

                    {{-- Meta --}}
                    <div class="cd-meta">
                        @if($khoaHoc->danh_gias_avg_so_sao)
                            <div class="cd-meta-item" style="color:#f59e0b; font-weight:600;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                {{ number_format($khoaHoc->danh_gias_avg_so_sao, 1) }}
                                ({{ number_format($khoaHoc->danh_gias_count) }} đánh giá)
                            </div>
                        @endif
                        <div class="cd-meta-item">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                            {{ number_format($khoaHoc->dang_ky_khoa_hocs_count ?? 0) }} học viên
                        </div>
                        <div class="cd-meta-item">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8"/></svg>
                            {{ $tongBaiHoc }} bài giảng
                        </div>
                        <div class="cd-meta-item">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            Cập nhật: {{ $khoaHoc->updated_at->format('m/Y') }}
                        </div>
                        <div class="cd-meta-item">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>
                            Tiếng Việt
                        </div>
                    </div>

                    {{-- Giáo viên --}}
                    @if($khoaHoc->giaoViens->isNotEmpty())
                        @foreach($khoaHoc->giaoViens->take(1) as $gv)
                            <div class="cd-instructor">
                                @if($gv->anh_dai_dien)
                                    <img src="{{ asset('storage/' . $gv->anh_dai_dien) }}" alt="{{ $gv->nguoiDung->ho_ten ?? '' }}" style="width:48px;height:48px;border-radius:50%;object-fit:cover;">
                                @elseif($gv->nguoiDung && $gv->nguoiDung->anh_dai_dien)
                                    <img src="{{ asset('storage/' . $gv->nguoiDung->anh_dai_dien) }}" alt="{{ $gv->nguoiDung->ho_ten ?? '' }}" style="width:48px;height:48px;border-radius:50%;object-fit:cover;">
                                @else
                                    <div class="cd-instructor-avatar-placeholder">
                                        {{ mb_substr($gv->nguoiDung->ho_ten ?? 'G', 0, 1, 'UTF-8') }}
                                    </div>
                                @endif
                                <div>
                                    <div style="font-weight:600; font-size:0.95rem;">
                                        Được giảng dạy bởi
                                        <span style="color:#fff; text-decoration:underline;">{{ $gv->nguoiDung->ho_ten ?? 'Giáo viên' }}</span>
                                    </div>
                                    @if($gv->chuyen_mon)
                                        <div style="font-size:0.85rem; opacity:0.8;">{{ $gv->chuyen_mon }}</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ================= MAIN BODY ================= --}}
<section class="container" style="padding-bottom:5rem;">
    <div class="row">

        {{-- ---- CỘT TRÁI: NỘI DUNG ---- --}}
        <div class="col-lg-8 cd-content-section" style="padding-top:3rem;">

            {{-- Bạn sẽ học được gì? --}}
            @if($khoaHoc->loiIch->isNotEmpty())
                <div class="mb-5">
                    <h2 class="cd-section-title">Bạn sẽ học được gì?</h2>
                    <div class="cd-learn-grid">
                        @foreach($khoaHoc->loiIch as $item)
                            <div class="cd-learn-item">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                <span>{{ $item->noi_dung }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @elseif($khoaHoc->mo_ta)
                <div class="mb-5">
                    <h2 class="cd-section-title">Bạn sẽ học được gì?</h2>
                    <div class="cd-about-text">
                        {!! nl2br(e($khoaHoc->mo_ta)) !!}
                    </div>
                </div>
            @endif

            {{-- Nội dung khóa học (accordion) --}}
            @if($khoaHoc->chuongHocs->isNotEmpty())
                <div class="mb-5">
                    <h2 class="cd-section-title">Nội dung khóa học</h2>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span style="color:var(--text-muted); font-size:0.95rem;">
                            {{ $khoaHoc->chuongHocs->count() }} Chương •
                            {{ $tongBaiHoc }} Bài giảng
                            @if($tongThoiLuongGiay > 0)
                                • Thời lượng
                                @php
                                    $h = intdiv($tongThoiLuongGiay, 3600);
                                    $m = intdiv($tongThoiLuongGiay % 3600, 60);
                                @endphp
                                {{ $h > 0 ? $h . ' giờ ' : '' }}{{ $m }} phút
                            @endif
                        </span>
                    </div>

                    <div class="accordion cd-accordion" id="curriculumAccordion">
                        @foreach($khoaHoc->chuongHocs as $cIndex => $chuong)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading-{{ $chuong->id }}">
                                    <button class="accordion-button {{ $cIndex > 0 ? 'collapsed' : '' }}"
                                            type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#collapse-{{ $chuong->id }}"
                                            aria-expanded="{{ $cIndex === 0 ? 'true' : 'false' }}"
                                            aria-controls="collapse-{{ $chuong->id }}">
                                        <span class="flex-grow-1 text-start">{{ $chuong->ten_chuong }}</span>
                                        <span class="small fw-normal me-3" style="color:var(--text-muted); width: 60px; text-align: right;">
                                            {{ $chuong->baiHocs->count() }} bài
                                        </span>
                                    </button>
                                </h2>
                                <div id="collapse-{{ $chuong->id }}"
                                     class="accordion-collapse collapse {{ $cIndex === 0 ? 'show' : '' }}"
                                     aria-labelledby="heading-{{ $chuong->id }}">
                                    <div class="accordion-body">
                                        @foreach($chuong->baiHocs as $bai)
                                            <div class="lesson-row">
                                                <div class="lesson-title">
                                                    @if($bai->video)
                                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8"/></svg>
                                                    @else
                                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                                    @endif
                                                    {{ $bai->ten_bai_hoc }}
                                                </div>
                                                <div class="lesson-meta">
                                                    @if($bai->mien_phi)
                                                        <a href="{{ route('baihoc.trial', ['slug' => $bai->slug]) }}" class="lesson-preview-btn">Học thử</a>
                                                    @endif
                                                    @if($bai->thoi_luong_giay)
                                                        <span>
                                                            {{ intdiv($bai->thoi_luong_giay, 60) }}:{{ str_pad($bai->thoi_luong_giay % 60, 2, '0', STR_PAD_LEFT) }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Yêu cầu khóa học --}}
            <div class="mb-5">
                <h2 class="cd-section-title">Yêu cầu khóa học</h2>
                @if($khoaHoc->yeuCau->isNotEmpty())
                    <ul class="cd-about-text">
                        @foreach($khoaHoc->yeuCau as $yc)
                            <li>{{ $yc->noi_dung }}</li>
                        @endforeach
                    </ul>
                @else
                    <ul class="cd-about-text">
                        <li>Không yêu cầu kiến thức nền tảng. Phù hợp với người mới bắt đầu.</li>
                        <li>Có thiết bị kết nối Internet (Máy tính, Máy tính bảng hoặc Điện thoại).</li>
                        <li>Dành ra tối thiểu 30 phút mỗi ngày để luyện tập.</li>
                    </ul>
                @endif
            </div>

            {{-- Mô tả chi tiết (nếu có nội dung dài) --}}
            @if($khoaHoc->mo_ta && strlen(strip_tags($khoaHoc->mo_ta)) > 200)
                <div class="mt-5">
                    <h2 class="cd-section-title">Mô tả chi tiết</h2>
                    <div class="cd-about-text">
                        {!! nl2br(e($khoaHoc->mo_ta)) !!}
                    </div>
                </div>
            @endif

            {{-- Đánh giá & Bình luận --}}
            <div class="mt-5">
                <h2 class="cd-section-title">Đánh giá từ học viên</h2>
                
                @if($khoaHoc->danhGias->isNotEmpty())
                    <div class="row g-3 mb-4">
                        @foreach($khoaHoc->danhGias as $dg)
                            <div class="col-md-6">
                                <div class="cd-review-card h-100 p-3 rounded" style="background: var(--card); border: 1px solid var(--border);">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <div class="cd-review-avatar d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--secondary)); color: #fff;">
                                            {{ $dg->avatarChuCai ?? 'H' }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold" style="font-size:0.9rem; color: var(--text);">
                                                {{ $dg->nguoiDung->ho_ten ?? 'Học viên ẩn danh' }}
                                            </div>
                                            <div style="color:#f59e0b; font-size:0.85rem;">
                                                @for($i = 1; $i <= 5; $i++)
                                                    {{ $i <= $dg->so_sao ? '★' : '☆' }}
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                    @if($dg->noi_dung)
                                        <p class="mb-0 small" style="color:var(--text-muted);">{{ $dg->noi_dung }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-4">Chưa có đánh giá nào. Hãy là người đầu tiên đánh giá khóa học này!</p>
                @endif

                {{-- Form Viết Đánh Giá --}}
                <div class="p-4 rounded" style="background: rgba(var(--primary-rgb, 239, 68, 68), 0.05); border: 1px dashed rgba(var(--primary-rgb, 239, 68, 68), 0.3);">
                    <h3 class="fs-6 fw-bold mb-3">Viết đánh giá của bạn</h3>

                    @if(session('success'))
                        <div class="alert alert-success py-2 mb-3">
                            {{ session('success') }}
                        </div>
                    @endif

                    @auth
                        <form action="{{ route('khoahoc.review', $khoaHoc->slug) }}" method="POST" id="reviewForm">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-medium">Đánh giá sao</label>
                                <div class="rating-input d-flex gap-1" style="color: #f59e0b; font-size: 1.5rem; cursor: pointer;">
                                    <span data-val="1">★</span><span data-val="2">★</span><span data-val="3">★</span><span data-val="4">★</span><span data-val="5">☆</span>
                                    <input type="hidden" name="so_sao" id="so_sao" value="4">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="noi_dung_danh_gia" class="form-label small fw-medium">Nội dung đánh giá</label>
                                <textarea class="form-control" id="noi_dung_danh_gia" name="noi_dung" rows="3" placeholder="Chia sẻ cảm nhận của bạn về khóa học..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-brand btn-sm px-4">Gửi đánh giá</button>
                        </form>
                    @else
                        <div class="text-center py-3">
                            <p class="text-muted small mb-2">Vui lòng đăng nhập để gửi đánh giá khóa học</p>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" class="btn btn-outline-primary btn-sm px-4">Đăng nhập ngay</a>
                        </div>
                    @endauth
                </div>
            </div>

        </div>

        {{-- ---- CỘT PHẢI: FLOATING CARD ---- --}}
        <div class="col-lg-4">
            <div class="cd-floating-card">

                {{-- Ảnh bìa / preview --}}
                <div class="cd-preview-container">
                    @if($khoaHoc->anh_bia)
                        <img src="{{ asset('storage/' . $khoaHoc->anh_bia) }}" alt="{{ $khoaHoc->ten_khoa_hoc }}">
                    @else
                        <img src="https://images.unsplash.com/photo-1543269865-cbf427effbad?q=80&w=800&auto=format&fit=crop" alt="{{ $khoaHoc->ten_khoa_hoc }}">
                    @endif
                    @if($firstFreeVideo)
                    <div class="cd-play-btn" onclick="playPreviewVideo('{{ $firstFreeVideo }}', {{ $firstIsExternal ? 'true' : 'false' }})">
                        <svg width="30" height="30" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                    </div>
                    @endif
                </div>

                {{-- Giá --}}
                @if(!$khoaHoc->gia || $khoaHoc->gia == 0)
                    <div class="cd-price-tag">Miễn phí</div>
                @elseif($khoaHoc->gia_giam && $khoaHoc->gia_giam < $khoaHoc->gia)
                    <div class="cd-price-tag">
                        {{ number_format($khoaHoc->gia_giam, 0, ',', '.') }}₫
                        <small class="text-decoration-line-through ms-2" style="font-size:0.7em; opacity:0.6;">
                            {{ number_format($khoaHoc->gia, 0, ',', '.') }}₫
                        </small>
                    </div>
                @else
                    <div class="cd-price-tag">{{ number_format($khoaHoc->gia, 0, ',', '.') }}₫</div>
                @endif

                {{-- Nút đăng ký --}}
                @auth
                    @if($enrollment && $enrollment->trang_thai == 'Đã duyệt')
                        <a href="{{ route('frontend.dashboard.khoahoc.show', $khoaHoc->slug) }}" class="btn-brand w-100 btn-lg mb-3 d-block text-center" style="border-radius:12px;">
                            Vào học ngay
                        </a>
                    @elseif($enrollment && $enrollment->trang_thai == 'Chờ duyệt')
                        <button type="button" class="btn-brand w-100 btn-lg mb-3 d-block text-center" style="border-radius:12px; border:none; background-color: var(--warning); color: #000;" disabled>
                            Đang chờ duyệt
                        </button>
                    @else
                        <form action="{{ route('khoahoc.register', $khoaHoc->slug) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-brand w-100 btn-lg mb-3 d-block text-center" style="border-radius:12px; border:none;">
                                Đăng ký khóa học
                            </button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn-brand w-100 btn-lg mb-3 d-block text-center" style="border-radius:12px;">
                        Đăng nhập để đăng ký
                    </a>
                @endauth
                <div class="text-center mb-4" style="font-size:0.85rem; color:var(--text-muted);">Đảm bảo hoàn tiền trong 7 ngày</div>

                <hr style="border-color:var(--border);">

                <h4 class="font-head fw-bold fs-6 mt-4 mb-3">Khóa học bao gồm:</h4>
                <ul class="cd-includes">
                    <li>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8"/></svg>
                        {{ $tongBaiHoc }} bài giảng video On-demand
                    </li>
                    <li>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                        Flashcard &amp; Luyện viết chữ Hán
                    </li>
                    <li>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                        Quyền truy cập không giới hạn thời gian
                    </li>
                    <li>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        Chứng chỉ hoàn thành khóa học
                    </li>
                    <li>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
                        Tương thích Điện thoại &amp; Máy tính
                    </li>
                </ul>

            </div>
        </div>

    </div>
</section>

{{-- Video Modal --}}
<div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content" style="background: transparent; border: none;">
      <div class="modal-header" style="border-bottom: none; padding: 0.5rem 1rem;">
        <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal" aria-label="Close" style="background-color: rgba(0,0,0,0.5); border-radius: 50%; opacity: 1; padding: 0.8rem;"></button>
      </div>
      <div class="modal-body p-0">
        <div id="modalVideoWrapper" style="position: relative; width: 100%; padding-top: 56.25%; background: #000; border-radius: 12px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);">
            <!-- Video will be injected here -->
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
function playPreviewVideo(videoRaw, isExternal) {
    const wrapper = document.getElementById('modalVideoWrapper');
    if (!wrapper || !videoRaw) return;
    
    let embedHtml = '';
    if (isExternal) {
        let embedUrl = videoRaw;
        const ytMatch = videoRaw.match(/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/))([a-zA-Z0-9_-]+)/);
        const vimeoMatch = videoRaw.match(/vimeo\.com\/(\d+)/);
        
        if (ytMatch) {
            embedUrl = 'https://www.youtube.com/embed/' + ytMatch[1] + '?autoplay=1';
            embedHtml = `<iframe src="${embedUrl}" style="width:100%;height:100%;border:0;position:absolute;top:0;left:0;" allow="autoplay; encrypted-media; picture-in-picture" allowfullscreen></iframe>`;
        } else if (vimeoMatch) {
            embedUrl = 'https://player.vimeo.com/video/' + vimeoMatch[1] + '?autoplay=1';
            embedHtml = `<iframe src="${embedUrl}" style="width:100%;height:100%;border:0;position:absolute;top:0;left:0;" allow="autoplay; encrypted-media; picture-in-picture" allowfullscreen></iframe>`;
        } else {
            embedHtml = `<video controls autoplay style="width:100%;height:100%;object-fit:cover;position:absolute;top:0;left:0;"><source src="${videoRaw}" type="video/mp4"></video>`;
        }
    } else {
        embedHtml = `<video controls autoplay style="width:100%;height:100%;object-fit:cover;position:absolute;top:0;left:0;"><source src="/storage/${videoRaw}" type="video/mp4"></video>`;
    }
    
    wrapper.innerHTML = embedHtml;
    
    // Show modal
    const videoModal = new bootstrap.Modal(document.getElementById('videoModal'));
    videoModal.show();
}

// Clear video when modal is closed so it stops playing
document.getElementById('videoModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('modalVideoWrapper').innerHTML = '';
});

// Star rating logic for review form
document.querySelectorAll('.rating-input span').forEach(star => {
    star.addEventListener('click', function() {
        const val = parseInt(this.getAttribute('data-val'));
        document.getElementById('so_sao').value = val;
        
        const stars = this.parentElement.querySelectorAll('span');
        stars.forEach((s, index) => {
            if (index < val) {
                s.textContent = '★';
            } else {
                s.textContent = '☆';
            }
        });
    });
});
</script>
@endpush