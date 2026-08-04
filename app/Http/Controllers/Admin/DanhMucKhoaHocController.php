<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DanhMucKhoaHoc;
use App\Http\Requests\StoreDanhMucKhoaHocRequest;
use App\Http\Requests\UpdateDanhMucKhoaHocRequest;

class DanhMucKhoaHocController extends Controller
{
    /**
     * Hiển thị danh sách danh mục dạng cây cha-con.
     */
    public function index()
    {
        // Load danh mục gốc kèm con (eager load 2 cấp)
        $danhMucs = DanhMucKhoaHoc::roots()
            ->with(['children' => function ($q) {
                $q->withCount('khoaHocs')->orderBy('thu_tu');
            }])
            ->withCount('khoaHocs')
            ->orderBy('thu_tu')
            ->get();

        // Danh sách cha để dùng trong dropdown modal
        $danhMucRoots = DanhMucKhoaHoc::roots()->orderBy('thu_tu')->get(['id', 'ten_danh_muc']);

        return view('admin.danhmuckhoahoc.index', compact('danhMucs', 'danhMucRoots'));
    }

    /**
     * Lưu danh mục mới vào Database.
     */
    public function store(StoreDanhMucKhoaHocRequest $request)
    {
        $danhmuc = DanhMucKhoaHoc::create($request->validated());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thêm danh mục thành công!',
                'data'    => $danhmuc,
            ]);
        }

        return redirect()->route('admin.danhmuc.index')->with('success', 'Thêm danh mục thành công!');
    }

    /**
     * Cập nhật danh mục trong Database.
     */
    public function update(UpdateDanhMucKhoaHocRequest $request, DanhMucKhoaHoc $danhmuc)
    {
        $danhmuc->update($request->validated());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật danh mục thành công!',
                'data'    => $danhmuc,
            ]);
        }

        return redirect()->route('admin.danhmuc.index')->with('success', 'Cập nhật danh mục thành công!');
    }

    /**
     * Xóa danh mục khỏi Database.
     */
    public function destroy(Request $request, DanhMucKhoaHoc $danhmuc)
    {
        // Các con sẽ tự động set parent_id = null (do onDelete('set null'))
        $danhmuc->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Xóa danh mục thành công!',
            ]);
        }

        return redirect()->route('admin.danhmuc.index')->with('success', 'Xóa danh mục thành công!');
    }
}
