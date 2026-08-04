<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HoSoGiaoVien;
use App\Models\NguoiDung;
use App\Http\Requests\HoSoGiaoVienRequest;
use Illuminate\Http\Request;

class HoSoGiaoVienController extends Controller
{
    public function index()
    {
        // Lấy danh sách hồ sơ kèm theo thông tin Người dùng và Khóa học phụ trách
        $hosos = HoSoGiaoVien::with(['nguoiDung', 'khoaHocs'])->orderBy('id', 'desc')->get();
        // Lấy danh sách những người có vai trò Giảng viên nhưng chưa có hồ sơ
        $giaoViens = NguoiDung::whereHas('vaiTro', function($q) {
                $q->whereIn('ten_vai_tro', ['Giảng viên', 'Giáo viên']);
            })
            ->whereDoesntHave('hoSoGiaoVien')
            ->get();

        // Lấy danh sách tất cả khóa học để phân công
        $khoaHocs = \App\Models\KhoaHoc::where('trang_thai', true)->orderBy('ten_khoa_hoc')->get();

        return view('admin.hosogiaovien.index', compact('hosos', 'giaoViens', 'khoaHocs'));
    }

    public function store(HoSoGiaoVienRequest $request)
    {
        $hoso = HoSoGiaoVien::create($request->validated());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thêm hồ sơ giáo viên thành công!'
            ]);
        }
        return redirect()->back()->with('success', 'Thêm hồ sơ giáo viên thành công!');
    }

    public function update(HoSoGiaoVienRequest $request, string $id)
    {
        $hoso = HoSoGiaoVien::findOrFail($id);
        $hoso->update($request->validated());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật hồ sơ thành công!'
            ]);
        }
        return redirect()->back()->with('success', 'Cập nhật hồ sơ thành công!');
    }

    public function destroy(Request $request, string $id)
    {
        HoSoGiaoVien::findOrFail($id)->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa hồ sơ giáo viên!'
            ]);
        }
        return redirect()->back()->with('success', 'Xóa hồ sơ thành công!');
    }

    /**
     * Phân công khóa học cho giáo viên giảng dạy
     */
    public function assign(Request $request, string $id)
    {
        $hoso = HoSoGiaoVien::findOrFail($id);
        
        $courseIds = $request->input('khoa_hoc_ids', []);
        
        $syncData = [];
        foreach ($courseIds as $courseId) {
            $syncData[$courseId] = [
                'vai_tro_giang_day' => $request->input('vai_tro_giang_day_' . $courseId, 'Giảng viên chính'),
                'ngay_phan_cong' => now(),
            ];
        }
        
        $hoso->khoaHocs()->sync($syncData);
        
        return redirect()->back()->with('success', 'Phân công khóa học giảng dạy thành công!');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240'
        ], [
            'file.required' => 'Vui lòng chọn file.',
            'file.mimes' => 'File phải là định dạng Excel (xlsx, xls) hoặc csv.',
            'file.max' => 'Dung lượng file không được vượt quá 10MB.'
        ]);

        try {
            $import = new \App\Imports\GiaoVienImport();
            \Maatwebsite\Excel\Facades\Excel::import($import, $request->file('file'));
            
            $imported = $import->getImportedCount();
            $duplicates = $import->getDuplicateCount();

            $msg = "Đã import thành công $imported giáo viên.";
            if ($duplicates > 0) {
                $msg .= " Bỏ qua $duplicates giáo viên đã có hồ sơ.";
            }

            return redirect()->back()->with('success', $msg);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra trong quá trình import: ' . $e->getMessage());
        }
    }
}
