<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KhoaHoc;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\KhoaHocLoiIch;
use App\Http\Requests\Admin\KhoaHocLoiIchRequest;
use Illuminate\Http\Request;
use App\Imports\KhoaHocLoiIchImport; 

class KhoaHocLoiIchController extends Controller
{
    /**
     * Danh sách lợi ích, hỗ trợ lọc theo khóa học.
     */
    public function index(Request $request)
    {
        $query = KhoaHocLoiIch::with('khoaHoc')
            ->orderBy('thu_tu')
            ->orderBy('id', 'desc');

        if ($request->filled('khoa_hoc_id')) {
            $query->where('khoa_hoc_id', $request->khoa_hoc_id);
        }

        $danhsach = $query->paginate(20)->withQueryString();
        $khoaHocs = KhoaHoc::orderBy('ten_khoa_hoc')->get();

        return view('admin.khoahocloiich.index', compact('danhsach', 'khoaHocs'));
    }

    /**
     * Form thêm mới lợi ích.
     */
    public function create()
    {
        $khoaHocs = KhoaHoc::orderBy('ten_khoa_hoc')->get();
        $loiIch = new KhoaHocLoiIch();
        return view('admin.khoahocloiich.create', compact('khoaHocs', 'loiIch'));
    }

    /**
     * Lưu lợi ích mới vào database.
     */
    public function store(KhoaHocLoiIchRequest $request)
    {
        $data = $request->validated();

        // Nếu không có thứ tự, tự động gán max+1 trong cùng khóa học
        if (empty($data['thu_tu'])) {
            $max = KhoaHocLoiIch::where('khoa_hoc_id', $data['khoa_hoc_id'])->max('thu_tu') ?? 0;
            $data['thu_tu'] = $max + 1;
        }

        KhoaHocLoiIch::create($data);

        return redirect()->route('admin.khoahocloiich.index')
            ->with('success', 'Thêm lợi ích thành công!');
    }

    /**
     * Xem chi tiết một lợi ích.
     */
    public function show(KhoaHocLoiIch $khoahocloiich)
    {
        $khoahocloiich->load('khoaHoc');
        return view('admin.khoahocloiich.show', compact('khoahocloiich'));
    }

    /**
     * Form chỉnh sửa lợi ích.
     */
    public function edit(KhoaHocLoiIch $khoahocloiich)
    {
        $khoaHocs = KhoaHoc::orderBy('ten_khoa_hoc')->get();
        return view('admin.khoahocloiich.edit', compact('khoahocloiich', 'khoaHocs'));
    }

    /**
     * Cập nhật lợi ích.
     */
    public function update(KhoaHocLoiIchRequest $request, KhoaHocLoiIch $khoahocloiich)
    {
        $data = $request->validated();
        $khoahocloiich->update($data);

        return redirect()->route('admin.khoahocloiich.index')
            ->with('success', 'Cập nhật lợi ích thành công!');
    }

    /**
     * Xóa lợi ích.
     */
    public function destroy(KhoaHocLoiIch $khoahocloiich)
    {
        $khoahocloiich->delete();

        return redirect()->route('admin.khoahocloiich.index')
            ->with('success', 'Xóa lợi ích thành công!');
    }

    /**
     * Cập nhật thứ tự (dùng cho drag‑drop).
     * Nhận payload: { items: [ {id, thu_tu}, ... ] }
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'items' => ['required', 'array'],
            'items.*.id' => ['required', 'integer', 'exists:khoa_hoc_loi_ich,id'],
            'items.*.thu_tu' => ['required', 'integer', 'min:0'],
        ]);

        foreach ($request->items as $item) {
            KhoaHocLoiIch::where('id', $item['id'])->update(['thu_tu' => $item['thu_tu']]);
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

    $import = new KhoaHocLoiIchImport($request->khoa_hoc_id);
    Excel::import($import, $request->file('file'));

    $message = sprintf(
        'Đã import thành công %d lợi ích, bỏ qua %d bản ghi trùng lặp.',
        $import->getImportedCount(),
        $import->getDuplicateCount()
    );

    return redirect()->route('admin.khoahocloiich.index')
                     ->with('success', $message);
}
}