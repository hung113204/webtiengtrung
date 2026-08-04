@extends('admin.layouts.main')

@section('content')
      <div class="page-header">
        <div>
          <h1 class="fs-4 fw-bold mb-1">Tổng quan hệ thống</h1>
          <p class="text-muted mb-0 small">Theo dõi và phân tích dữ liệu hoạt động của nền tảng.</p>
        </div>
        <button class="btn btn-primary d-flex align-items-center gap-2" style="background: var(--admin-primary); border: none;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
          Xuất báo cáo
        </button>
      </div>

      <!-- Stats -->
      <div class="row g-4 mb-4">
        <div class="col-sm-6 col-lg-3 animate-fade-in delay-1">
          <div class="stat-card">
            <div class="stat-title">Tổng số khóa học</div>
            <div class="stat-value">{{ $stats['total_courses'] }}</div>
            <div class="text-success small mt-2 fw-medium">↑ Thêm 2 khóa tháng này</div>
          </div>
        </div>
        <div class="col-sm-6 col-lg-3 animate-fade-in delay-2">
          <div class="stat-card">
            <div class="stat-title">Học viên đang hoạt động</div>
            <div class="stat-value">{{ number_format($stats['active_users']) }}</div>
            <div class="text-success small mt-2 fw-medium">↑ +15% so với tháng trước</div>
          </div>
        </div>
        <div class="col-sm-6 col-lg-3 animate-fade-in delay-3">
          <div class="stat-card">
            <div class="stat-title">Doanh thu (Dự kiến)</div>
            <div class="stat-value">{{ $stats['expected_revenue'] }}</div>
            <div class="text-success small mt-2 fw-medium">Từ các học viên đã duyệt học</div>
          </div>
        </div>
        <div class="col-sm-6 col-lg-3 animate-fade-in delay-4">
          <div class="stat-card">
            <div class="stat-title">Yêu cầu đăng ký chờ duyệt</div>
            <div class="stat-value">{{ $stats['new_registrations'] }}</div>
            <div class="text-muted small mt-2 fw-medium">Cần phê duyệt kích hoạt...</div>
          </div>
        </div>
      </div>

      <!-- Charts Section -->
      <div class="row g-4 mb-4">
        <div class="col-lg-8 animate-fade-in delay-4">
          <div class="table-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
              <h3 class="fs-6 fw-semibold mb-0">Biểu đồ doanh thu (30 ngày qua)</h3>
              <select class="form-select form-select-sm w-auto">
                <option>Tháng này</option>
                <option>Tháng trước</option>
                <option>Năm nay</option>
              </select>
            </div>
            <canvas id="revenueChart" height="100"></canvas>
          </div>
        </div>
        <div class="col-lg-4 animate-fade-in delay-5">
          <div class="table-card p-4 h-100">
            <h3 class="fs-6 fw-semibold mb-4">Cơ cấu học viên</h3>
            <canvas id="userChart" height="200"></canvas>
          </div>
        </div>
      </div>

      <!-- User Activity Chart Section -->
      <div class="row g-4 mb-4">
        <div class="col-lg-12 animate-fade-in delay-6">
          <div class="table-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
              <h3 class="fs-6 fw-semibold mb-0">Biểu đồ đăng ký học viên mới (6 tháng gần nhất)</h3>
              <button class="btn btn-sm btn-outline-primary">Tải báo cáo</button>
            </div>
            <canvas id="userRegistrationChart" height="80"></canvas>
          </div>
        </div>
      </div>

      <!-- Recent Activity Table -->
      <div class="table-card animate-fade-in delay-5">
        <div class="table-header">
          <h3 class="fs-6 fw-semibold mb-0">Đơn hàng & Đăng ký mới nhất</h3>
          <a href="{{ route('admin.dangkykhoahoc.index') }}" class="btn btn-sm btn-outline-secondary">Xem tất cả</a>
        </div>
        <div class="table-responsive">
          <table class="table table-hover mb-0 align-middle">
            <thead class="table-light text-muted small">
              <tr>
                <th class="fw-medium px-4 py-3">Học viên</th>
                <th class="fw-medium py-3">Khóa học</th>
                <th class="fw-medium py-3">Thời gian</th>
                <th class="fw-medium py-3">Thanh toán</th>
                <th class="fw-medium pe-4 py-3 text-end">Hành động</th>
              </tr>
            </thead>
            <tbody>
              @foreach($recentOrders as $order)
              <tr>
                <td class="px-4">
                  <div class="fw-semibold text-dark">{{ $order['name'] }}</div>
                  <div class="small text-muted">{{ $order['email'] }}</div>
                </td>
                <td><span class="badge bg-light text-dark border">{{ $order['course_tag'] }}</span> {{ $order['course_name'] }}</td>
                <td class="text-muted small">{{ $order['time'] }}</td>
                <td><span class="badge {{ $order['status_class'] }}">{{ $order['status_text'] }}</span></td>
                <td class="text-end pe-4"><a href="{{ route('admin.dangkykhoahoc.index') }}" class="btn btn-sm btn-light border">Chi tiết</a></td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
@endsection

@section('scripts')
    <script>
        window.chartData = @json($chartData);
    </script>
    <script src="{{ asset('backend/asset/js/home.js') }}"></script>
@endsection


