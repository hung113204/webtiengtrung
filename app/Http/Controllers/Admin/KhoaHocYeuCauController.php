<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KhoaHoc;
use App\Models\KhoaHocYeuCau;
use App\Http\Requests\Admin\KhoaHocYeuCauRequest;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\KhoaHocYeuCauImport;

class KhoaHocYeuCauController extends Controller
{
    /**
     * Danh sách yêu cầu, hỗ trợ lọc theo khóa học.
     */
    public function index(Request $request)
    {
        $query = KhoaHocYeuCau::with('khoaHoc')
            ->orderBy('thu_tu')
            ->orderBy('id', 'desc');

        if ($request->filled('khoa_hoc_id')) {
            $query->where('khoa_hoc_id', $request->khoa_hoc_id);
        }

        $danhsach = $query->paginate(20)->withQueryString();
        $khoaHocs = KhoaHoc::orderBy('ten_khoa_hoc')->get();

        return view('admin.khoahocyeucau.index', compact('danhsach', 'khoaHocs'));
    }

    /**
     * Form thêm mới yêu cầu.
     */
    public function create()
    {
        $khoaHocs = KhoaHoc::orderBy('ten_khoa_hoc')->get();
        $yeuCau = new KhoaHocYeuCau();
        return view('admin.khoahocyeucau.create', compact('khoaHocs', 'yeuCau'));
    }

    /**
     * Lưu yêu cầu mới vào database.
     */
    public function store(KhoaHocYeuCauRequest $request)
    {
        $data = $request->validated();

        if (empty($data['thu_tu'])) {
            $max = KhoaHocYeuCau::where('khoa_hoc_id', $data['khoa_hoc_id'])->max('thu_tu') ?? 0;
            $data['thu_tu'] = $max + 1;
        }

        KhoaHocYeuCau::create($data);

        return redirect()->route('admin.khoahocyeucau.index')
            ->with('success', 'Thêm yêu cầu thành công!');
    }

    /**
     * Xem chi tiết một yêu cầu.
     */
    public function show(KhoaHocYeuCau $khoahocyeucau)
    {
        $khoahocyeucau->load('khoaHoc');
        return view('admin.khoahocyeucau.show', compact('khoahocyeucau'));
    }

    /**
     * Form chỉnh sửa yêu cầu.
     */
    public function edit(KhoaHocYeuCau $khoahocyeucau)
    {
        $khoaHocs = KhoaHoc::orderBy('ten_khoa_hoc')->get();
        return view('admin.khoahocyeucau.edit', compact('khoahocyeucau', 'khoaHocs'));
    }

    /**
     * Cập nhật yêu cầu.
     */
    public function update(KhoaHocYeuCauRequest $request, KhoaHocYeuCau $khoahocyeucau)
    {
        $data = $request->validated();
        $khoahocyeucau->update($data);

        return redirect()->route('admin.khoahocyeucau.index')
            ->with('success', 'Cập nhật yêu cầu thành công!');
    }

    /**
     * Xóa yêu cầu.
     */
    public function destroy(KhoaHocYeuCau $khoahocyeucau)
    {
        $khoahocyeucau->delete();

        return redirect()->route('admin.khoahocyeucau.index')
            ->with('success', 'Xóa yêu cầu thành công!');
    }

    /**
     * Cập nhật thứ tự (dùng cho drag‑drop).
     * Nhận payload: { items: [ {id, thu_tu}, ... ] }
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'items' => ['required', 'array'],
            'items.*.id' => ['required', 'integer', 'exists:khoa_hoc_yeu_cau,id'],
            'items.*.thu_tu' => ['required', 'integer', 'min:0'],
        ]);

        foreach ($request->items as $item) {
            KhoaHocYeuCau::where('id', $item['id'])->update(['thu_tu' => $item['thu_tu']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Sắp xếp thứ tự thành công!',
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'khoa_hoc_id' => 'required|exists:khoa_hoc,id',
            'file' => 'required|mimes:xlsx,xls,csv|max:5120', // 5MB
        ]);

        $import = new KhoaHocYeuCauImport($request->khoa_hoc_id);
        Excel::import($import, $request->file('file'));

        $message = sprintf(
            'Đã import thành công %d yêu cầu, bỏ qua %d bản ghi trùng lặp.',
            $import->getImportedCount(),
            $import->getDuplicateCount()
        );

        return redirect()->route('admin.khoahocyeucau.index')
                         ->with('success', $message);
    }
}