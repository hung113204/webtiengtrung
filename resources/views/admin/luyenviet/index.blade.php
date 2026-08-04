@extends('admin.layouts.main')

@section('title', 'Quản lý Luyện viết — Hányǔ Admin')

@section('content')
<div class="page-header animate-fade-in delay-1">
  <div>
    <h1 class="fs-4 fw-bold mb-1">Quản lý Luyện viết</h1>
    <p class="text-muted mb-0 small">Quản lý các chữ Hán, bộ thủ, quy tắc bút thuận và ảnh GIF minh họa nét viết.</p>
  </div>
  <button class="btn btn-primary d-flex align-items-center gap-2 shadow-sm" style="background: var(--admin-primary); border: none;" data-bs-toggle="modal" data-bs-target="#addWritingModal">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
    Thêm chữ viết mới
  </button>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show animate-fade-in" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

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

<!-- Writing Data Table -->
<div class="card bg-white border-0 shadow-sm rounded-3 animate-fade-in delay-2 mb-4">
  <div class="card-header bg-white border-bottom-0 pt-4 pb-2 d-flex flex-wrap gap-3">
    <form action="{{ route('admin.luyenviet.index') }}" method="GET" class="d-flex gap-3 flex-grow-1">
        <div class="input-group" style="max-width: 300px;">
        <span class="input-group-text bg-white border-end-0 text-muted">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        </span>
        <input type="text" name="search" value="{{ request('search') }}" class="form-control border-start-0 ps-0" placeholder="Tìm chữ Hán, pinyin...">
        </div>
        
        <select class="form-select" name="bo_thu" onchange="this.form.submit()" style="max-width: 180px;">
        <option value="">Lọc theo Bộ thủ</option>
        <option value="nu" {{ request('bo_thu') == 'nu' ? 'selected' : '' }}>Nữ (女)</option>
        <option value="thuy" {{ request('bo_thu') == 'thuy' ? 'selected' : '' }}>Thủy (水)</option>
        <option value="hoa" {{ request('bo_thu') == 'hoa' ? 'selected' : '' }}>Hỏa (火)</option>
        <option value="tam" {{ request('bo_thu') == 'tam' ? 'selected' : '' }}>Tâm (心)</option>
        </select>
    </form>
  </div>

  <div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead class="table-light text-muted small">
        <tr>
          <th class="fw-medium px-4 py-3" style="width: 80px;">ID</th>
          <th class="fw-medium py-3">Chữ Hán & Pinyin</th>
          <th class="fw-medium py-3">Minh họa (GIF)</th>
          <th class="fw-medium py-3">Thông tin nét</th>
          <th class="fw-medium py-3">Liên kết bài học</th>
          <th class="fw-medium pe-4 py-3 text-end">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        @forelse($luyenViets as $item)
            <tr>
            <td class="px-4 py-3 text-muted">{{ $item->id }}</td>
            <td>
                <div class="d-flex align-items-center gap-3">
                <div style="width: 50px; height: 50px; background: #fee2e2; color: var(--admin-primary); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-family: 'KaiTi', serif;">
                    {{ $item->chu_han }}
                </div>
                <div>
                    <div class="fw-bold text-dark fs-5">{{ $item->pinyin ?? 'N/A' }}</div>
                    <div class="small text-muted">{{ $item->nghia ?? 'N/A' }}</div>
                </div>
                </div>
            </td>
            <td>
                @if($item->gif_net_viet)
                    <img src="{{ Storage::url($item->gif_net_viet) }}" alt="GIF" class="border rounded object-fit-cover" style="width: 60px; height: 60px;">
                @else
                    <div class="border rounded d-flex justify-content-center align-items-center" style="width: 60px; height: 60px; background: #f8f9fa;">
                    <span class="small text-muted">No GIF</span>
                    </div>
                @endif
            </td>
            <td class="small text-muted">
                <div class="d-flex flex-column gap-1">
                <span>Số nét: <strong class="text-dark">{{ $item->so_net ?? 'N/A' }} nét</strong></span>
                <span>Bộ thủ: <strong class="text-dark">{{ $item->bo_thu ?? 'N/A' }}</strong></span>
                </div>
            </td>
            <td>
                <span class="badge bg-light text-dark border">{{ $item->baiHoc->ten_bai_hoc ?? 'N/A' }}</span>
            </td>
            <td class="text-end pe-4">
                <div class="d-flex justify-content-end gap-1">
                <button class="icon-btn" title="Chỉnh sửa" data-bs-toggle="modal" data-bs-target="#editWritingModal{{ $item->id }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                </button>
                <form action="{{ route('admin.luyenviet.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa không?');">
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
                <td colspan="6" class="text-center py-5 text-muted">
                    Chưa có dữ liệu luyện viết nào. Hãy thêm chữ Hán mới!
                </td>
            </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  
  @if($luyenViets->hasPages())
  <div class="card-footer bg-white border-top p-3 d-flex justify-content-end">
      {{ $luyenViets->links('pagination::bootstrap-5') }}
  </div>
  @endif
</div>

<!-- Modal Thêm Luyện Viết -->
<div class="modal fade" id="addWritingModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow" style="background: var(--admin-card);">
      <div class="modal-header border-bottom border-light">
        <h5 class="modal-title fw-bold">Thêm Chữ Hán mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('admin.luyenviet.store') }}" method="POST" enctype="multipart/form-data" id="addWritingForm">
          @csrf
          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label fw-medium">Chữ Hán <span class="text-danger">*</span></label>
              <input type="text" name="chu_han" class="form-control text-center" placeholder="VD: 好" style="font-size: 2rem; font-family: 'KaiTi', serif;" required>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label fw-medium">Pinyin</label>
              <input type="text" name="pinyin" class="form-control" placeholder="VD: hǎo">
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label fw-medium">Nghĩa tiếng Việt</label>
              <input type="text" name="nghia" class="form-control" placeholder="VD: Tốt, đẹp">
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium">Số nét</label>
              <input type="number" name="so_net" class="form-control" placeholder="VD: 6">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium">Bộ thủ</label>
              <input type="text" name="bo_thu" class="form-control" placeholder="VD: Nữ (女)">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium">File GIF minh họa (Bút thuận)</label>
            <input class="form-control" name="gif_net_viet" type="file" accept="image/gif">
            <div class="form-text">Vui lòng tải lên file ảnh động (.gif) hướng dẫn cách viết từng nét. Kích thước tối đa 2MB.</div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium">Liên kết Bài học <span class="text-danger">*</span></label>
            <select class="form-select" name="id_bai_hoc" required>
              <option value="">-- Chọn bài học --</option>
              @foreach($baiHocs as $baiHoc)
                  <option value="{{ $baiHoc->id }}">{{ $baiHoc->ten_bai_hoc }}</option>
              @endforeach
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer border-top border-light">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy bỏ</button>
        <button type="submit" form="addWritingForm" class="btn btn-primary" style="background: var(--admin-primary); border: none;">Lưu dữ liệu</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Sửa Luyện Viết -->
@foreach($luyenViets as $item)
<div class="modal fade" id="editWritingModal{{ $item->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow" style="background: var(--admin-card);">
      <div class="modal-header border-bottom border-light">
        <h5 class="modal-title fw-bold">Chỉnh sửa Chữ Hán</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('admin.luyenviet.update', $item->id) }}" method="POST" enctype="multipart/form-data" id="editWritingForm{{ $item->id }}">
          @csrf
          @method('PUT')
          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label fw-medium">Chữ Hán <span class="text-danger">*</span></label>
              <input type="text" name="chu_han" value="{{ $item->chu_han }}" class="form-control text-center" style="font-size: 2rem; font-family: 'KaiTi', serif;" required>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label fw-medium">Pinyin</label>
              <input type="text" name="pinyin" value="{{ $item->pinyin }}" class="form-control">
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label fw-medium">Nghĩa tiếng Việt</label>
              <input type="text" name="nghia" value="{{ $item->nghia }}" class="form-control">
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium">Số nét</label>
              <input type="number" name="so_net" value="{{ $item->so_net }}" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-medium">Bộ thủ</label>
              <input type="text" name="bo_thu" value="{{ $item->bo_thu }}" class="form-control">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium">File GIF minh họa (Bút thuận)</label>
            @if($item->gif_net_viet)
                <div class="mb-2">
                    <img src="{{ Storage::url($item->gif_net_viet) }}" alt="GIF" class="border rounded" style="width: 80px; height: 80px;">
                </div>
            @endif
            <input class="form-control" name="gif_net_viet" type="file" accept="image/gif">
            <div class="form-text">Để trống nếu không muốn thay đổi. Tải file mới lên để thay thế file cũ.</div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium">Liên kết Bài học <span class="text-danger">*</span></label>
            <select class="form-select" name="id_bai_hoc" required>
              <option value="">-- Chọn bài học --</option>
              @foreach($baiHocs as $baiHoc)
                  <option value="{{ $baiHoc->id }}" {{ $item->id_bai_hoc == $baiHoc->id ? 'selected' : '' }}>{{ $baiHoc->ten_bai_hoc }}</option>
              @endforeach
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer border-top border-light">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy bỏ</button>
        <button type="submit" form="editWritingForm{{ $item->id }}" class="btn btn-primary" style="background: var(--admin-primary); border: none;">Lưu cập nhật</button>
      </div>
    </div>
  </div>
</div>
@endforeach

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tìm ô input Chữ Hán và Số nét trong form Thêm mới
    const chuHanInput = document.querySelector('#addWritingModal input[name="chu_han"]');
    const soNetInput = document.querySelector('#addWritingModal input[name="so_net"]');

    if (chuHanInput && soNetInput) {
        // Lắng nghe sự kiện khi người dùng gõ xong (blur hoặc input)
        chuHanInput.addEventListener('change', async function() {
            const text = this.value.trim();
            if (!text) return;
            
            // Lấy các chữ Hán (loại bỏ dấu câu)
            const chars = text.match(/[\u4E00-\u9FA5]/g) || [];
            
            if (chars.length > 0) {
                soNetInput.placeholder = "Đang đếm nét bằng AJAX...";
                let totalStrokes = 0;
                
                // Dùng AJAX (Fetch) gọi đến API Hanzi Writer để lấy số nét từng chữ
                for (let char of chars) {
                    try {
                        const response = await fetch(`https://cdn.jsdelivr.net/npm/hanzi-writer-data@2.0/${char}.json`);
                        if (response.ok) {
                            const data = await response.json();
                            totalStrokes += data.strokes.length; // Độ dài mảng strokes chính là số nét
                        }
                    } catch (error) {
                        console.error('Lỗi khi lấy dữ liệu nét chữ:', error);
                    }
                }
                
                if (totalStrokes > 0) {
                    soNetInput.value = totalStrokes;
                } else {
                    soNetInput.placeholder = "VD: 6";
                }
            }
        });
    }
});
</script>
@endsection
