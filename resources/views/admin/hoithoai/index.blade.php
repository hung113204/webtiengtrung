@extends('admin.layouts.main')

@section('title', 'Quản lý Hội Thoại - Hanyu Admin')

@section('content')
<div class="page-header animate-fade-in delay-1">
  <div>
    <h1 class="fs-4 fw-bold mb-1">Quản lý Hội Thoại</h1>
    <p class="text-muted mb-0 small">Thêm và chỉnh sửa các đoạn hội thoại cho bài học.</p>
  </div>
  <div class="d-flex gap-2">
    <a href="{{ route('admin.hoithoai.create') }}" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm" style="background: var(--admin-primary); border: none;">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
      Thêm Hội Thoại
    </a>
  </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show animate-fade-in" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card bg-white border-0 shadow-sm rounded-3 animate-fade-in delay-2 mb-4">
  <div class="card-header bg-white border-bottom-0 pt-4 pb-2 d-flex flex-wrap gap-3">
    <form action="{{ route('admin.hoithoai.index') }}" method="GET" class="d-flex gap-3 flex-grow-1">
        <div class="input-group" style="max-width: 300px;">
            <span class="input-group-text bg-white border-end-0 text-muted">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </span>
            <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Tìm tiêu đề / nội dung..." value="{{ request('search') }}">
        </div>

        <select name="id_bai_hoc" class="form-select w-auto">
            <option value="">-- Tất cả bài học --</option>
            @foreach($baiHocs as $bh)
                <option value="{{ $bh->id }}" {{ request('id_bai_hoc') == $bh->id ? 'selected' : '' }}>{{ $bh->ten_bai_hoc }}</option>
            @endforeach
        </select>
        
        <button type="submit" class="btn btn-light px-4">Lọc</button>
        @if(request('search') || request('id_bai_hoc'))
            <a href="{{ route('admin.hoithoai.index') }}" class="btn btn-link text-danger text-decoration-none">Xóa lọc</a>
        @endif
    </form>
  </div>

  <div class="table-responsive p-3">
    <table class="table table-hover mb-0 align-middle">
      <thead class="table-light text-muted small">
        <tr>
          <th width="20%">Bài học</th>
          <th width="20%">Tiêu đề</th>
          <th width="30%">Mô tả</th>
          <th width="15%">Số câu thoại</th>
          <th width="15%" class="text-end">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        @forelse($hoiThoais as $item)
        <tr>
            <td class="fw-medium text-dark">{{ $item->baiHoc->ten_bai_hoc ?? 'N/A' }}</td>
            <td>{{ $item->tieu_de ?: 'Không có' }}</td>
            <td>
                <div class="text-truncate" style="max-width: 250px;">
                    {{ $item->mo_ta ?: '...' }}
                </div>
            </td>
            <td>
                <span class="badge bg-info text-dark rounded-pill px-3">{{ $item->chiTietHoiThoais->count() }} câu</span>
            </td>
            <td class="text-end pe-4">
                <div class="d-flex justify-content-end align-items-center gap-1">
                    <a href="{{ route('admin.hoithoai.show', $item->id) }}" class="icon-btn text-primary" title="Chi tiết câu thoại">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </a>
                    <a href="{{ route('admin.hoithoai.edit', $item->id) }}" class="icon-btn" title="Chỉnh sửa">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </a>
                    <form action="{{ route('admin.hoithoai.destroy', $item->id) }}" method="POST" class="m-0 p-0 d-flex" onsubmit="return confirm('Bạn có chắc chắn muốn xóa hội thoại này và toàn bộ các câu thoại bên trong?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="icon-btn text-danger" title="Xóa">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path></svg>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center py-5 text-muted">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="mb-3 opacity-50"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                <br>Chưa có dữ liệu hội thoại nào.
            </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  
  @if($hoiThoais->hasPages())
  <div class="card-footer bg-white border-top-0 pb-3">
    {{ $hoiThoais->links('pagination::bootstrap-5') }}
  </div>
  @endif
</div>
@endsection
