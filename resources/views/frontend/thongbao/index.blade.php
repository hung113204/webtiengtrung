@extends('frontend.layouts.dashboard')

@section('title', 'Thông báo - Hányǔ')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800 font-head fw-bold">Danh sách Thông báo</h1>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-0">
            @if($notifications->count() > 0)
                <div class="list-group list-group-flush rounded-4">
                    @foreach($notifications as $tb)
                        <a href="{{ route('frontend.thongbao.show', $tb->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-start p-4 {{ !$tb->pivot->da_doc ? 'bg-light' : '' }}">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold fs-5 mb-1 {{ !$tb->pivot->da_doc ? 'text-primary' : 'text-dark' }}">{{ $tb->tieu_de }}</div>
                                <div class="text-muted small">
                                    {!! Str::limit(strip_tags($tb->noi_dung), 100) !!}
                                </div>
                            </div>
                            <div class="d-flex flex-column align-items-end">
                                <span class="badge bg-secondary rounded-pill mb-2">{{ $tb->created_at->diffForHumans() }}</span>
                                @if(!$tb->pivot->da_doc)
                                    <span class="badge bg-danger rounded-pill">Mới</span>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-muted mb-3"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                    <h5 class="text-muted">Bạn chưa có thông báo nào</h5>
                </div>
            @endif
        </div>
    </div>
    
    <div class="mt-4">
        {{ $notifications->links() }}
    </div>
</div>
@endsection
