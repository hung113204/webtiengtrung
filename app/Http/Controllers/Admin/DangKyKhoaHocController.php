<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DangKyKhoaHoc;
use Illuminate\Http\Request;

class DangKyKhoaHocController extends Controller
{
    /**
     * Danh sách đăng ký khóa học
     */
    public function index(Request $request)
    {
        $query = DangKyKhoaHoc::with(['nguoiDung', 'khoaHoc', 'hoaDon'])->latest();

        // Tìm kiếm theo tên học viên, email hoặc tên khóa học
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('nguoiDung', function($userQuery) use ($search) {
                    $userQuery->where('ho_ten', 'like', "%{$search}%")
                              ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('khoaHoc', function($courseQuery) use ($search) {
                    $courseQuery->where('ten_khoa_hoc', 'like', "%{$search}%");
                });
            });
        }

        // Lọc theo trạng thái
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        $registrations = $query->paginate(15)->withQueryString();

        return view('admin.dangkykhoahoc.index', compact('registrations'));
    }

    /**
     * Cập nhật trạng thái đăng ký (Phê duyệt / Hủy)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'trang_thai' => 'required|in:Chờ duyệt,Đã duyệt,Đã hủy',
        ]);

        $registration = DangKyKhoaHoc::findOrFail($id);
        $registration->update([
            'trang_thai' => $request->trang_thai,
        ]);

        // Đồng bộ hóa hóa đơn tương ứng
        $hoaDon = \App\Models\HoaDon::firstOrCreate(
            ['id_dang_ky' => $registration->id],
            [
                'ma_hoa_don' => 'HD' . str_pad($registration->id, 6, '0', STR_PAD_LEFT),
                'id_nguoi_dung' => $registration->id_nguoi_dung,
                'so_tien' => $registration->khoaHoc ? ($registration->khoaHoc->gia_giam ?? $registration->khoaHoc->gia ?? 0) : 0,
                'phuong_thuc_thanh_toan' => 'Chuyển khoản',
            ]
        );

        if ($request->trang_thai === 'Đã duyệt') {
            $hoaDon->update([
                'trang_thai' => 'Đã thanh toán',
                'ngay_thanh_toan' => now(),
            ]);
        } elseif ($request->trang_thai === 'Đã hủy') {
            $hoaDon->update([
                'trang_thai' => 'Đã hủy',
                'ngay_thanh_toan' => null,
            ]);
        } else {
            $hoaDon->update([
                'trang_thai' => 'Chưa thanh toán',
                'ngay_thanh_toan' => null,
            ]);
        }

        return redirect()->back()->with('success', 'Cập nhật trạng thái đăng ký thành công!');
    }

    /**
     * Xóa lượt đăng ký khóa học
     */
    public function destroy($id)
    {
        $registration = DangKyKhoaHoc::findOrFail($id);
        $registration->delete();

        return redirect()->back()->with('success', 'Xóa lượt đăng ký khóa học thành công!');
    }
}
