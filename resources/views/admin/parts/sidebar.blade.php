  <ul class="nav admin-nav d-none d-lg-flex m-0 p-0" style="list-style: none;">
    <li class="nav-item">
      <a class="nav-link admin-nav-item {{ request()->routeIs('admin.home') ? 'active' : '' }}" href="{{ route('admin.home') ?? 'index.html' }}">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"></rect><rect x="14" y="3" width="7" height="7" rx="1"></rect><rect x="14" y="14" width="7" height="7" rx="1"></rect><rect x="3" y="14" width="7" height="7" rx="1"></rect></svg>
        Tổng quan
      </a>
    </li>
    <li class="nav-item dropdown">
      <a class="nav-link admin-nav-item dropdown-toggle {{ request()->routeIs(['admin.danhmuc.*', 'admin.khoahoc.*', 'admin.lotrinh.*', 'admin.chuonghoc.*', 'admin.baihoc.*', 'admin.tuvung.*', 'admin.nguphap.*', 'admin.hoithoai.*', 'admin.khoahocloiich.*','admin.khoahocyeucau.*']) ? 'active' : '' }}" href="#" data-bs-toggle="dropdown">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"></path></svg>
        Đào tạo
      </a>
      <ul class="dropdown-menu shadow-sm border-0 mt-2">
        <li><a class="dropdown-item {{ request()->routeIs('admin.danhmuc.*') ? 'active' : '' }}" href="{{ route('admin.danhmuc.index') }}">Quản lý danh mục</a></li>
        <li><a class="dropdown-item {{ request()->routeIs('admin.khoahoc.*') ? 'active' : '' }}" href="{{ route('admin.khoahoc.index') }}">Quản lý khóa học</a></li>
        <li><a class="dropdown-item {{ request()->routeIs('admin.lotrinh.*') ? 'active' : '' }}" href="{{ route('admin.lotrinh.index') }}">Quản lý lộ trình</a></li>
        <li><a class="dropdown-item {{ request()->routeIs('admin.chuonghoc.*') ? 'active' : '' }}" href="{{ route('admin.chuonghoc.index') }}">Quản lý chương học</a></li>
        <li><a class="dropdown-item {{ request()->routeIs('admin.baihoc.*') ? 'active' : '' }}" href="{{ route('admin.baihoc.index') }}">Quản lý bài học</a></li>
        <li><a class="dropdown-item {{ request()->routeIs('admin.tuvung.*') ? 'active' : '' }}" href="{{ route('admin.tuvung.index') }}">Quản lý từ vựng</a></li>
        <li><a class="dropdown-item {{ request()->routeIs('admin.nguphap.*') ? 'active' : '' }}" href="{{ route('admin.nguphap.index') }}">Quản lý ngữ pháp</a></li>
        <li><a class="dropdown-item {{ request()->routeIs('admin.hoithoai.*') ? 'active' : '' }}" href="{{ route('admin.hoithoai.index') }}">Quản lý hội thoại</a></li>
        <li><a class="dropdown-item {{ request()->routeIs('admin.luyenviet.*') ? 'active' : '' }}" href="{{ route('admin.luyenviet.index') }}">Quản lý luyện viết</a></li>
        <li><a class="dropdown-item {{ request()->routeIs('admin.khoahocloiich.*') ? 'active' : '' }}" href="{{ route('admin.khoahocloiich.index') }}">Quản lý khóa học lợi ích</a></li>
        <li><a class="dropdown-item {{ request()->routeIs('admin.khoahocyeucau.*') ? 'active' : '' }}" href="{{ route('admin.khoahocyeucau.index') }}">Quản lý khóa học yêu cầu</a></li>
      </ul>
    </li>
    <li class="nav-item dropdown">
      <a class="nav-link admin-nav-item dropdown-toggle {{ request()->routeIs(['admin.capdohsk.*', 'admin.loaicauhoi.*', 'admin.mucdocauhoi.*', 'admin.cauhoi.*', 'admin.dethi.*', 'admin.ketqua.*']) ? 'active' : '' }}" href="#" data-bs-toggle="dropdown">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
        Khảo thí
      </a>
      <ul class="dropdown-menu shadow-sm border-0 mt-2">
        <li><a class="dropdown-item {{ request()->routeIs('admin.capdohsk.*') ? 'active' : '' }}" href="{{ route('admin.capdohsk.index') }}">Quản lý HSK</a></li>
        <li><a class="dropdown-item {{ request()->routeIs('admin.loaicauhoi.*') ? 'active' : '' }}" href="{{ route('admin.loaicauhoi.index') }}">Quản lý loại câu hỏi</a></li>
        <li><a class="dropdown-item {{ request()->routeIs('admin.mucdocauhoi.*') ? 'active' : '' }}" href="{{ route('admin.mucdocauhoi.index') }}">Quản lý mức độ câu hỏi</a></li>
        <li><a class="dropdown-item {{ request()->routeIs('admin.cauhoi.*') ? 'active' : '' }}" href="{{ route('admin.cauhoi.index') }}">Quản lý câu hỏi</a></li>
        <li><a class="dropdown-item {{ request()->routeIs('admin.dethi.*') ? 'active' : '' }}" href="{{ route('admin.dethi.index') }}">Quản lý đề thi</a></li>
        <li><a class="dropdown-item {{ request()->routeIs('admin.ketqua.*') ? 'active' : '' }}" href="{{ route('admin.ketqua.index') }}">Quản lý kết quả</a></li>
        <li><a class="dropdown-item" href="#">Quản lý chứng chỉ</a></li>
      </ul>
    </li>
    <li class="nav-item dropdown">
      <a class="nav-link admin-nav-item dropdown-toggle {{ request()->routeIs(['admin.nguoidung.*', 'admin.vaitro.*', 'admin.hosogiaovien.*', 'admin.hosohocvien.*', 'admin.phancong.*', 'admin.tiendo.*', 'admin.binhluan.*', 'admin.dangkykhoahoc.*', 'admin.hoadon.*', 'admin.thongbao.*', 'admin.danhgia.*']) ? 'active' : '' }}" href="#" data-bs-toggle="dropdown">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 00-3-3.87"></path><path d="M16 3.13a4 4 0 010 7.75"></path></svg>
        Tương tác & Nhân sự
      </a>
      <ul class="dropdown-menu shadow-sm border-0 mt-2">
        @if(Auth::user()->hasPermission('manage_users'))
        <li><a class="dropdown-item {{ request()->routeIs('admin.nguoidung.*') ? 'active' : '' }}" href="{{ route('admin.nguoidung.index') }}">Quản lý người dùng</a></li>
        @endif
        @if(Auth::user()->hasPermission('manage_roles'))
        <li><a class="dropdown-item {{ request()->routeIs('admin.vaitro.*') ? 'active' : '' }}" href="{{ route('admin.vaitro.index') }}">Quản lý vai trò</a></li>
        <li><a class="dropdown-item {{ request()->routeIs('admin.quyen.*') ? 'active' : '' }}" href="{{ route('admin.quyen.index') }}">Quản lý danh sách quyền</a></li>
        @endif
        <li><a class="dropdown-item {{ request()->routeIs('admin.hosogiaovien.*') ? 'active' : '' }}" href="{{ route('admin.hosogiaovien.index') }}">Quản lý hồ sơ giảng viên</a></li>
        <li><a class="dropdown-item {{ request()->routeIs('admin.hosohocvien.*') ? 'active' : '' }}" href="{{ route('admin.hosohocvien.index') }}">Quản lý hồ sơ học viên</a></li>
        <li><a class="dropdown-item {{ request()->routeIs('admin.phancong.*') ? 'active' : '' }}" href="{{ route('admin.phancong.index') }}">Phân công giảng dạy</a></li>
        <li><a class="dropdown-item {{ request()->routeIs('admin.dangkykhoahoc.*') ? 'active' : '' }}" href="{{ route('admin.dangkykhoahoc.index') }}">Quản lý đăng ký khóa học</a></li>
        @if(Auth::user()->hasPermission('manage_invoices'))
        <li><a class="dropdown-item {{ request()->routeIs('admin.hoadon.*') ? 'active' : '' }}" href="{{ route('admin.hoadon.index') }}">Quản lý hóa đơn</a></li>
        @endif
        <li><a class="dropdown-item {{ request()->routeIs('admin.tiendo.*') ? 'active' : '' }}" href="{{ route('admin.tiendo.index') }}">Quản lý tiến độ</a></li>
        <li><a class="dropdown-item {{ request()->routeIs('admin.videos.*') ? 'active' : '' }}" href="{{ route('admin.videos.index') }}">Thư viện Video (HLS)</a></li>

        <li><a class="dropdown-item {{ request()->routeIs('admin.binhluan.*') ? 'active' : '' }}" href="{{ route('admin.binhluan.index') }}">Quản lý bình luận</a></li>
        <li><a class="dropdown-item {{ request()->routeIs('admin.thongbao.*') ? 'active' : '' }}" href="{{ route('admin.thongbao.index') }}">Quản lý thông báo</a></li>
        <li><a class="dropdown-item {{ request()->routeIs('admin.danhgia.*') ? 'active' : '' }}" href="{{ route('admin.danhgia.index') }}">Quản lý đánh giá</a></li>
      </ul>
    </li>
    <li class="nav-item">
      <a class="nav-link admin-nav-item {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}" href="{{ route('admin.banners.index') }}">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
        Banners
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link admin-nav-item {{ request()->routeIs('admin.tinhnang.*') ? 'active' : '' }}" href="{{ route('admin.tinhnang.index') }}">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
        Tính năng
      </a>
    </li>
    @if(Auth::user()->hasPermission('manage_settings'))
    <li class="nav-item">
      <a class="nav-link admin-nav-item {{ request()->routeIs('admin.caihinh.*') ? 'active' : '' }}" href="{{ route('admin.caihinh.index') }}">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 11-2.83 2.83l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 11-2.83-2.83l.06-.06A1.65 1.65 0 004.6 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 112.83-2.83l.06.06A1.65 1.65 0 009 4.6a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 112.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"></path></svg>
        Cài đặt
      </a>
    </li>
    @endif
  </ul>
