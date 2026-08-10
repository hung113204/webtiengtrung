@extends('frontend.layouts.dashboard')

@section('title', 'Chi tiết Thông báo - Hányǔ')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <a href="{{ route('frontend.thongbao.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Quay lại
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4 p-md-5">
            <h1 class="h3 mb-3 font-head fw-bold text-dark">{{ $notification->tieu_de }}</h1>
            <div class="d-flex align-items-center text-muted small mb-4 pb-4 border-bottom">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                <span>{{ $notification->created_at->format('d/m/Y H:i') }} ({{ $notification->created_at->diffForHumans() }})</span>
            </div>

            <div class="notification-content fs-5" style="line-height: 1.8; color: var(--text);">
                {!! $notification->noi_dung !!}
            </div>
        </div>
    </div>
</div>

<style>
    .notification-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 1rem 0;
    }
    .notification-content p {
        margin-bottom: 1.2rem;
    }
    .notification-content ul, .notification-content ol {
        margin-bottom: 1.2rem;
        padding-left: 2rem;
    }
    .notification-content blockquote {
        border-left: 4px solid var(--primary);
        padding-left: 1rem;
        color: var(--text-muted);
        font-style: italic;
        background: var(--bg-subtle);
        padding: 1rem;
        border-radius: 4px;
    }
</style>
@endsection
