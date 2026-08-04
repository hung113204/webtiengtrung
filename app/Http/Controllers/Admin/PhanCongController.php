<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PhanCongGiangDay;
use App\Models\HoSoGiaoVien;
use App\Models\KhoaHoc;
use Illuminate\Http\Request;

class PhanCongController extends Controller
{
    public function index(Request $request)
    {
        $query = PhanCongGiangDay::with(['giaoVien.nguoiDung', 'khoaHoc']);

        // Search filter
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->whereHas('giaoVien.nguoiDung', function($sub) use ($keyword) {
                    $sub->where('ho_ten', 'LIKE', "%{$keyword}%")
                        ->orWhere('email', 'LIKE', "%{$keyword}%");
                })->orWhereHas('khoaHoc', function($sub) use ($keyword) {
                    $sub->where('ten_khoa_hoc', 'LIKE', "%{$keyword}%");
                });
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('vai_tro_giang_day', $request->role);
        }

        $phanCongs = $query->orderBy('id', 'desc')->paginate(10);

        // Fetch teachers and courses for create/edit select boxes
        $giaoViens = HoSoGiaoVien::with('nguoiDung')->get();
        $khoaHocs = KhoaHoc::where('trang_thai', true)->orderBy('ten_khoa_hoc')->get();

        return view('admin.phancong.index', compact('phanCongs', 'giaoViens', 'khoaHocs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_giao_vien' => 'required|exists:ho_so_giao_vien,id',
            'id_khoa_hoc' => 'required|exists:khoa_hoc,id',
            'vai_tro_giang_day' => 'required|string',
        ], [
            'id_giao_vien.required' => 'Vui lòng chọn giảng viên.',
            'id_khoa_hoc.required' => 'Vui lòng chọn khóa học.',
            'vai_tro_giang_day.required' => 'Vui lòng chọn vai trò.',
        ]);

        // Check duplicate
        $exists = PhanCongGiangDay::where('id_giao_vien', $request->id_giao_vien)
            ->where('id_khoa_hoc', $request->id_khoa_hoc)
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['id_khoa_hoc' => 'Giảng viên này đã được phân công dạy khóa học này rồi.']);
        }

        PhanCongGiangDay::create([
            'id_giao_vien' => $request->id_giao_vien,
            'id_khoa_hoc' => $request->id_khoa_hoc,
            'vai_tro_giang_day' => $request->vai_tro_giang_day,
            'ngay_phan_cong' => now(),
        ]);

        return redirect()->route('admin.phancong.index')->with('success', 'Phân công giảng dạy thành công!');
    }

    public function update(Request $request, string $id)
    {
        $phanCong = PhanCongGiangDay::findOrFail($id);

        $request->validate([
            'vai_tro_giang_day' => 'required|string',
        ]);

        $phanCong->update([
            'vai_tro_giang_day' => $request->vai_tro_giang_day,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật phân công giảng dạy thành công!'
            ]);
        }

        return redirect()->route('admin.phancong.index')->with('success', 'Cập nhật phân công thành công!');
    }

    public function destroy(Request $request, string $id)
    {
        PhanCongGiangDay::findOrFail($id)->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa phân công giảng dạy!'
            ]);
        }

        return redirect()->route('admin.phancong.index')->with('success', 'Xóa phân công thành công!');
    }
}
