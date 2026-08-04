@extends('frontend.layouts.main')

@section('title', ($loTrinh->ten_lo_trinh ?? 'Lộ trình học tập') . ' — Hányǔ Bàn')
@section('description', $loTrinh->mo_ta_ngan ?? 'Lộ trình học tiếng Trung cá nhân hóa từ cơ bản đến nâng cao dành cho người Việt.')

@push('styles')
<link href="{{ asset('frontend/asset/css/roadmap.css') }}" rel="stylesheet">
@endpush

@section('content')

  {{-- ================= HERO ================= --}}
  <section class="roadmap-hero">
    <div class="container text-center">
      @if($loTrinh)
        <h1>{{ $loTrinh->ten_lo_trinh }}</h1>
        <p class="hero-sub">{!! nl2br(e($loTrinh->mo_ta ?? $loTrinh->mo_ta_ngan)) !!}</p>
      @else
        <h1>Lộ trình học tập cá nhân hóa</h1>
        <p class="hero-sub">
          Cho dù bạn bắt đầu từ con số 0 hay muốn nâng cao trình độ để du học, làm việc —<br>
          Dưới đây là con đường được <strong>Hányǔ Bàn</strong> thiết kế tối ưu nhất dành cho bạn.
        </p>
      @endif
    </div>
  </section>

  {{-- ================= TIMELINE ================= --}}
  <section class="container pb-5">

    @if($loTrinhs->isEmpty())
      <div class="text-center py-5" style="color:var(--text-muted);">
        <p>Lộ trình đang được cập nhật. Vui lòng quay lại sau!</p>
      </div>
    @else
      @foreach($loTrinhs as $lt)
        <div class="mb-5">
          @if($loTrinhs->count() > 1)
            <h2 class="font-head fw-bold text-center mb-4" style="font-size:1.5rem;">
              {{ $lt->ten_lo_trinh }}
            </h2>
          @endif

          <div class="timeline-container">
            <div class="timeline-line"></div>

            @forelse($lt->giaiDoans as $index => $giaiDoan)
              <div class="timeline-item">
                <div class="timeline-dot">
                  @if($giaiDoan->icon_text)
                    <span class="dot-icon">{{ $giaiDoan->icon_text }}</span>
                  @endif
                </div>
                <div class="timeline-content">
                  <span class="step-badge">Giai đoạn {{ $index + 1 }}</span>
                  <h2 class="step-title">{{ $giaiDoan->ten_giai_doan }}</h2>
                  @if($giaiDoan->mo_ta)
                    <p class="step-desc">{{ $giaiDoan->mo_ta }}</p>
                  @endif

                  @if($giaiDoan->khoaHocs->isNotEmpty())
                    <div class="course-rec-label">Khóa học đề xuất</div>
                    @foreach($giaiDoan->khoaHocs->take(1) as $kh)
                      <a href="{{ route('khoahoc.index') }}" class="course-recommendation">
                        @if($kh->anh_bia)
                          <img src="{{ asset('storage/' . $kh->anh_bia) }}" alt="{{ $kh->ten_khoa_hoc }}">
                        @else
                          <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?q=80&w=800&auto=format&fit=crop" alt="{{ $kh->ten_khoa_hoc }}">
                        @endif
                        <div>
                          <span class="cr-title">{{ $kh->ten_khoa_hoc }}</span>
                          <span class="cr-meta">
                            {{ $kh->tong_bai_hoc ?? 0 }} bài giảng •
                            @if(!$kh->gia || $kh->gia == 0)
                              Miễn phí
                            @elseif($kh->gia_giam && $kh->gia_giam < $kh->gia)
                              {{ number_format($kh->gia_giam, 0, ',', '.') }}₫
                            @else
                              {{ number_format($kh->gia, 0, ',', '.') }}₫
                            @endif
                          </span>
                        </div>
                      </a>
                    @endforeach
                  @endif
                </div>
              </div>
            @empty
              <p class="text-center" style="color:var(--text-muted);">Chưa có giai đoạn nào trong lộ trình này.</p>
            @endforelse

          </div>{{-- /.timeline-container --}}
        </div>
      @endforeach
    @endif

    {{-- CTA --}}
    <div class="text-center mt-5 mb-5">
      <h3 class="font-head fw-bold mb-3">Bạn đã sẵn sàng bước đi đầu tiên chưa?</h3>
      @auth
        <a href="{{ route('khoahoc.index') }}" class="btn-brand btn-lg" style="border-radius:99px;">Vào học ngay</a>
      @else
        <a href="{{ route('register.form') }}" class="btn-brand btn-lg" style="border-radius:99px;">Bắt đầu học ngay miễn phí</a>
      @endauth
    </div>

  </section>

@endsection
