@extends('admin.layouts.main')

@section('title', 'Chỉnh sửa khóa học — Hányǔ Admin')

@section('content')
      <nav aria-label="breadcrumb" class="mb-3 animate-fade-in delay-1">
        <ol class="breadcrumb small mb-0">
          <li class="breadcrumb-item"><a href="{{ route('admin.khoahoc.index') }}" class="text-decoration-none text-muted">Đào tạo</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.khoahoc.index') }}" class="text-decoration-none text-muted">Khóa học</a></li>
          <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa</li>
        </ol>
      </nav>

      <div class="page-header animate-fade-in delay-1">
        <div>
          <h1 class="fs-4 fw-bold mb-1">Chỉnh sửa khóa học: <span class="text-primary">{{ $khoahoc->ten_khoa_hoc }}</span></h1>
          <p class="text-muted mb-0 small">Cập nhật thông tin chi tiết hoặc trạng thái xuất bản.</p>
        </div>
      </div>

      <form action="{{ route('admin.khoahoc.update', $khoahoc->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.khoahoc._form', ['submit_text' => 'Cập nhật khóa học'])
      </form>
@endsection
