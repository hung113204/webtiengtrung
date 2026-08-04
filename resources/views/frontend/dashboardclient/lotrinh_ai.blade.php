@extends('frontend.layouts.dashboard')

@section('title', 'Lộ trình AI của tôi — Hányǔ Bàn')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <h1 class="font-head fw-bold fs-3 mb-0">Lộ trình AI cá nhân hóa</h1>
            <button type="button" id="btn-generate-ai" class="btn btn-outline-primary rounded-pill d-flex align-items-center gap-2">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 12a9 9 0 0 1-9 9m9-9a9 9 0 0 0-9-9m9 9H3m9 9a9 9 0 0 1-9-9m9 9c1.66 0 3-4.03 3-9s-1.34-9-3-9m0 18c-1.66 0-3-4.03-3-9s1.34-9 3-9m-9 9a9 9 0 0 1 9-9"></path>
                </svg>
                <span id="btn-text">{{ empty($hoSo->lo_trinh_ai) ? 'Tạo lộ trình ngay' : 'Tạo lại lộ trình' }}</span>
            </button>
        </div>
        <p class="text-muted">
            Trình độ: <strong>{{ $hoSo->trinh_do_hien_tai ?? 'Chưa cập nhật' }}</strong> — 
            Mục tiêu: <strong>{{ $hoSo->muc_tieu_hoc_tap ?? 'Chưa cập nhật' }}</strong>
        </p>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="brand-card p-4">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div class="d-flex align-items-center justify-content-center bg-soft-primary text-primary rounded-3 flex-shrink-0 shadow-sm" style="width: 48px; height: 48px; background: rgba(220, 38, 38, 0.1); color: #dc2626;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"></path>
                    </svg>
                </div>
                <h2 class="font-head fs-4 fw-bold mb-0 text-dark">Lộ trình học tập đề xuất</h2>
            </div>
            
            <p class="small text-muted mb-4">Lộ trình được sắp xếp từ cơ bản đến nâng cao dựa trên phân tích của AI.</p>

            <div id="ai-loading" class="text-center py-5 d-none">
                <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h5 class="fw-bold">AI đang phân tích...</h5>
                <p class="text-muted small">Vui lòng chờ giây lát, hệ thống đang sắp xếp các khóa học phù hợp nhất với bạn.</p>
            </div>

            <div id="ai-timeline-container">
                @if(!empty($hoSo->lo_trinh_ai))
                    @include('frontend.dashboardclient.partials.lotrinh_ai_timeline')
                @else
                    <div class="text-center py-5 bg-light rounded-4 border border-dashed">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-muted mb-3">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                        </svg>
                        <h5 class="fw-bold text-dark mb-2">Chưa có lộ trình</h5>
                        <p class="text-muted small mb-3">Bạn chưa yêu cầu AI tạo lộ trình cá nhân hóa.</p>
                        <button class="btn btn-primary rounded-pill px-4" onclick="document.getElementById('btn-generate-ai').click()">Tạo lộ trình ngay</button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4 mt-4 mt-lg-0">
        <div class="brand-card p-4">
            <h3 class="font-head fs-6 fw-bold mb-3">Tại sao nên học theo lộ trình?</h3>
            <ul class="list-unstyled mb-0 small text-muted">
                <li class="mb-3 d-flex gap-2">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--success)" stroke-width="3" class="mt-1 flex-shrink-0"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    <span>Tiết kiệm thời gian mò mẫm, biết chính xác cần học gì tiếp theo.</span>
                </li>
                <li class="mb-3 d-flex gap-2">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--success)" stroke-width="3" class="mt-1 flex-shrink-0"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    <span>Kiến thức được xây dựng liên kết với nhau, dễ tiếp thu.</span>
                </li>
                <li class="mb-3 d-flex gap-2">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--success)" stroke-width="3" class="mt-1 flex-shrink-0"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    <span>Tối ưu hóa khả năng đạt được mục tiêu cá nhân.</span>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnGenerate = document.getElementById('btn-generate-ai');
    const container = document.getElementById('ai-timeline-container');
    const loading = document.getElementById('ai-loading');
    const btnText = document.getElementById('btn-text');
    
    // Khởi tạo Toast
    const toastEl = document.getElementById('ajaxToast');
    const toastBody = document.getElementById('ajaxToastBody');
    let toast = null;
    if(toastEl) {
        toast = new bootstrap.Toast(toastEl, { delay: 3000 });
    }

    btnGenerate.addEventListener('click', function(e) {
        if(!e.isTrusted && !window.autoGenerating) {
           // allow script to click
        } else if (e.isTrusted) {
           if(!confirm('Bạn có muốn AI tạo lộ trình mới dựa trên mục tiêu của bạn không?')) return;
        }
        
        window.autoGenerating = true;
        
        btnGenerate.disabled = true;
        btnText.innerText = 'Đang tạo...';
        container.classList.add('d-none');
        loading.classList.remove('d-none');
        
        fetch('{{ route('frontend.dashboard.lotrinh_ai.generate') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            btnGenerate.disabled = false;
            btnText.innerText = 'Tạo lại lộ trình';
            loading.classList.add('d-none');
            container.classList.remove('d-none');
            
            if(data.status === 'success') {
                container.innerHTML = data.html;
                if(toast) {
                    toastEl.classList.remove('text-bg-danger');
                    toastEl.classList.add('text-bg-success');
                    toastBody.innerText = data.message;
                    toast.show();
                }
            } else {
                if(toast) {
                    toastEl.classList.remove('text-bg-success');
                    toastEl.classList.add('text-bg-danger');
                    toastBody.innerText = data.message;
                    toast.show();
                } else {
                    alert(data.message);
                }
            }
        })
        .catch(error => {
            btnGenerate.disabled = false;
            btnText.innerText = 'Tạo lại lộ trình';
            loading.classList.add('d-none');
            container.classList.remove('d-none');
            console.error('Error:', error);
            
            if(toast) {
                toastEl.classList.remove('text-bg-success');
                toastEl.classList.add('text-bg-danger');
                toastBody.innerText = 'Có lỗi mạng xảy ra. Vui lòng thử lại sau.';
                toast.show();
            } else {
                alert('Có lỗi mạng xảy ra. Vui lòng thử lại sau.');
            }
        });
    });

    @if(session('auto_generate'))
        // Auto trigger the generation when redirected from Onboarding
        setTimeout(() => {
            window.autoGenerating = true;
            btnGenerate.click();
        }, 500);
    @endif
});
</script>
@endpush
