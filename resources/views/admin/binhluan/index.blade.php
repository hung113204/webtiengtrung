@extends('admin.layouts.main')

@section('title', 'Quản lý Bình luận — Hányǔ Admin')

@section('content')
<div class="page-header animate-fade-in delay-1 mb-4 d-flex justify-content-between align-items-center">
  <div>
    <h1 class="fs-4 fw-bold mb-1" style="letter-spacing: -0.01em;">Quản lý Bình luận</h1>
    <p class="text-muted mb-0 small">Kiểm duyệt và trả lời các phản hồi của học viên trên các bài học.</p>
  </div>
</div>

<!-- Alert notifications -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show animate-fade-in mb-4" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Stats Row -->
<div class="row mb-4 animate-fade-in delay-2">
  <div class="col-md-4 mb-3 mb-md-0">
    <div class="card border-0 shadow-sm h-100" style="background: var(--admin-card); border-radius: 16px;">
      <div class="card-body d-flex align-items-center gap-3 p-4">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(59, 130, 246, 0.1); color: #3b82f6; display: flex; align-items: center; justify-content: center;">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
        </div>
        <div>
          <div class="text-muted small fw-medium">Tổng số bình luận</div>
          <div class="fs-4 fw-bold text-dark">{{ number_format($totalComments) }}</div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4 mb-3 mb-md-0">
    <div class="card border-0 shadow-sm h-100" style="background: var(--admin-card); border-radius: 16px;">
      <div class="card-body d-flex align-items-center gap-3 p-4">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(16, 185, 129, 0.1); color: #10b981; display: flex; align-items: center; justify-content: center;">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
        </div>
        <div>
          <div class="text-muted small fw-medium">Hôm nay</div>
          <div class="fs-4 fw-bold text-dark">{{ number_format($todayComments) }}</div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card border-0 shadow-sm h-100" style="background: var(--admin-card); border-radius: 16px;">
      <div class="card-body d-flex align-items-center gap-3 p-4">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(239, 68, 68, 0.1); color: var(--admin-primary); display: flex; align-items: center; justify-content: center;">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
        </div>
        <div>
          <div class="text-muted small fw-medium">Số lượt phản hồi</div>
          <div class="fs-4 fw-bold text-dark">{{ number_format($repliedComments) }}</div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Filters and Comments List -->
<div class="card border-0 shadow-sm animate-fade-in delay-3 mb-5" style="background: var(--admin-card); border-radius: 16px;">
  <div class="card-header border-0 bg-transparent p-4 pb-0">
    <form method="GET" action="{{ route('admin.binhluan.index') }}" class="row g-3">
      <div class="col-md-4">
        <div class="input-group">
          <span class="input-group-text bg-light border-0 text-muted rounded-start-3 px-3">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
          </span>
          <input type="text" name="search" value="{{ request('search') }}" class="form-control bg-light border-0 ps-0 rounded-end-3" placeholder="Tìm kiếm nội dung, học viên...">
        </div>
      </div>
      
      <div class="col-md-8 d-flex justify-content-md-end gap-2 flex-wrap">
        <select name="id_bai_hoc" class="form-select bg-light border-0 rounded-3" style="width: auto; min-width: 200px; box-shadow: none;" onchange="this.form.submit()">
          <option value="">Tất cả bài học</option>
          @foreach($baiHocs as $bh)
            <option value="{{ $bh->id }}" {{ request('id_bai_hoc') == $bh->id ? 'selected' : '' }}>{{ $bh->ten_bai_hoc }}</option>
          @endforeach
        </select>

        <select name="trang_thai" class="form-select bg-light border-0 rounded-3" style="width: auto; min-width: 150px; box-shadow: none;" onchange="this.form.submit()">
          <option value="">Tất cả trạng thái</option>
          <option value="1" {{ request('trang_thai') === '1' ? 'selected' : '' }}>Đang hiển thị</option>
          <option value="0" {{ request('trang_thai') === '0' ? 'selected' : '' }}>Đang ẩn</option>
        </select>
        
        @if(request('search') || request('id_bai_hoc') || request('trang_thai') !== null)
          <a href="{{ route('admin.binhluan.index') }}" class="btn btn-outline-secondary rounded-3 d-flex align-items-center gap-2">
            Xóa lọc
          </a>
        @endif
      </div>
    </form>
  </div>

  <div class="card-body p-4">
    <div class="comments-stream">
      @forelse($binhLuans as $bl)
        <div class="comment-item-wrapper mb-4 p-3 rounded-4 bg-light bg-opacity-50 border border-light-subtle">
          <!-- Parent Comment -->
          <div class="d-flex justify-content-between align-items-start mb-2">
            <div class="d-flex align-items-center gap-3">
              <img src="https://ui-avatars.com/api/?name={{ urlencode($bl->nguoiDung->ho_ten ?? 'Học viên') }}&background=random" class="rounded-circle" width="40" height="40" alt="Avatar">
              <div>
                <div class="d-flex align-items-center gap-2">
                  <span class="fw-semibold text-dark small">{{ $bl->nguoiDung->ho_ten ?? 'N/A' }}</span>
                  @if($bl->nguoiDung && $bl->nguoiDung->isAdmin())
                    <span class="badge bg-danger rounded-pill" style="font-size: 0.65rem;">Quản trị viên</span>
                  @endif
                  <span class="text-muted small" style="font-size: 0.75rem;">• {{ $bl->created_at->diffForHumans() }}</span>
                </div>
                <div class="text-muted" style="font-size: 0.75rem;">
                  {{ $bl->nguoiDung->email ?? '' }}
                </div>
              </div>
            </div>
            
            <div class="d-flex gap-1">
              <!-- Toggle Visibility -->
              <form action="{{ route('admin.binhluan.update', $bl->id) }}" method="POST" class="d-inline">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-icon btn-light rounded-circle border-0 text-muted" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; padding: 0;" title="{{ $bl->trang_thai ? 'Ẩn bình luận' : 'Hiển thị bình luận' }}">
                  @if($bl->trang_thai)
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                  @else
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                  @endif
                </button>
              </form>

              <!-- Delete -->
              <form action="{{ route('admin.binhluan.destroy', $bl->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bình luận này và toàn bộ phản hồi liên quan?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-icon btn-light rounded-circle border-0 text-danger" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; padding: 0;" title="Xóa bình luận">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                </button>
              </form>
            </div>
          </div>

          <div class="mb-3">
            <div class="badge bg-light text-secondary border border-light-subtle rounded-2 mb-2" style="font-weight: 500; font-size: 0.7rem; padding: 0.35rem 0.5rem;">
              Bài học: {{ $bl->baiHoc->ten_bai_hoc ?? 'N/A' }} 
              @if($bl->baiHoc && $bl->baiHoc->chuongHoc && $bl->baiHoc->chuongHoc->khoaHoc)
                ({{ $bl->baiHoc->chuongHoc->khoaHoc->ten_khoa_hoc }})
              @endif
            </div>
            
            <p class="text-dark mb-0 small @if(!$bl->trang_thai) text-decoration-line-through text-muted @endif" style="line-height: 1.5; white-space: pre-line;">{{ $bl->noi_dung }}</p>
          </div>

          <!-- Nested Replies -->
          @if($bl->replies->count() > 0)
            <div class="replies-container ms-5 border-start border-light-subtle ps-4 mt-3">
              @foreach($bl->replies as $reply)
                <div class="reply-item mb-3 bg-white p-3 rounded-4 border border-light-subtle animate-fade-in">
                  <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="d-flex align-items-center gap-2">
                      <img src="https://ui-avatars.com/api/?name={{ urlencode($reply->nguoiDung->ho_ten ?? 'Admin') }}&background=random" class="rounded-circle" width="28" height="28" alt="Avatar">
                      <div>
                        <div class="d-flex align-items-center gap-2">
                          <span class="fw-semibold text-dark small" style="font-size: 0.8rem;">{{ $reply->nguoiDung->ho_ten ?? 'Quản trị viên' }}</span>
                          @if($reply->nguoiDung && $reply->nguoiDung->isAdmin())
                            <span class="badge bg-danger rounded-pill" style="font-size: 0.6rem; padding: 0.2rem 0.4rem;">QTV</span>
                          @endif
                          <span class="text-muted" style="font-size: 0.7rem;">{{ $reply->created_at->diffForHumans() }}</span>
                        </div>
                      </div>
                    </div>
                    
                    <form action="{{ route('admin.binhluan.destroy', $reply->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa phản hồi này?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-link text-danger p-0 border-0" style="font-size: 0.75rem; text-decoration: none;" title="Xóa phản hồi">Xóa</button>
                    </form>
                  </div>
                  <p class="text-dark mb-0 small" style="line-height: 1.4; white-space: pre-line;">{{ $reply->noi_dung }}</p>
                </div>
              @endforeach
            </div>
          @endif

          <!-- Reply Box Button -->
          <div class="ms-5 ps-4 mt-2">
            <button class="btn btn-link btn-sm text-primary p-0 d-flex align-items-center gap-1" style="font-size: 0.8rem; text-decoration: none;" type="button" data-bs-toggle="collapse" data-bs-target="#replyForm-{{ $bl->id }}" aria-expanded="false">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 17 4 12 9 7"></polyline><path d="M20 18v-2a4 4 0 0 0-4-4H4"></path></svg>
              Trả lời bình luận này
            </button>
            
            <div class="collapse mt-2" id="replyForm-{{ $bl->id }}">
              <form action="{{ route('admin.binhluan.reply', $bl->id) }}" method="POST" class="card border-0 shadow-sm bg-white p-3 rounded-4">
                @csrf
                <div class="mb-3">
                  <textarea name="noi_dung" class="form-control border-light-subtle rounded-3 small" rows="3" placeholder="Nhập nội dung phản hồi của bạn..." required style="font-size: 0.8rem; box-shadow: none;"></textarea>
                </div>
                <div class="d-flex justify-content-end gap-2">
                  <button type="button" class="btn btn-light btn-sm rounded-3" data-bs-toggle="collapse" data-bs-target="#replyForm-{{ $bl->id }}" style="font-size: 0.75rem;">Hủy</button>
                  <button type="submit" class="btn btn-primary btn-sm rounded-3" style="background: var(--admin-primary); border: none; font-size: 0.75rem;">Gửi phản hồi</button>
                </div>
              </form>
            </div>
          </div>

        </div>
      @empty
        <div class="text-center py-5 text-muted">
          <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mb-2 opacity-50"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
          <p class="mb-0">Không có bình luận nào.</p>
        </div>
      @endforelse
    </div>

    @if($binhLuans->hasPages())
      <div class="mt-4">
        {{ $binhLuans->links() }}
      </div>
    @endif
  </div>
</div>
@endsection
