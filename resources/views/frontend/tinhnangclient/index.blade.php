@extends('frontend.layouts.main')

@section('title', 'Tính năng nổi bật - Hányǔ Bàn')

@push('styles')
<link href="{{ asset('frontend/asset/css/features.css') }}" rel="stylesheet" />
@endpush

@section('content')
  <!-- HERO SECTION -->
  <section class="features-hero" style="margin-top: 80px;">
    <div class="container">
      <h1>Mọi kỹ năng, một nền tảng duy nhất</h1>
      <p>Hányǔ Bàn cung cấp bộ công cụ toàn diện giúp bạn học từ vựng, luyện nghe nói và chuẩn bị cho kỳ thi HSK mà không cần phải cài đặt nhiều ứng dụng khác nhau.</p>
    </div>
  </section>

  @foreach($tinhNangs as $item)
  <section class="feature-block">
    <div class="container">
      <div class="row align-items-center g-5">
        @if($item->vi_tri_anh == 'right')
        <div class="col-lg-6 order-2 order-lg-1">
          <div class="feature-text">
            @if($item->badge_text)
            <span class="feature-badge">{{ $item->badge_text }}</span>
            @endif
            <h2>{{ $item->tieu_de }}</h2>
            @if($item->mo_ta)
            <p>{{ $item->mo_ta }}</p>
            @endif
            
            @if($item->danh_sach_bullet && is_array($item->danh_sach_bullet))
            <ul class="feature-list">
              @foreach($item->danh_sach_bullet as $bullet)
              <li><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg> {{ $bullet }}</li>
              @endforeach
            </ul>
            @endif
            
            @if($item->button_text)
            <a href="{{ $item->button_link ?? '#' }}" class="btn-brand">{{ $item->button_text }}</a>
            @endif
          </div>
        </div>
        <div class="col-lg-6 order-1 order-lg-2">
          <div class="feature-image-wrapper">
            @if($item->image_url)
            <img src="{{ $item->image_url }}" alt="{{ $item->tieu_de }}">
            @endif
            
            @if($item->stat_number || $item->stat_label)
            <div class="floating-stat">
              @if($item->stat_icon)
              <div class="icon">{!! $item->stat_icon !!}</div>
              @endif
              <div class="data">
                <strong>{{ $item->stat_number }}</strong>
                <span>{{ $item->stat_label }}</span>
              </div>
            </div>
            @endif
          </div>
        </div>
        @else
        <!-- Vị trí ảnh bên trái -->
        <div class="col-lg-6">
          <div class="feature-image-wrapper">
            @if($item->image_url)
            <img src="{{ $item->image_url }}" alt="{{ $item->tieu_de }}">
            @endif
            
            @if($item->stat_number || $item->stat_label)
            <div class="floating-stat" style="left: -20px; right: auto;">
              @if($item->stat_icon)
              <div class="icon">{!! $item->stat_icon !!}</div>
              @endif
              <div class="data">
                <strong>{{ $item->stat_number }}</strong>
                <span>{{ $item->stat_label }}</span>
              </div>
            </div>
            @endif
          </div>
        </div>
        <div class="col-lg-6">
          <div class="feature-text">
            @if($item->badge_text)
            <span class="feature-badge">{{ $item->badge_text }}</span>
            @endif
            <h2>{{ $item->tieu_de }}</h2>
            @if($item->mo_ta)
            <p>{{ $item->mo_ta }}</p>
            @endif
            
            @if($item->danh_sach_bullet && is_array($item->danh_sach_bullet))
            <ul class="feature-list">
              @foreach($item->danh_sach_bullet as $bullet)
              <li><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg> {{ $bullet }}</li>
              @endforeach
            </ul>
            @endif

            @if($item->button_text)
            <a href="{{ $item->button_link ?? '#' }}" class="btn-brand">{{ $item->button_text }}</a>
            @endif
          </div>
        </div>
        @endif
      </div>
    </div>
  </section>
  @endforeach
  
  <section class="section-pad" style="background:color-mix(in srgb, var(--primary) 3%, var(--bg));">
    <div class="container text-center">
      <h2 class="font-head fw-bold mb-4">Trải nghiệm mọi tính năng cao cấp ngay hôm nay</h2>
      <a href="{{ route('register.form') }}" class="btn-brand btn-lg" style="border-radius: 99px;">Đăng ký tài khoản miễn phí</a>
    </div>
  </section>
@endsection
