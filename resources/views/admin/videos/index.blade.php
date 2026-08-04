@extends('admin.layouts.main')

@section('title', 'Thư viện Video | Admin')

@section('content')
<div class="page-header animate-fade-in delay-1">
  <div>
    <h1 class="fs-4 fw-bold mb-1">Thư viện Video</h1>
    <p class="text-muted mb-0 small">Quản lý và xử lý video HLS độc lập</p>
  </div>
  <button class="btn btn-primary d-flex align-items-center gap-2 shadow-sm" style="background: var(--admin-primary); border: none;" data-bs-toggle="modal" data-bs-target="#uploadVideoModal">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
    Tải lên Video
  </button>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="table-card animate-fade-in delay-2">
  <div class="table-header d-flex flex-wrap gap-3">
    <form action="{{ route('admin.videos.index') }}" method="GET" class="d-flex flex-wrap gap-3 w-100">
        <div class="input-group" style="max-width: 300px;">
          <span class="input-group-text bg-white border-end-0 text-muted">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
          </span>
          <input type="text" class="form-control border-start-0 ps-0" name="search" value="{{ request('search') }}" placeholder="Tìm tên video...">
        </div>
    </form>
  </div>

  <div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead class="table-light text-muted small">
        <tr>
          <th class="fw-medium px-4 py-3" style="width: 120px;">Mã Video</th>
          <th class="fw-medium py-3">Tên Video</th>
          <th class="fw-medium py-3">Kích thước</th>
          <th class="fw-medium py-3">Trạng thái</th>
          <th class="fw-medium pe-4 py-3 text-end">Hành động</th>
        </tr>
      </thead>
      <tbody>
        @forelse($videos as $video)
        <tr data-video-id="{{ $video->id }}">
          <td class="px-4 py-3 fw-bold text-muted small">#{{ $video->hash_id }}</td>
          <td class="py-3">
            <div class="d-flex align-items-center gap-3">
              @if($video->thumbnail_path)
                <img src="{{ asset('storage/' . $video->thumbnail_path) }}" style="width: 80px; height: 45px; object-fit: cover; border-radius: 4px;" alt="Thumbnail">
              @else
                <div style="width: 80px; height: 45px; background: #f1f5f9; border-radius: 4px; display:flex; align-items:center; justify-content:center;">
                  <svg width="20" height="20" class="text-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="2.18" ry="2.18"></rect><line x1="7" y1="2" x2="7" y2="22"></line><line x1="17" y1="2" x2="17" y2="22"></line><line x1="2" y1="12" x2="22" y2="12"></line><line x1="2" y1="7" x2="7" y2="7"></line><line x1="2" y1="17" x2="7" y2="17"></line><line x1="17" y1="17" x2="22" y2="17"></line><line x1="17" y1="7" x2="22" y2="7"></line></svg>
                </div>
              @endif
              <div>
                <div class="fw-bold text-dark fs-6">{{ $video->ten_video }}</div>
                @if($video->thong_bao_loi)
                  <div class="small text-danger mt-1" title="{{ $video->thong_bao_loi }}">{{ Str::limit($video->thong_bao_loi, 60) }}</div>
                @endif
              </div>
            </div>
          </td>
          <td class="py-3 text-muted small">
            {{ $video->kich_thuoc ? number_format($video->kich_thuoc / 1048576, 2) . ' MB' : 'N/A' }}
          </td>
          <td class="py-3 video-status-cell" id="video-status-{{ $video->id }}">
            @if($video->trang_thai === 'hoan_thanh')
                <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle">Hoàn thành</span>
            @elseif($video->trang_thai === 'dang_xu_ly')
                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle">Đang xử lý ({{ $video->phan_tram }}%)</span>
            @elseif($video->trang_thai === 'dang_cho')
                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning-subtle">Đang chờ</span>
            @else
                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger-subtle">Lỗi</span>
            @endif
          </td>
          <td class="text-end pe-4">
            <div class="d-flex justify-content-end align-items-center gap-1">
              @if($video->trang_thai === 'loi')
              <form action="{{ route('admin.videos.retry', $video->id) }}" method="POST" class="m-0 p-0 d-flex">
                  @csrf
                  <button type="submit" class="icon-btn text-warning" title="Thử lại (Retry)">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="1 4 1 10 7 10"></polyline><polyline points="23 20 23 14 17 14"></polyline><path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"></path></svg>
                  </button>
              </form>
              @endif

              @if($video->trang_thai === 'hoan_thanh')
              <button type="button" class="icon-btn text-success btn-preview-video" data-hls="{{ asset('storage/' . $video->hls_path) }}" title="Xem trước HLS">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polygon points="10 8 16 12 10 16 10 8"></polygon></svg>
              </button>
              @endif
              
              <form action="{{ route('admin.videos.generateVocab', $video->id) }}" method="POST" class="m-0 p-0 d-flex" onsubmit="return confirm('Hệ thống sẽ chạy ngầm để trích xuất từ vựng từ video này. Bạn có muốn tiếp tục?');">
                  @csrf
                  <button type="submit" class="icon-btn text-primary" title="Tạo từ vựng tự động bằng AI">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                  </button>
              </form>
              <form action="{{ route('admin.videos.destroy', $video->id) }}" method="POST" class="m-0 p-0 d-flex" onsubmit="return confirm('Bạn có chắc chắn muốn xóa video này?');">
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
            <td colspan="5" class="text-center py-4 text-muted">Chưa có video nào trong thư viện.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($videos->hasPages())
  <div class="card-footer bg-white border-top border-light">
      {{ $videos->links() }}
  </div>
  @endif
</div>

<!-- Modal Upload -->
<div class="modal fade" id="uploadVideoModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow" style="background: var(--admin-card);">
      <div class="modal-header border-bottom border-light">
        <h5 class="modal-title fw-bold">Tải lên Video Mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('admin.videos.store') }}" method="POST" enctype="multipart/form-data" id="uploadVideoForm">
          @csrf
          <div class="mb-3">
            <label class="form-label fw-medium">Tên video (Tùy chọn)</label>
            <input type="text" name="ten_video" class="form-control" placeholder="Để trống sẽ lấy tên file">
          </div>
          <div class="mb-3">
            <label class="form-label fw-medium">Chọn File Video <span class="text-danger">*</span></label>
            <input type="file" name="video_file" class="form-control" accept="video/mp4,video/x-m4v,video/*" required>
            <div class="form-text">Định dạng hỗ trợ: MP4, MOV, AVI (Tối đa 500MB)</div>
          </div>
        </form>
      </div>
      <div class="modal-footer border-top border-light">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy bỏ</button>
        <button type="button" class="btn btn-primary" onclick="document.getElementById('uploadVideoForm').submit()" style="background: var(--admin-primary); border: none;">Tải lên & Xử lý</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Preview -->
<div class="modal fade" id="previewVideoModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow" style="background: var(--admin-card); overflow: hidden;">
      <div class="modal-header border-bottom border-light">
        <h5 class="modal-title fw-bold">Xem trước Video HLS</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0 bg-black">
        <video id="hlsVideoPlayer" controls style="width: 100%; max-height: 70vh; display: block;"></video>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const videoRows = document.querySelectorAll('tr[data-video-id]');
    const pendingVideoIds = [];
    
    // Check which videos are not completed/error
    videoRows.forEach(row => {
        const id = row.getAttribute('data-video-id');
        const statusHtml = document.getElementById('video-status-' + id).innerHTML;
        if (statusHtml.includes('Đang chờ') || statusHtml.includes('Đang xử lý')) {
            pendingVideoIds.push(id);
        }
    });

    if (pendingVideoIds.length > 0) {
        setInterval(() => {
            fetch('{{ route("admin.videos.status") }}?ids[]=' + pendingVideoIds.join('&ids[]='))
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data) {
                        data.data.forEach(video => {
                            const cell = document.getElementById('video-status-' + video.id);
                            if (cell) {
                                let html = '';
                                if (video.trang_thai === 'hoan_thanh') {
                                    html = '<span class="badge bg-success bg-opacity-10 text-success border border-success-subtle">Hoàn thành</span>';
                                    // Remove from polling if done
                                    const index = pendingVideoIds.indexOf(video.id.toString());
                                    if (index > -1) pendingVideoIds.splice(index, 1);
                                } else if (video.trang_thai === 'dang_xu_ly') {
                                    html = `<span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle">Đang xử lý (${video.phan_tram}%)</span>`;
                                } else if (video.trang_thai === 'dang_cho') {
                                    html = '<span class="badge bg-warning bg-opacity-10 text-warning border border-warning-subtle">Đang chờ</span>';
                                } else {
                                    html = '<span class="badge bg-danger bg-opacity-10 text-danger border border-danger-subtle">Lỗi</span>';
                                    const index = pendingVideoIds.indexOf(video.id.toString());
                                    if (index > -1) pendingVideoIds.splice(index, 1);
                                }
                                cell.innerHTML = html;
                            }
                        });
                    }
                })
                .catch(err => console.error('Error fetching video status:', err));
        }, 3000); // Check every 3 seconds
    }

    // Preview Logic
    const previewModal = new bootstrap.Modal(document.getElementById('previewVideoModal'));
    const videoElement = document.getElementById('hlsVideoPlayer');
    let hlsInstance = null;

    document.querySelectorAll('.btn-preview-video').forEach(btn => {
        btn.addEventListener('click', function() {
            const hlsUrl = this.getAttribute('data-hls');
            
            if (Hls.isSupported()) {
                if (hlsInstance) {
                    hlsInstance.destroy();
                }
                hlsInstance = new Hls();
                hlsInstance.loadSource(hlsUrl);
                hlsInstance.attachMedia(videoElement);
                hlsInstance.on(Hls.Events.MANIFEST_PARSED, function() {
                    videoElement.play();
                });
            } else if (videoElement.canPlayType('application/vnd.apple.mpegurl')) {
                // For Safari
                videoElement.src = hlsUrl;
                videoElement.addEventListener('loadedmetadata', function() {
                    videoElement.play();
                });
            }
            previewModal.show();
        });
    });

    document.getElementById('previewVideoModal').addEventListener('hidden.bs.modal', function () {
        videoElement.pause();
        if (hlsInstance) {
            hlsInstance.destroy();
            hlsInstance = null;
        }
        videoElement.src = '';
    });
});
</script>
@endsection
