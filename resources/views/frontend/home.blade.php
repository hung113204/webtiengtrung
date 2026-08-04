@extends('frontend.layouts.main')

@section('content')
  @include('frontend.parts.home.hero')
  @include('frontend.parts.home.trust_strip')
  @include('frontend.parts.home.benefits')
  @include('frontend.parts.home.courses')
  @include('frontend.parts.home.stats')
  @include('frontend.parts.home.roadmap')
  @include('frontend.parts.home.testimonials')
  @include('frontend.parts.home.trial')
  @include('frontend.parts.home.faq')
  @include('frontend.parts.home.cta')
@endsection

@push('scripts')
<script>
  document.addEventListener("DOMContentLoaded", function() {
    function initWriter(id, char, delay) {
      if(!document.getElementById(id)) return;
      var writer = HanziWriter.create(id, char, {
        width: 64,
        height: 64,
        padding: 6,
        strokeColor: '#dc2626', /* var(--primary-brand) */
        radicalColor: '#f59e0b',
        delayBetweenStrokes: 200,
        strokeAnimationSpeed: 1.5,
        delayBetweenLoops: 3000
      });
      setTimeout(() => {
        writer.loopCharacterAnimation();
      }, delay);
    }
    
    // Khởi tạo hoạt ảnh viết chữ lần lượt
    initWriter('hz-ni', '你', 0);
    initWriter('hz-hao', '好', 1000);
    initWriter('hz-xue', '学', 2000);
    initWriter('hz-zhong', '中', 3000);
  });
</script>
@endpush