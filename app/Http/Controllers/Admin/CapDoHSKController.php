<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CapDoHSK;
use App\Http\Requests\CapDoHSKRequest;
use App\Http\Requests\ImportExcelRequest;
use Illuminate\Http\Request;

class CapDoHSKController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $danhsach = CapDoHSK::withCount(['tuVungs', 'nguPhaps'])->orderBy('thu_tu', 'asc')->paginate(10);
        return view('admin.capdohsk.index', compact('danhsach'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.capdohsk.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CapDoHSKRequest $request)
    {
        CapDoHSK::create($request->validated());
        return redirect()->route('admin.capdohsk.index')->with('success', 'Thêm cấp độ HSK thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(CapDoHSK $capdohsk)
    {
        return view('admin.capdohsk.show', compact('capdohsk'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CapDoHSK $capdohsk)
    {
        return view('admin.capdohsk.edit', compact('capdohsk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CapDoHSKRequest $request, CapDoHSK $capdohsk)
    {
        $capdohsk->update($request->validated());
        return redirect()->route('admin.capdohsk.index')->with('success', 'Cập nhật cấp độ HSK thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CapDoHSK $capdohsk, Request $request)
    {
        try {
            $capdohsk->delete();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Xóa cấp độ HSK thành công!']);
            }

            return redirect()->route('admin.capdohsk.index')->with('success', 'Xóa cấp độ HSK thành công!');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Lỗi khi xóa: ' . $e->getMessage()], 500);
            }
            return redirect()->route('admin.capdohsk.index')->with('error', 'Lỗi khi xóa: ' . $e->getMessage());
        }
    }

    /**
     * Xóa nhiều cấp độ HSK (Bulk Delete) bằng Ajax
     */
    public function bulkDelete(Request $request)
    {
        try {
            $ids = $request->input('ids');
            if (empty($ids) || !is_array($ids)) {
                return response()->json(['success' => false, 'message' => 'Vui lòng chọn ít nhất một mục để xóa.']);
            }

            CapDoHSK::whereIn('id', $ids)->delete();

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
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\CapDoHSKImport, $request->file('file'));
            return redirect()->route('admin.capdohsk.index')->with('success', 'Nhập dữ liệu Cấp độ HSK từ Excel thành công!');
        } catch (\Exception $e) {
            return redirect()->route('admin.capdohsk.index')->with('error', 'Lỗi khi nhập Excel: ' . $e->getMessage());
        }
    }
}
