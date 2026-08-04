<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HoSoHocVien;
use App\Models\NguoiDung;
use Illuminate\Http\Request;

class HoSoHocVienController extends Controller
{
    /**
     * Hiển thị danh sách hồ sơ học viên
     */
    public function index()
    {
        $hosos = HoSoHocVien::with('nguoiDung')->orderBy('id', 'desc')->get();
        
        // Lấy những học viên (role = Học viên, null hoặc chữ chứa 'học viên') chưa có hồ sơ
        $hocViens = NguoiDung::where(function($query) {
            $query->whereDoesntHave('vaiTro')
                  ->orWhereHas('vaiTro', function($q) {
                      $q->where('ten_vai_tro', 'like', '%học viên%')
                        ->orWhere('ten_vai_tro', 'like', '%hoc vien%');
                  });
        })->whereDoesntHave('hoSoHocVien')->orderBy('ho_ten')->get();

        return view('admin.hosohocvien.index', compact('hosos', 'hocViens'));
    }

    /**
     * Lưu hồ sơ học viên mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_nguoi_dung' => 'required|exists:nguoi_dung,id|unique:ho_so_hoc_vien,id_nguoi_dung',
            'trinh_do_hien_tai' => 'nullable|string|max:255',
            'muc_tieu_hoc_tap' => 'nullable|string|max:255',
            'thoi_gian_hoc_du_kien' => 'nullable|string|max:255',
        ], [
            'id_nguoi_dung.required' => 'Vui lòng chọn học viên.',
            'id_nguoi_dung.unique' => 'Học viên này đã có hồ sơ học tập.',
        ]);

        HoSoHocVien::create($request->all());

        return redirect()->route('admin.hosohocvien.index')->with('success', 'Thêm hồ sơ học viên thành công!');
    }

    /**
     * Cập nhật hồ sơ học viên
     */
    public function update(Request $request, $id)
    {
        $hoso = HoSoHocVien::findOrFail($id);

        $request->validate([
            'trinh_do_hien_tai' => 'nullable|string|max:255',
            'muc_tieu_hoc_tap' => 'nullable|string|max:255',
            'thoi_gian_hoc_du_kien' => 'nullable|string|max:255',
        ]);

        $hoso->update($request->all());

        return redirect()->route('admin.hosohocvien.index')->with('success', 'Cập nhật hồ sơ học viên thành công!');
    }

    /**
     * Xóa hồ sơ học viên
     */
    public function destroy(Request $request, $id)
    {
        $hoso = HoSoHocVien::findOrFail($id);
        $hoso->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa hồ sơ học viên thành công!'
            ]);
        }

        return redirect()->route('admin.hosohocvien.index')->with('success', 'Xóa hồ sơ học viên thành công!');
    }
}
