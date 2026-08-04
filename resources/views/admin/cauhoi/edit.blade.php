@extends('admin.layouts.main')

@section('title', 'Cập nhật câu hỏi — Hányǔ Admin')

@section('content')
<nav aria-label="breadcrumb" class="mb-3 animate-fade-in delay-1">
<ol class="breadcrumb small mb-0">
    <li class="breadcrumb-item"><a href="{{ route('admin.khoahoc.index') ?? '#' }}" class="text-decoration-none text-muted">Đào tạo</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.cauhoi.index') ?? '#' }}" class="text-decoration-none text-muted">Ngân hàng đề thi</a></li>
    <li class="breadcrumb-item active" aria-current="page">Cập nhật câu hỏi</li>
</ol>
</nav>

<form action="{{ route('admin.cauhoi.update', $cauHoi->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="page-header animate-fade-in delay-1">
        <div>
            <h1 class="fs-4 fw-bold mb-1">Cập nhật câu hỏi #{{ $cauHoi->id }}</h1>
            <p class="text-muted mb-0 small">Chỉnh sửa nội dung câu hỏi và các đáp án.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.cauhoi.index') ?? '#' }}" class="btn btn-light border px-4">Hủy</a>
            <button type="submit" class="btn btn-primary px-4" style="background: var(--admin-primary); border: none;">Cập nhật câu hỏi</button>
        </div>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show animate-fade-in" role="alert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @include('admin.cauhoi._form', ['cauHoi' => $cauHoi])
</form>
@endsection
