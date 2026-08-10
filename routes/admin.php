<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DanhMucKhoaHocController;
use App\Http\Controllers\Admin\NguoiDungController;
use App\Http\Controllers\Admin\VaiTroController;
use App\Http\Controllers\Admin\HoSoGiaoVienController;
use App\Http\Controllers\Admin\HoSoHocVienController;
use App\Http\Controllers\Admin\PhanCongController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CapDoHSKController;
use App\Http\Controllers\Admin\KhoaHocController;
use App\Http\Controllers\Admin\NguPhapController;
use App\Http\Controllers\Admin\HoiThoaiController;
use App\Http\Controllers\Admin\DeThiController;
use App\Http\Controllers\Admin\KetQuaLuyenThiController;
use App\Http\Controllers\Admin\KhoaHocLoiIchController;
use App\Http\Controllers\Admin\KhoaHocYeuCauController;
use App\Http\Controllers\Admin\BannerController;

// Admin Auth Routes
Route::get('admin/login', [AuthController::class, 'index'])->name('admin.login');
Route::post('admin/login', [AuthController::class, 'login']);
Route::post('admin/logout', [AuthController::class, 'logout'])->name('admin.logout');
Route::get('admin/logout', function() {
    // GET fallback — redirect về login thay vì báo 419
    if (\Illuminate\Support\Facades\Auth::check()) {
        \Illuminate\Support\Facades\Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }
    return redirect()->route('admin.login');
});

// Admin Session Routes (no rate-limit, just ping)
Route::post('admin/keepalive', function () {
    // Làm mới session để tránh bị đăng xuất do timeout phía server
    request()->session()->put('last_activity', time());
    return response()->json(['status' => 'ok']);
})->middleware(['web', 'auth'])->name('admin.keepalive');

Route::get('admin/session-check', function () {
    return response()->json(['authenticated' => auth()->check()]);
})->middleware(['web'])->name('admin.session.check');

// Admin Web Routes
Route::prefix('admin')->middleware(['web', 'auth', 'admin.teacher'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.home');
    
    // Quản lý Danh mục khóa học
    Route::resource('danhmuc', DanhMucKhoaHocController::class)->names('admin.danhmuc')->parameters([
        'danhmuc' => 'danhmuc'
    ]);

    // Quản lý Banners
    Route::resource('banners', BannerController::class)->names('admin.banners');

    // Quản lý Tính năng
    Route::resource('tinhnang', \App\Http\Controllers\Admin\TinhNangController::class)->names('admin.tinhnang')->parameters([
        'tinhnang' => 'tinhNang'
    ]);

    // Quản lý Cấp độ HSK
    Route::post('capdohsk/bulk-delete', [CapDoHSKController::class, 'bulkDelete'])->name('admin.capdohsk.bulkDelete');
    Route::post('capdohsk/import', [CapDoHSKController::class, 'import'])->name('admin.capdohsk.import');
    Route::resource('capdohsk', CapDoHSKController::class)->names('admin.capdohsk')->parameters([
        'capdohsk' => 'capdohsk'
    ]);

    // Quản lý Khóa học
    Route::resource('khoahoc', KhoaHocController::class)->names('admin.khoahoc')->parameters([
        'khoahoc' => 'khoahoc'
    ]);

    // Quản lý Chương học
    Route::post('chuonghoc/import', [\App\Http\Controllers\Admin\ChuongHocController::class, 'import'])->name('admin.chuonghoc.import');
    Route::resource('chuonghoc', \App\Http\Controllers\Admin\ChuongHocController::class)->names('admin.chuonghoc')->parameters([
        'chuonghoc' => 'chuonghoc'
    ]);
      Route::apiResource('khoa-hoc-loi-ich', KhoaHocLoiIchController::class)
        ->parameters(['khoa-hoc-loi-ich' => 'khoahocloiich'])
        ->names('admin.khoahocloiich');

    // Route riêng cho sắp xếp (reorder)
    Route::post('khoa-hoc-loi-ich/reorder', [KhoaHocLoiIchController::class, 'reorder'])
        ->name('admin.khoahocloiich.reorder');
    Route::post('khoa-hoc-loi-ich/import', [KhoaHocLoiIchController::class, 'import'])
        ->name('admin.khoahocloiich.import');

    // Route cho KhoaHocYeuCau
    Route::apiResource('khoa-hoc-yeu-cau', KhoaHocYeuCauController::class)
        ->parameters(['khoa-hoc-yeu-cau' => 'khoahocyeucau'])
        ->names('admin.khoahocyeucau');

    Route::post('khoa-hoc-yeu-cau/reorder', [KhoaHocYeuCauController::class, 'reorder'])
        ->name('admin.khoahocyeucau.reorder');
    Route::post('khoa-hoc-yeu-cau/import', [KhoaHocYeuCauController::class, 'import'])
        ->name('admin.khoahocyeucau.import');

    // Quản lý Bài học
    Route::put('baihoc/{baihoc}/video', [\App\Http\Controllers\Admin\BaiHocController::class, 'updateVideo'])->name('admin.baihoc.updateVideo');
    Route::put('baihoc/{baihoc}/condition', [\App\Http\Controllers\Admin\BaiHocController::class, 'updateCondition'])->name('admin.baihoc.updateCondition');
    Route::resource('baihoc', \App\Http\Controllers\Admin\BaiHocController::class)->names('admin.baihoc')->parameters([
        'baihoc' => 'baihoc'
    ]);

    // Quản lý Từ vựng
    Route::post('tuvung/import', [\App\Http\Controllers\Admin\TuVungController::class, 'import'])->name('admin.tuvung.import');
    Route::resource('tuvung', \App\Http\Controllers\Admin\TuVungController::class)->names('admin.tuvung')->parameters([
        'tuvung' => 'tuvung'
    ]);

    // Quản lý Lộ trình
    Route::post('lotrinh/{lotrinh}/giaidoan', [\App\Http\Controllers\Admin\LoTrinhController::class, 'storeGiaiDoan'])->name('admin.lotrinh.storeGiaiDoan');
    Route::put('lotrinh/{lotrinh}/giaidoan/{giaidoan}', [\App\Http\Controllers\Admin\LoTrinhController::class, 'updateGiaiDoan'])->name('admin.lotrinh.updateGiaiDoan');
    Route::delete('lotrinh/{lotrinh}/giaidoan/{giaidoan}', [\App\Http\Controllers\Admin\LoTrinhController::class, 'destroyGiaiDoan'])->name('admin.lotrinh.destroyGiaiDoan');
    
    Route::post('lotrinh/{lotrinh}/giaidoan/{giaidoan}/khoahoc', [\App\Http\Controllers\Admin\LoTrinhController::class, 'attachCourse'])->name('admin.lotrinh.attach');
    Route::delete('lotrinh/{lotrinh}/giaidoan/{giaidoan}/khoahoc/{khoahoc}', [\App\Http\Controllers\Admin\LoTrinhController::class, 'detachCourse'])->name('admin.lotrinh.detach');
    Route::resource('lotrinh', \App\Http\Controllers\Admin\LoTrinhController::class)->names('admin.lotrinh')->parameters([
        'lotrinh' => 'lotrinh'
    ]);

    // Quản lý Người dùng
    Route::resource('nguoidung', NguoiDungController::class)->middleware('permission:manage_users')->names('admin.nguoidung')->parameters([
        'nguoidung' => 'nguoidung'
    ]);

    // Quản lý Vai trò
    Route::resource('vaitro', VaiTroController::class)->middleware('permission:manage_roles')->names('admin.vaitro')->parameters([
        'vaitro' => 'vaitro'
    ]);

    // Quản lý Quyền
    Route::resource('quyen', \App\Http\Controllers\Admin\QuyenController::class)->middleware('permission:manage_roles')->names('admin.quyen')->except(['create', 'edit', 'show'])->parameters([
        'quyen' => 'quyen'
    ]);

    // Quản lý Hồ sơ giáo viên
    Route::post('hosogiaovien/import', [HoSoGiaoVienController::class, 'import'])->name('admin.hosogiaovien.import');
    Route::post('hosogiaovien/{id}/assign', [HoSoGiaoVienController::class, 'assign'])->name('admin.hosogiaovien.assign');
    Route::resource('hosogiaovien', HoSoGiaoVienController::class)->names('admin.hosogiaovien')->parameters([
        'hosogiaovien' => 'hosogiaovien'
    ]);

    // Quản lý Hồ sơ học viên
    Route::resource('hosohocvien', HoSoHocVienController::class)->names('admin.hosohocvien')->parameters([
        'hosohocvien' => 'hosohocvien'
    ]);

    // Quản lý Phân công giảng dạy
    Route::resource('phancong', PhanCongController::class)->names('admin.phancong')->parameters([
        'phancong' => 'phancong'
    ]);
    // Quản lý Ngữ pháp
    Route::post('nguphap/import', [\App\Http\Controllers\Admin\NguPhapController::class, 'import'])->name('admin.nguphap.import');
    Route::resource('nguphap', \App\Http\Controllers\Admin\NguPhapController::class)->names('admin.nguphap')->parameters([
        'nguphap' => 'nguphap'
    ]);

    // Quản lý Thư viện Video
    Route::get('videos/status', [\App\Http\Controllers\Admin\VideoController::class, 'getStatus'])->name('admin.videos.status');
    Route::post('videos/{video}/retry', [\App\Http\Controllers\Admin\VideoController::class, 'retry'])->name('admin.videos.retry');
    Route::post('videos/{video}/generate-vocab', [\App\Http\Controllers\Admin\VideoController::class, 'generateVocab'])->name('admin.videos.generateVocab');
    Route::resource('videos', \App\Http\Controllers\Admin\VideoController::class)->names('admin.videos')->only(['index', 'store', 'destroy']);


    // Quản lý Tiến độ Học tập
    Route::resource('tiendo', \App\Http\Controllers\Admin\TienDoController::class)->names('admin.tiendo')->only(['index', 'show']);

    // Quản lý Bình luận
    Route::post('binhluan/{binhluan}/reply', [\App\Http\Controllers\Admin\BinhLuanController::class, 'reply'])->name('admin.binhluan.reply');
    Route::resource('binhluan', \App\Http\Controllers\Admin\BinhLuanController::class)->names('admin.binhluan')->only(['index', 'update', 'destroy']);

    // Quản lý Đăng ký Khóa học
    Route::put('dangkykhoahoc/{id}/status', [\App\Http\Controllers\Admin\DangKyKhoaHocController::class, 'updateStatus'])->name('admin.dangkykhoahoc.status');
    Route::resource('dangkykhoahoc', \App\Http\Controllers\Admin\DangKyKhoaHocController::class)->names('admin.dangkykhoahoc')->only(['index', 'destroy']);

    // Quản lý Hóa đơn
    Route::resource('hoadon', \App\Http\Controllers\Admin\HoaDonController::class)->middleware('permission:manage_invoices')->names('admin.hoadon')->only(['index', 'update', 'destroy']);

    // Quản lý Thông báo
    Route::resource('thongbao', \App\Http\Controllers\Admin\ThongBaoController::class)->names('admin.thongbao');
    
    // Đánh giá
    Route::resource('danhgia', \App\Http\Controllers\Admin\DanhGiaController::class)->names('admin.danhgia')->parameters([
        'danhgia' => 'id'
    ]);

    // Cấu hình Hệ thống
    Route::get('caihinh', [\App\Http\Controllers\Admin\CauHinhController::class, 'index'])->middleware('permission:manage_settings')->name('admin.caihinh.index');
    Route::post('caihinh', [\App\Http\Controllers\Admin\CauHinhController::class, 'update'])->middleware('permission:manage_settings')->name('admin.caihinh.update');

    // Quản lý Hội Thoại
    Route::resource('hoithoai', \App\Http\Controllers\Admin\HoiThoaiController::class)->names('admin.hoithoai')->parameters([
        'hoithoai' => 'hoithoai'
    ]);
    Route::resource('chitiethoithoai', \App\Http\Controllers\Admin\ChiTietHoiThoaiController::class)->only(['store', 'update', 'destroy'])->names('admin.chitiethoithoai')->parameters([
        'chitiethoithoai' => 'chitiethoithoai'
    ]);
    Route::post('chitiethoithoai/import/{id_hoi_thoai}', [\App\Http\Controllers\Admin\ChiTietHoiThoaiController::class, 'importExcel'])->name('admin.chitiethoithoai.import');

    // Quản lý Luyện Viết
    Route::resource('luyenviet', \App\Http\Controllers\Admin\LuyenVietController::class)->names('admin.luyenviet')->parameters([
        'luyenviet' => 'luyenviet'
    ]);

    // Quản lý Loại Câu Hỏi
    Route::post('loaicauhoi/bulk-delete', [App\Http\Controllers\Admin\LoaiCauHoiController::class, 'bulkDelete'])->name('admin.loaicauhoi.bulkDelete');
    Route::post('loaicauhoi/import', [App\Http\Controllers\Admin\LoaiCauHoiController::class, 'import'])->name('admin.loaicauhoi.import');
    Route::resource('loaicauhoi', App\Http\Controllers\Admin\LoaiCauHoiController::class)->names('admin.loaicauhoi')->parameters([
        'loaicauhoi' => 'loaicauhoi'
    ]);

    // Quản lý Câu hỏi
    Route::post('cauhoi/import', [\App\Http\Controllers\Admin\CauHoiController::class, 'import'])->name('admin.cauhoi.import');
    Route::resource('cauhoi', \App\Http\Controllers\Admin\CauHoiController::class)->names('admin.cauhoi')->parameters([
        'cauhoi' => 'cauhoi'
    ]);

    // Quản lý Mức độ Câu hỏi
    Route::resource('mucdocauhoi', \App\Http\Controllers\Admin\MucDoController::class)->names('admin.mucdocauhoi')->parameters([
        'mucdocauhoi' => 'mucdo'
    ]);

    // Quản lý Đề thi
    Route::get('dethi/{dethi}/questions', [DeThiController::class, 'getQuestions'])->name('admin.dethi.questions');
    Route::post('dethi/{dethi}/questions/attach', [DeThiController::class, 'attachQuestion'])->name('admin.dethi.questions.attach');
    Route::post('dethi/{dethi}/questions/detach', [DeThiController::class, 'detachQuestion'])->name('admin.dethi.questions.detach');
    Route::post('dethi/{dethi}/questions/reorder', [DeThiController::class, 'reorderQuestions'])->name('admin.dethi.questions.reorder');
    Route::resource('dethi', DeThiController::class)->names('admin.dethi')->parameters([
        'dethi' => 'dethi'
    ]);

    // Quản lý Kết quả Luyện thi
    Route::resource('ketqua', KetQuaLuyenThiController::class)->names('admin.ketqua')->only(['index', 'show', 'destroy']);
});
