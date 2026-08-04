@extends('admin.layouts.main')

@section('title', 'Quản lý nội dung Bài học — Hányǔ Admin')

@section('content')
<div class="page-header animate-fade-in delay-1">
  <div class="d-flex align-items-center gap-3">
    <a href="{{ route('admin.baihoc.index') }}" class="btn btn-light btn-sm text-muted">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
    </a>
    <div>
      <h1 class="fs-4 fw-bold mb-1">Nội dung Bài học: {{ $baiHoc->ten_bai_hoc }}</h1>
      <p class="text-muted mb-0 small">Thuộc chương: {{ $baiHoc->chuongHoc->ten_chuong ?? 'N/A' }} — Khóa: {{ $baiHoc->chuongHoc->khoaHoc->ten_khoa_hoc ?? 'N/A' }}</p>
    </div>
  </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show animate-fade-in mt-3" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show animate-fade-in mt-3" role="alert">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="row mt-4 animate-fade-in delay-2">
  <div class="col-12">
    <ul class="nav nav-tabs border-bottom-0 gap-2 mb-4" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active rounded-top" data-bs-toggle="tab" data-bs-target="#cau-hinh" type="button" role="tab"><i class="fas fa-cog me-1"></i>Cấu hình Bài học</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-top" data-bs-toggle="tab" data-bs-target="#video-bai-giang" type="button" role="tab"><i class="fas fa-video me-1"></i>Video bài giảng</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-top" data-bs-toggle="tab" data-bs-target="#tu-vung" type="button" role="tab">Từ vựng</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-top" data-bs-toggle="tab" data-bs-target="#ngu-phap" type="button" role="tab">Ngữ pháp</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-top" data-bs-toggle="tab" data-bs-target="#hoi-thoai" type="button" role="tab">Hội thoại</button>
        </li>
    </ul>

    <div class="tab-content table-card p-4">
        <!-- Tab Cấu hình Bài học -->
        <div class="tab-pane fade show active" id="cau-hinh" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Điều kiện hoàn thành Bài học</h5>
            </div>
            
            <form action="{{ route('admin.baihoc.updateCondition', $baiHoc->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="border rounded p-4 bg-light mb-3">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Chế độ hoàn thành bài học</label>
                        <select class="form-select" name="loai_dieu_kien" id="loai_dieu_kien" onchange="toggleConditionInput()">
                            <option value="tu_dong" {{ $baiHoc->loai_dieu_kien === 'tu_dong' ? 'selected' : '' }}>Tự động hoàn thành (Ngay khi vào bài học)</option>
                            <option value="xem_video" {{ $baiHoc->loai_dieu_kien === 'xem_video' ? 'selected' : '' }}>Yêu cầu xem Video (Theo % thời lượng)</option>
                            <option value="kiem_tra" {{ $baiHoc->loai_dieu_kien === 'kiem_tra' ? 'selected' : '' }}>Yêu cầu vượt qua Bài kiểm tra cuối bài</option>
                            <option value="phat_am_ai" {{ $baiHoc->loai_dieu_kien === 'phat_am_ai' ? 'selected' : '' }}>Thực hành Phát âm AI (Yêu cầu nhận diện giọng nói)</option>
                        </select>
                    </div>

                    <div id="phan_tram_video_wrapper" class="mb-3 {{ $baiHoc->loai_dieu_kien !== 'xem_video' ? 'd-none' : '' }}">
                        <label class="form-label fw-bold">Phần trăm xem video tối thiểu (%)</label>
                        <input type="number" class="form-control" name="phan_tram_video" value="{{ $baiHoc->phan_tram_video ?? 80 }}" min="1" max="100">
                        <div class="form-text">Học viên phải xem ít nhất tỷ lệ này của video bài giảng để được tính là hoàn thành.</div>
                    </div>

                    <div id="kiem_tra_wrapper" class="alert alert-info {{ $baiHoc->loai_dieu_kien !== 'kiem_tra' ? 'd-none' : '' }}">
                        <i class="fas fa-info-circle me-2"></i> Hệ thống bài kiểm tra đang được phát triển. Tạm thời bài học sẽ tự động hoàn thành.
                    </div>

                    <script>
                        function toggleConditionInput() {
                            var type = document.getElementById('loai_dieu_kien').value;
                            var videoWrapper = document.getElementById('phan_tram_video_wrapper');
                            var kiemTraWrapper = document.getElementById('kiem_tra_wrapper');
                            
                            videoWrapper.classList.add('d-none');
                            kiemTraWrapper.classList.add('d-none');
                            
                            if (type === 'xem_video') {
                                videoWrapper.classList.remove('d-none');
                            } else if (type === 'kiem_tra') {
                                kiemTraWrapper.classList.remove('d-none');
                            }
                        }
                    </script>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-primary" style="background: var(--admin-primary); border: none;">Lưu Cấu hình</button>
                </div>
            </form>
        </div>

        <!-- Tab Video -->
        <div class="tab-pane fade" id="video-bai-giang" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Nguồn Video Bài giảng</h5>
            </div>
            
            @if($baiHoc->video_id && $baiHoc->videoItem)
                <div class="mb-4 text-center bg-light p-3 border rounded">
                    <p class="fw-bold text-muted mb-3"><i class="fas fa-play-circle me-1"></i>Bản xem trước Video (HLS)</p>
                    <video id="preview-video" class="video-js vjs-default-skin vjs-big-play-centered mx-auto rounded shadow-sm" controls preload="auto" width="640" height="360" data-setup='{}'>
                        <source src="{{ Storage::url($baiHoc->videoItem->hls_path) }}" type="application/x-mpegURL">
                        Trình duyệt của bạn không hỗ trợ thẻ video.
                    </video>
                </div>
            @elseif($baiHoc->video && filter_var($baiHoc->video, FILTER_VALIDATE_URL))
                <div class="mb-4 text-center bg-light p-3 border rounded">
                    <p class="fw-bold text-muted mb-3"><i class="fas fa-link me-1"></i>Bản xem trước (Video URL)</p>
                    @if(Str::contains($baiHoc->video, 'youtube.com') || Str::contains($baiHoc->video, 'youtu.be'))
                        @php
                            $ytId = '';
                            if(preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $baiHoc->video, $match)) {
                                $ytId = $match[1];
                            }
                        @endphp
                        @if($ytId)
                            <div class="ratio ratio-16x9 mx-auto shadow-sm rounded overflow-hidden" style="max-width: 640px;">
                                <iframe src="https://www.youtube.com/embed/{{ $ytId }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        @else
                            <a href="{{ $baiHoc->video }}" target="_blank" class="btn btn-outline-primary">Mở Video Link</a>
                        @endif
                    @else
                        <video id="preview-video" class="video-js vjs-default-skin vjs-big-play-centered mx-auto rounded shadow-sm" controls preload="auto" width="640" height="360">
                            <source src="{{ $baiHoc->video }}" type="video/mp4">
                            Trình duyệt của bạn không hỗ trợ thẻ video.
                        </video>
                    @endif
                </div>
            @endif
            
            <form action="{{ route('admin.baihoc.updateVideo', $baiHoc->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="border rounded p-4 bg-light mb-3">
                    @php
                        $isUrl = empty($baiHoc->video_id) && filter_var($baiHoc->video, FILTER_VALIDATE_URL);
                        $isLibrary = !empty($baiHoc->video_id);
                        if(!$isUrl && !$isLibrary) { $isUrl = true; }
                    @endphp
                    <select class="form-select mb-3" id="editVideoSource" name="video_type" onchange="toggleEditVideoInput()" required>
                        <option value="url" {{ $isUrl ? 'selected' : '' }}>Sử dụng Link (Youtube, Facebook, Vimeo...)</option>
                        <option value="library" {{ $isLibrary ? 'selected' : '' }}>Chọn từ Thư viện Video</option>
                    </select>
                    
                    <div id="editVideoUrlWrapper" class="{{ !$isUrl ? 'd-none' : '' }}">
                        <input type="text" class="form-control" name="video_url" value="{{ $isUrl ? $baiHoc->video : '' }}" placeholder="VD: https://www.youtube.com/watch?v=...">
                        <div class="form-text mt-2">Dán đường dẫn video bài giảng chính của bài học này.</div>
                    </div>
                    
                    <div id="editVideoLibraryWrapper" class="{{ !$isLibrary ? 'd-none' : '' }}">
                        <select class="form-select" name="video_id" id="video_id_select">
                            <option value="">-- Chọn video đã xử lý --</option>
                            @foreach($videos as $video)
                                <option value="{{ $video->id }}" data-hls="{{ Storage::url($video->hls_path) }}" {{ $baiHoc->video_id == $video->id ? 'selected' : '' }}>{{ $video->ten_video }}</option>
                            @endforeach
                        </select>
                        <div class="form-text mt-2">Chỉ hiển thị các video đã được AI xử lý xong. <a href="{{ route('admin.videos.index') }}" target="_blank">Quản lý thư viện</a></div>
                    </div>
                    
                    <script>
                        function toggleEditVideoInput() {
                            var type = document.getElementById('editVideoSource').value;
                            if (type === 'url') {
                                document.getElementById('editVideoUrlWrapper').classList.remove('d-none');
                                document.getElementById('editVideoLibraryWrapper').classList.add('d-none');
                            } else {
                                document.getElementById('editVideoUrlWrapper').classList.add('d-none');
                                document.getElementById('editVideoLibraryWrapper').classList.remove('d-none');
                            }
                        }

                        // Cập nhật live preview khi đổi video trong dropdown
                        document.addEventListener('DOMContentLoaded', function() {
                            var videoSelect = document.getElementById('video_id_select');
                            var previewVideo = document.getElementById('preview-video');
                            
                            if(videoSelect && previewVideo) {
                                videoSelect.addEventListener('change', function() {
                                    var selectedOption = this.options[this.selectedIndex];
                                    var hlsUrl = selectedOption.getAttribute('data-hls');
                                    
                                    if(hlsUrl) {
                                        // Nếu dùng video.js
                                        if (typeof videojs !== 'undefined') {
                                            var player = videojs('preview-video');
                                            player.src({
                                                src: hlsUrl,
                                                type: 'application/x-mpegURL'
                                            });
                                            player.play();
                                        } else {
                                            // Fallback HTML5
                                            previewVideo.src = hlsUrl;
                                            previewVideo.play();
                                        }
                                    }
                                });
                            }
                        });
                    </script>
                </div>
                
                <div class="text-end">
                    <button type="submit" class="btn btn-primary" style="background: var(--admin-primary); border: none;">Lưu Video</button>
                </div>
            </form>
        </div>

        <!-- Tab Từ vựng -->
        <div class="tab-pane fade" id="tu-vung" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Danh sách Từ vựng</h5>
                <a href="{{ route('admin.tuvung.index') }}" class="btn btn-primary btn-sm" style="background: var(--admin-primary); border: none;">Quản lý từ vựng</a>
            </div>
            
            @if($baiHoc->tuVungs->count() > 0)
            <div class="table-responsive border rounded">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light text-muted small">
                        <tr>
                            <th class="fw-medium px-4 py-3" style="width: 30%;">Từ vựng (Hán tự & Pinyin)</th>
                            <th class="fw-medium py-3">Nghĩa tiếng Việt</th>
                            <th class="fw-medium py-3">Câu ví dụ</th>
                            <th class="fw-medium py-3 text-center" style="width: 100px;">Âm thanh</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($baiHoc->tuVungs as $tuVung)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="d-flex flex-column">
                                        <span class="fs-5 fw-bold text-dark" style="font-family: 'Noto Sans SC', sans-serif;">{{ $tuVung->tu_han }}</span>
                                        <span class="small text-danger fw-medium">{{ $tuVung->phien_am }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-dark">{{ $tuVung->nghia_tieng_viet }}</td>
                            <td class="text-muted small">{{ $tuVung->vi_du ?? 'N/A' }}</td>
                            <td class="text-center">
                                @if($tuVung->am_thanh)
                                <button class="btn btn-sm btn-light border rounded-circle d-flex align-items-center justify-content-center p-1 mx-auto" style="width: 28px; height: 28px;" title="Nghe phát âm" onclick="new Audio('{{ Storage::url($tuVung->am_thanh) }}').play()">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon><path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path></svg>
                                </button>
                                @else
                                <span class="text-muted small">N/A</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5 text-muted border rounded bg-light">
                <p class="mb-0">Chưa có từ vựng nào trong bài học này.</p>
            </div>
            @endif
        </div>

        <!-- Tab Ngữ pháp -->
        <div class="tab-pane fade" id="ngu-phap" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Cấu trúc Ngữ pháp</h5>
                <a href="{{ route('admin.nguphap.index') }}" class="btn btn-primary btn-sm" style="background: var(--admin-primary); border: none;">Quản lý ngữ pháp</a>
            </div>
            
            @if($baiHoc->nguPhaps->count() > 0)
            <div class="table-responsive border rounded">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light text-muted small">
                        <tr>
                            <th class="fw-medium px-4 py-3" style="width: 25%;">Tiêu đề</th>
                            <th class="fw-medium py-3" style="width: 25%;">Cấu trúc</th>
                            <th class="fw-medium py-3">Giải thích & Ví dụ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($baiHoc->nguPhaps as $nguPhap)
                        <tr>
                            <td class="px-4 py-3 fw-medium text-dark">{{ $nguPhap->tieu_de }}</td>
                            <td><code class="bg-light p-1 rounded text-primary">{{ $nguPhap->cau_truc }}</code></td>
                            <td>
                                <div class="small text-muted mb-1">{{ Str::limit($nguPhap->giai_thich, 100) }}</div>
                                @if($nguPhap->vi_du)
                                    <div class="small fst-italic border-start border-2 border-primary ps-2">{{ Str::limit($nguPhap->vi_du, 100) }}</div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5 text-muted border rounded bg-light">
                <p class="mb-0">Chưa có điểm ngữ pháp nào được thêm.</p>
            </div>
            @endif
        </div>

        <!-- Tab Hội thoại -->
        <div class="tab-pane fade" id="hoi-thoai" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Bài Hội thoại</h5>
                <a href="{{ route('admin.hoithoai.index') }}" class="btn btn-primary btn-sm" style="background: var(--admin-primary); border: none;">Quản lý hội thoại</a>
            </div>
            
            @if($baiHoc->hoiThoais->count() > 0)
            <div class="table-responsive border rounded">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light text-muted small">
                        <tr>
                            <th class="fw-medium px-4 py-3">Tiêu đề Hội thoại</th>
                            <th class="fw-medium py-3">Mô tả</th>
                            <th class="fw-medium py-3 text-center">Số câu thoại</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($baiHoc->hoiThoais as $hoiThoai)
                        <tr>
                            <td class="px-4 py-3 fw-medium text-dark">{{ $hoiThoai->tieu_de }}</td>
                            <td class="text-muted small">{{ Str::limit($hoiThoai->mo_ta, 100) }}</td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border">{{ $hoiThoai->chiTietHoiThoais->count() }} câu</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5 text-muted border rounded bg-light">
                <p class="mb-0">Chưa có bài hội thoại nào.</p>
            </div>
            @endif
        </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<link href="https://vjs.zencdn.net/8.10.0/video-js.css" rel="stylesheet" />
<script src="https://vjs.zencdn.net/8.10.0/video.min.js"></script>
@endsection
