@extends('admin.layouts.main')

@section('title', 'Thêm khóa học mới — Hányǔ Admin')

@section('content')
      <nav aria-label="breadcrumb" class="mb-3 animate-fade-in delay-1">
        <ol class="breadcrumb small mb-0">
          <li class="breadcrumb-item"><a href="{{ route('admin.khoahoc.index') }}" class="text-decoration-none text-muted">Đào tạo</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.khoahoc.index') }}" class="text-decoration-none text-muted">Khóa học</a></li>
          <li class="breadcrumb-item active" aria-current="page">Thêm khóa học mới</li>
        </ol>
      </nav>

      <div class="page-header animate-fade-in delay-1">
        <div>
          <h1 class="fs-4 fw-bold mb-1">Thêm khóa học mới</h1>
          <p class="text-muted mb-0 small">Thiết lập các thông tin cơ bản cho khóa học mới của bạn.</p>
        </div>
      </div>

      <form action="{{ route('admin.khoahoc.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.khoahoc._form', ['submit_text' => 'Lưu khóa học'])
      </form>
@endsection
