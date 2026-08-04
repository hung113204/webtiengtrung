<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoaiCauHoiRequest;
use App\Http\Requests\ImportExcelRequest;
use App\Models\LoaiCauHoi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LoaiCauHoiController extends Controller
{
    public function index()
    {
        $loaiCauHois = LoaiCauHoi::orderBy('thu_tu')->orderBy('id', 'desc')->paginate(10);
        return view('admin.loaicauhoi.index', compact('loaiCauHois'));
    }

    public function create()
    {
        return view('admin.loaicauhoi.create');
    }

    public function store(LoaiCauHoiRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = !empty($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['ten_loai']);

        LoaiCauHoi::create($data);

        return redirect()->route('admin.loaicauhoi.index')->with('success', 'Thêm loại câu hỏi thành công!');
    }

    public function edit($id)
    {
        $loaiCauHoi = LoaiCauHoi::findOrFail($id);
        return view('admin.loaicauhoi.edit', compact('loaiCauHoi'));
    }

    public function update(LoaiCauHoiRequest $request, $id)
    {
        $loaiCauHoi = LoaiCauHoi::findOrFail($id);
        
        $data = $request->validated();
        $data['slug'] = !empty($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['ten_loai']);

        $loaiCauHoi->update($data);

        return redirect()->route('admin.loaicauhoi.index')->with('success', 'Cập nhật loại câu hỏi thành công!');
    }

    public function destroy($id, Request $request)
    {
        try {
            $loaiCauHoi = LoaiCauHoi::findOrFail($id);
            $loaiCauHoi->delete();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Xóa loại câu hỏi thành công!']);
            }

            return redirect()->route('admin.loaicauhoi.index')->with('success', 'Xóa loại câu hỏi thành công!');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Lỗi khi xóa: ' . $e->getMessage()], 500);
            }
            return redirect()->route('admin.loaicauhoi.index')->with('error', 'Lỗi khi xóa: ' . $e->getMessage());
        }
    }

    /**
     * Xóa nhiều loại câu hỏi (Bulk Delete) bằng Ajax
     */
    public function bulkDelete(Request $request)
    {
        try {
            $ids = $request->input('ids');
            if (empty($ids) || !is_array($ids)) {
                return response()->json(['success' => false, 'message' => 'Vui lòng chọn ít nhất một mục để xóa.']);
            }

            LoaiCauHoi::whereIn('id', $ids)->delete();

            return response()->json(['success' => true, 'message' => 'Đã xóa ' . count($ids) . ' mục thành công!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Lỗi khi xóa: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Nhập dữ liệu từ file Excel
     */
    public function import(ImportExcelRequest $request)
    {
        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\LoaiCauHoiImport, $request->file('file'));
            return redirect()->route('admin.loaicauhoi.index')->with('success', 'Nhập dữ liệu Loại câu hỏi từ Excel thành công!');
        } catch (\Exception $e) {
            return redirect()->route('admin.loaicauhoi.index')->with('error', 'Lỗi khi nhập Excel: ' . $e->getMessage());
        }
    }
}
