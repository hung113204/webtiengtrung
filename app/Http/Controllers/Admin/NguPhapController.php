<?php

namespace App\Http\Controllers\Admin;

use App\Models\NguPhap;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\NguPhapRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\NguPhapImport;

class NguPhapController extends Controller
{
    public function index()
    {
        $nguPhaps = NguPhap::with('baiHoc.chuongHoc.khoaHoc')->orderBy('id', 'desc')->paginate(20);
        $baiHocs = \App\Models\BaiHoc::all();
        return view('admin.nguphap.index', compact('nguPhaps', 'baiHocs'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'id_bai_hoc' => 'nullable|exists:bai_hoc,id',
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ], [
            'excel_file.required' => 'Vui lòng tải lên file Excel.',
            'excel_file.mimes' => 'Chỉ chấp nhận file định dạng .xlsx, .xls, hoặc .csv.',
        ]);

        try {
            $import = new NguPhapImport($request->id_bai_hoc);
            Excel::import($import, $request->file('excel_file'));

            $imported = $import->getImportedCount();
            $duplicates = $import->getDuplicateCount();

            $message = "Nhập thành công {$imported} điểm ngữ pháp!";
            if ($duplicates > 0) {
                $message .= " Đã bỏ qua {$duplicates} điểm ngữ pháp bị trùng lặp.";
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['excel_file' => 'Lỗi khi nhập file: ' . $e->getMessage()]);
        }
    }

    public function store(NguPhapRequest $request)
    {
        $data = $request->validated();
        
        NguPhap::create($data);

        return redirect()->back()->with('success', 'Thêm điểm ngữ pháp thành công!');
    }

    public function update(NguPhapRequest $request, $id)
    {
        $nguPhap = NguPhap::findOrFail($id);
        $data = $request->validated();

        $nguPhap->update($data);

        return redirect()->back()->with('success', 'Cập nhật điểm ngữ pháp thành công!');
    }

    public function destroy($id)
    {
        $nguPhap = NguPhap::findOrFail($id);
        
        $nguPhap->delete();

        return redirect()->back()->with('success', 'Xóa điểm ngữ pháp thành công!');
    }
}
