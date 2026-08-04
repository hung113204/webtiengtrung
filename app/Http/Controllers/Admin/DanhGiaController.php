<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DanhGia;
use App\Models\NguoiDung;
use App\Models\KhoaHoc;
use Illuminate\Http\Request;

class DanhGiaController extends Controller
{
    public function index()
    {
        $danhsach = DanhGia::with(['nguoiDung', 'khoaHoc'])->orderBy('id', 'desc')->paginate(10);
        $nguoiDungs = NguoiDung::all();
        $khoaHocs = KhoaHoc::all();
        return view('admin.danhgia.index', compact('danhsach', 'nguoiDungs', 'khoaHocs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_nguoi_dung' => 'required|exists:nguoi_dung,id',
            'id_khoa_hoc' => 'required|exists:khoa_hoc,id',
            'so_sao' => 'required|integer|min:1|max:5',
            'tieu_de' => 'nullable|string|max:255',
            'noi_dung' => 'required|string',
            'uu_diem' => 'nullable|string',
            'nhuoc_diem' => 'nullable|string'
        ]);
        
        $validated['trang_thai'] = $request->has('trang_thai');

        DanhGia::create($validated);
        return redirect()->route('admin.danhgia.index')->with('success', 'Thêm đánh giá thành công!');
    }

    public function update(Request $request, $id)
    {
        $danhgia = DanhGia::findOrFail($id);
        $validated = $request->validate([
            'id_nguoi_dung' => 'required|exists:nguoi_dung,id',
            'id_khoa_hoc' => 'required|exists:khoa_hoc,id',
            'so_sao' => 'required|integer|min:1|max:5',
            'tieu_de' => 'nullable|string|max:255',
            'noi_dung' => 'required|string',
            'uu_diem' => 'nullable|string',
            'nhuoc_diem' => 'nullable|string'
        ]);

        $validated['trang_thai'] = $request->has('trang_thai');

        $danhgia->update($validated);
        return redirect()->route('admin.danhgia.index')->with('success', 'Cập nhật đánh giá thành công!');
    }

    public function destroy($id)
    {
        $danhgia = DanhGia::findOrFail($id);
        $danhgia->delete();
        return redirect()->route('admin.danhgia.index')->with('success', 'Xóa đánh giá thành công!');
    }
}
