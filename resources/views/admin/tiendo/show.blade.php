@extends('admin.layouts.main')

@section('title', 'Chi tiết Tiến độ Học tập — Hányǔ Admin')

@section('content')
<div class="page-header animate-fade-in delay-1 mb-4 d-flex justify-content-between align-items-center">
  <div class="d-flex align-items-center gap-3">
    <a href="{{ route('admin.tiendo.index') }}" class="btn btn-light btn-sm text-muted">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
    </a>
    <div>
      <h1 class="fs-4 fw-bold mb-1" style="letter-spacing: -0.01em;">Tiến độ của: {{ $dangKy->nguoiDung->ho_ten }}</h1>
      <p class="text-muted mb-0 small">Khóa học: {{ $khoaHoc->ten_khoa_hoc }}</p>
    </div>
  </div>
</div>

<div class="row mb-4 animate-fade-in delay-2">
  <div class="col-md-12">
    <div class="card border-0 shadow-sm" style="background: var(--admin-card); border-radius: 16px;">
      <div class="card-body p-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <div>
            <h5 class="fw-bold mb-1">Tổng quan tiến độ</h5>
            <div class="text-muted small">Hoàn thành {{ $completedLessons }}/{{ $totalLessons }} bài học</div>
          </div>
          <div class="fs-4 fw-bold text-primary">{{ $progressPercent }}%</div>
        </div>
        <div class="progress" style="height: 10px; border-radius: 5px; background: rgba(0,0,0,0.05);">
          <div class="progress-bar {{ $progressPercent == 100 ? 'bg-success' : ($progressPercent >= 50 ? 'bg-primary' : 'bg-warning') }}" role="progressbar" style="width: {{ $progressPercent }}%;" aria-valuenow="{{ $progressPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row animate-fade-in delay-3">
  <div class="col-12">
    <div class="card border-0 shadow-sm" style="background: var(--admin-card); border-radius: 16px;">
      <div class="card-header border-0 bg-transparent p-4 pb-2">
        <h5 class="fw-bold mb-0">Chi tiết lộ trình học</h5>
      </div>
      <div class="card-body p-4 pt-2">
        <div class="accordion" id="courseAccordion">
          @foreach($khoaHoc->chuongHocs as $index => $chuong)
            <div class="accordion-item border-0 mb-3 rounded-3 shadow-sm" style="overflow: hidden;">
              <h2 class="accordion-header" id="heading{{ $chuong->id }}">
                <button class="accordion-button {{ $index !== 0 ? 'collapsed' : '' }} bg-light fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $chuong->id }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $chuong->id }}">
                  Chương {{ $chuong->thu_tu }}: {{ $chuong->ten_chuong }}
                </button>
              </h2>
              <div id="collapse{{ $chuong->id }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" aria-labelledby="heading{{ $chuong->id }}" data-bs-parent="#courseAccordion">
                <div class="accordion-body p-0">
                  <ul class="list-group list-group-flush">
                    @forelse($chuong->baiHocs as $baiHoc)
                      @php
                        $tienDo = $tienDos->get($baiHoc->id);
                        $daHoanThanh = $tienDo && $tienDo->da_hoan_thanh;
                        $phanTram = $tienDo ? $tienDo->phan_tram_hoan_thanh : 0;
                      @endphp
                      <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                        <div class="d-flex align-items-center gap-3">
                          <div style="width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; {{ $daHoanThanh ? 'background: rgba(16, 185, 129, 0.1); color: #10b981;' : 'background: rgba(0,0,0,0.05); color: #9ca3af;' }}">
                            @if($daHoanThanh)
                              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            @else
                              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            @endif
                          </div>
                          <div>
                            <div class="fw-semibold text-dark">{{ $baiHoc->ten_bai_hoc }}</div>
                            <div class="small text-muted">
                              @if($baiHoc->loai_dieu_kien === 'tu_dong')
                                Điều kiện: Tự động
                              @elseif($baiHoc->loai_dieu_kien === 'xem_video')
                                Điều kiện: Xem {{ $baiHoc->phan_tram_video }}% Video
                              @else
                                Điều kiện: Bài kiểm tra
                              @endif
                            </div>
                          </div>
                        </div>
                        <div class="text-end">
                          @if($daHoanThanh)
                            <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle px-2 py-1 rounded-2 mb-1" style="font-weight: 500;">Hoàn thành</span>
                          @else
                            @if($phanTram > 0)
                              <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle px-2 py-1 rounded-2 mb-1" style="font-weight: 500;">Đang học ({{ $phanTram }}%)</span>
                            @else
                              <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle px-2 py-1 rounded-2 mb-1" style="font-weight: 500;">Chưa học</span>
                            @endif
                          @endif
                          
                          @if($tienDo && $tienDo->lan_hoc_cuoi)
                            <div class="small text-muted" style="font-size: 0.75rem;">
                              Lần cuối: {{ $tienDo->lan_hoc_cuoi->diffForHumans() }}
                            </div>
                          @endif
                        </div>
                      </li>
                    @empty
                      <li class="list-group-item p-3 text-muted text-center">Chương này chưa có bài học nào.</li>
                    @endforelse
                  </ul>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
