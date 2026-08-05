<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChuongHoc;
use App\Models\KhoaHoc;
use App\Http\Requests\Admin\ChuongHocRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\ImportExcelRequest;
use App\Imports\ChuongHocImport;
use Maatwebsite\Excel\Facades\Excel;

class ChuongHocController extends Controller
{
    public function index(Request $request)
    {
        $khoaHocs = KhoaHoc::all();
        
        $query = ChuongHoc::with('khoaHoc')->withCount('baiHocs');
        
        if ($request->filled('id_khoa_hoc')) {
            $query->where('id_khoa_hoc', $request->id_khoa_hoc);
        }
        
        if ($request->filled('search')) {
            $query->where('ten_chuong', 'like', '%' . $request->search . '%');
        }
        
        $chuongHocs = $query->orderBy('thu_tu', 'asc')->get();

        return view('admin.chuonghoc.index', compact('chuongHocs', 'khoaHocs'));
    }

    public function store(ChuongHocRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['ten_chuong']);
        
        ChuongHoc::create($data);

        return redirect()->route('admin.chuonghoc.index')->with('success', 'Thêm chương học thành công!');
    }

    public function update(ChuongHocRequest $request, $id)
    {
        $chuongHoc = ChuongHoc::findOrFail($id);
        $data = $request->validated();
        $data['slug'] = Str::slug($data['ten_chuong']);

        $chuongHoc->update($data);

        return redirect()->route('admin.chuonghoc.index')->with('success', 'Cập nhật chương học thành công!');
    }

    public function destroy($id)
    {
        $chuongHoc = ChuongHoc::findOrFail($id);
        $chuongHoc->delete();

        return redirect()->route('admin.chuonghoc.index')->with('success', 'Xóa chương học thành công!');
    }

    public function import(ImportExcelRequest $request)
    {
        try {
            if (!$request->has('id_khoa_hoc') || empty($request->id_khoa_hoc)) {
                return back()->with('error', 'Vui lòng chọn khóa học để nhập chương.');
            }

            Excel::import(new ChuongHocImport($request->id_khoa_hoc), $request->file('file'));
            return redirect()->route('admin.chuonghoc.index')->with('success', 'Nhập dữ liệu chương học từ Excel thành công!');
        } catch (\Exception $e) {
            return redirect()->route('admin.chuonghoc.index')->with('error', 'Lỗi khi nhập Excel: ' . $e->getMessage());
        }
    }
}
