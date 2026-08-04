<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QuyenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $quyens = \App\Models\Quyen::orderBy('nhom_quyen')->orderBy('id', 'desc')->get();
        return view('admin.quyen.index', compact('quyens'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ten_quyen' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:quyen,slug',
            'nhom_quyen' => 'nullable|string|max:255'
        ], [
            'ten_quyen.required' => 'Vui lòng nhập tên quyền.',
            'slug.required' => 'Vui lòng nhập mã slug.',
            'slug.unique' => 'Mã slug này đã tồn tại.'
        ]);

        \App\Models\Quyen::create($request->all());

        return redirect()->back()->with('success', 'Thêm quyền thành công!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $quyen = \App\Models\Quyen::findOrFail($id);

        $request->validate([
            'ten_quyen' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:quyen,slug,' . $id,
            'nhom_quyen' => 'nullable|string|max:255'
        ]);

        $quyen->update($request->all());

        return redirect()->back()->with('success', 'Cập nhật quyền thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $quyen = \App\Models\Quyen::findOrFail($id);
        $quyen->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa quyền thành công!'
            ]);
        }
        return redirect()->back()->with('success', 'Xóa quyền thành công!');
    }
}
