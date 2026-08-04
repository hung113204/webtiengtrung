<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChiTietHoiThoai;
use App\Models\HoiThoai;
use App\Http\Requests\ChiTietHoiThoai\StoreChiTietHoiThoaiRequest;
use App\Http\Requests\ChiTietHoiThoai\UpdateChiTietHoiThoaiRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChiTietHoiThoaiController extends Controller
{
    public function store(StoreChiTietHoiThoaiRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('am_thanh')) {
            $data['am_thanh'] = $request->file('am_thanh')->store('hoi_thoai/audio', 'public');
        }

        ChiTietHoiThoai::create($data);

        return back()->with('success', 'Đã thêm câu thoại thành công!');
    }

    public function update(UpdateChiTietHoiThoaiRequest $request, string $id)
    {
        $chiTiet = ChiTietHoiThoai::findOrFail($id);
        $data = $request->validated();

        if ($request->hasFile('am_thanh')) {
            if ($chiTiet->am_thanh) {
                Storage::disk('public')->delete($chiTiet->am_thanh);
            }
            $data['am_thanh'] = $request->file('am_thanh')->store('hoi_thoai/audio', 'public');
        }

        $chiTiet->update($data);

        return back()->with('success', 'Đã cập nhật câu thoại!');
    }

    public function destroy(string $id)
    {
        $chiTiet = ChiTietHoiThoai::findOrFail($id);

        if ($chiTiet->am_thanh) {
            Storage::disk('public')->delete($chiTiet->am_thanh);
        }

        $chiTiet->delete();

        return back()->with('success', 'Đã xóa câu thoại!');
    }

    public function importExcel(Request $request, string $id_hoi_thoai)
    {
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            $import = new \App\Imports\ChiTietHoiThoaiImport($id_hoi_thoai);
            \Maatwebsite\Excel\Facades\Excel::import($import, $request->file('file_excel'));

            $imported = $import->getImportedCount();
            $duplicates = $import->getDuplicateCount();

            $msg = "Đã nhập thành công {$imported} câu thoại.";
            if ($duplicates > 0) {
                $msg .= " Bỏ qua {$duplicates} câu trùng lặp.";
            }

            return back()->with('success', $msg);
        } catch (\Exception $e) {
            return back()->withErrors(['file_excel' => 'Lỗi khi nhập file: ' . $e->getMessage()]);
        }
    }
}
