@extends('admin.layouts.main')

@section('title', 'Cấu trúc Lộ trình — Hányǔ Admin')

@section('content')
<div class="page-header animate-fade-in d-flex justify-content-between align-items-center mb-4">
  <div>
    <h1 class="fs-4 fw-bold mb-1">Cấu trúc lộ trình: {{ $loTrinh->ten_lo_trinh }}</h1>
    <p class="text-muted mb-0 small">Quản lý các giai đoạn học và gán khóa học vào từng giai đoạn.</p>
  </div>
  <div class="d-flex gap-2">
    <a href="{{ route('admin.lotrinh.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
      Quay lại
    </a>
    <button type="button" class="btn btn-primary d-flex align-items-center gap-2" style="background: var(--admin-primary); border: none;" data-bs-toggle="modal" data-bs-target="#addGiaiDoanModal">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
      Thêm giai đoạn
    </button>
  </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show animate-fade-in" role="alert">
  <div class="d-flex align-items-center gap-2">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
    {{ session('success') }}
  </div>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show animate-fade-in" role="alert">
  <div class="d-flex align-items-center gap-2">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
    {{ session('error') }}
  </div>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show animate-fade-in" role="alert">
  <div class="d-flex flex-column gap-1">
    @foreach($errors->all() as $error)
      <div class="d-flex align-items-center gap-2">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
        {{ $error }}
      </div>
    @endforeach
  </div>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="row">
  <div class="col-12">
    @forelse($loTrinh->giaiDoans as $gd)
    <div class="card border-0 shadow-sm mb-4 animate-fade-in">
      <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
        <div>
          <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle me-2">Giai đoạn {{ $gd->thu_tu }}</span>
          @if($gd->icon_text)
          <span class="badge bg-danger text-white rounded-circle me-2 d-inline-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">{{ $gd->icon_text }}</span>
          @endif
          <h5 class="mb-0 fw-bold d-inline-block">{{ $gd->ten_giai_doan }}</h5>
          @if($gd->mo_ta)
          <p class="text-muted small mb-0 mt-1">{{ $gd->mo_ta }}</p>
          @endif
        </div>
        <div class="d-flex gap-2">
          <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editGiaiDoanModal{{ $gd->id }}">Sửa</button>
          <form action="{{ route('admin.lotrinh.destroyGiaiDoan', [$loTrinh->id, $gd->id]) }}" method="POST" onsubmit="return confirm('Xóa giai đoạn này sẽ xóa toàn bộ khóa học bên trong. Chắc chắn xóa?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger">Xóa</button>
          </form>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="row g-0">
          <!-- Cột hiển thị danh sách khóa học -->
          <div class="col-md-8 border-end">
            <div class="table-responsive">
              <table class="table mb-0 align-middle table-hover">
                <thead class="table-light text-muted small">
                  <tr>
                    <th class="fw-medium ps-4 py-3" style="width: 80px;">Thứ tự</th>
                    <th class="fw-medium py-3">Khóa học</th>
                    <th class="fw-medium text-end pe-4 py-3">Thao tác</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($gd->khoaHocs as $index => $kh)
                  <tr>
                    <td class="ps-4 text-muted fw-bold">#{{ $index + 1 }}</td>
                    <td>
                      <div class="d-flex align-items-center gap-3 py-2">
                        <div style="width: 60px; height: 40px; border-radius: 4px; overflow: hidden; background: #f3f4f6;">
                          <img src="{{ $kh->anh_bia ? Storage::url($kh->anh_bia) : 'https://images.unsplash.com/photo-1546422904-90eab23c3d7e?auto=format&fit=crop&w=60&q=80' }}" class="w-100 h-100" style="object-fit: cover;">
                        </div>
                        <div>
                          <div class="fw-bold text-dark">{{ $kh->ten_khoa_hoc }}</div>
                          <div class="small text-muted">{{ Str::limit($kh->mo_ta_ngan, 60) }}</div>
                        </div>
                      </div>
                    </td>
                    <td class="text-end pe-4">
                      <form action="{{ route('admin.lotrinh.detach', [$loTrinh->id, $gd->id, $kh->id]) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa khóa học này khỏi giai đoạn?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">Xóa</button>
                      </form>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="3" class="text-center py-5 text-muted">
                      Chưa có khóa học nào trong giai đoạn này.
                    </td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
          <!-- Cột form thêm khóa học -->
          <div class="col-md-4 p-4 bg-light">
            <h6 class="fw-bold mb-3">Gán khóa học vào GĐ {{ $gd->thu_tu }}</h6>
            <form action="{{ route('admin.lotrinh.attach', [$loTrinh->id, $gd->id]) }}" method="POST">
              @csrf
              <div class="mb-3">
                <select name="id_khoa_hoc" class="form-select form-select-sm" required>
                  <option value="">-- Chọn khóa học --</option>
                  @foreach($allKhoaHocs as $kh)
                    <option value="{{ $kh->id }}">{{ $kh->ten_khoa_hoc }}</option>
                  @endforeach
                </select>
              </div>
              <button type="submit" class="btn btn-sm btn-primary w-100" style="background: var(--admin-primary); border: none;">+ Thêm khóa học</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Edit Modal cho Giai Đoạn -->
    <div class="modal fade" id="editGiaiDoanModal{{ $gd->id }}" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
          <div class="modal-header border-bottom border-light">
            <h5 class="modal-title fw-bold">Sửa Giai đoạn</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form action="{{ route('admin.lotrinh.updateGiaiDoan', [$loTrinh->id, $gd->id]) }}" method="POST">
              @csrf
              @method('PUT')
              <div class="mb-3">
                <label class="form-label fw-medium">Ký hiệu / Icon (VD: 你, 1-2, 5)</label>
                <input type="text" name="icon_text" class="form-control" value="{{ $gd->icon_text }}">
              </div>
              <div class="mb-3">
                <label class="form-label fw-medium">Tên giai đoạn</label>
                <input type="text" name="ten_giai_doan" class="form-control" value="{{ $gd->ten_giai_doan }}" required>
              </div>
              <div class="mb-3">
                <label class="form-label fw-medium">Mô tả mục tiêu</label>
                <textarea name="mo_ta" class="form-control" rows="3">{{ $gd->mo_ta }}</textarea>
              </div>
              <div class="mb-3">
                <label class="form-label fw-medium">Thứ tự hiển thị (VD: 1, 2, 3)</label>
                <input type="number" name="thu_tu" class="form-control" value="{{ $gd->thu_tu }}">
              </div>
              <button type="submit" class="btn btn-primary w-100" style="background: var(--admin-primary); border: none;">Lưu thay đổi</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    @empty
    <div class="text-center py-5 text-muted bg-white rounded shadow-sm">
      <div class="mb-2"><svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg></div>
      Lộ trình này chưa có Giai đoạn nào.<br>Hãy thêm giai đoạn đầu tiên!
    </div>
    @endforelse
  </div>
</div>

<!-- Add Giai Doan Modal -->
<div class="modal fade" id="addGiaiDoanModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-bottom border-light">
        <h5 class="modal-title fw-bold">Thêm Giai đoạn mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('admin.lotrinh.storeGiaiDoan', $loTrinh->id) }}" method="POST">
          @csrf
          <div class="mb-3">
            <label class="form-label fw-medium">Ký hiệu / Icon (VD: 你, 1-2, 5)</label>
            <input type="text" name="icon_text" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label fw-medium">Tên giai đoạn (VD: Nhập môn & Nền tảng)</label>
            <input type="text" name="ten_giai_doan" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-medium">Mô tả mục tiêu</label>
            <textarea name="mo_ta" class="form-control" rows="3" placeholder="Xây dựng gốc rễ vững chắc..."></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label fw-medium">Thứ tự hiển thị (VD: 1, 2, 3)</label>
            <input type="number" name="thu_tu" class="form-control" value="{{ ($loTrinh->giaiDoans->max('thu_tu') ?? 0) + 1 }}">
          </div>
          <button type="submit" class="btn btn-primary w-100" style="background: var(--admin-primary); border: none;">Tạo giai đoạn</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
