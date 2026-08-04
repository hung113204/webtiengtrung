<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VaiTro;
use App\Http\Requests\VaiTroRequest;
use Illuminate\Http\Request;

class VaiTroController extends Controller
{
    public function index()
    {
        $vaitros = VaiTro::with('quyens')->orderBy('id', 'desc')->get();
        $quyens = \App\Models\Quyen::all()->groupBy('nhom_quyen');
        return view('admin.vaitro.index', compact('vaitros', 'quyens'));
    }

    public function store(VaiTroRequest $request)
    {
        $data = $request->validated();
        
        if (empty($data['slug'])) {
            $data['slug'] = \Illuminate\Support\Str::slug($data['ten_vai_tro']);
        }
        
        $data['is_default'] = $request->has('is_default') ? true : false;
        
        if ($data['is_default']) {
            VaiTro::where('is_default', true)->update(['is_default' => false]);
        }

        $vaitro = VaiTro::create($data);
        if ($request->has('permissions')) {
            $vaitro->quyens()->sync($request->permissions);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thêm vai trò thành công!'
            ]);
        }
        return redirect()->back()->with('success', 'Thêm vai trò thành công!');
    }

    public function update(VaiTroRequest $request, string $id)
    {
        $vaitro = VaiTro::findOrFail($id);
        $data = $request->validated();

        if (empty($data['slug'])) {
            $data['slug'] = \Illuminate\Support\Str::slug($data['ten_vai_tro']);
        }

        $data['is_default'] = $request->has('is_default') ? true : false;
        
        if ($data['is_default'] && !$vaitro->is_default) {
            VaiTro::where('is_default', true)->update(['is_default' => false]);
        }

        $vaitro->update($data);
        if ($request->has('permissions')) {
            $vaitro->quyens()->sync($request->permissions);
        } else {
            $vaitro->quyens()->detach();
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật vai trò thành công!'
            ]);
        }
        return redirect()->back()->with('success', 'Cập nhật vai trò thành công!');
    }

    public function destroy(Request $request, string $id)
    {
        VaiTro::findOrFail($id)->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa vai trò thành công!'
            ]);
        }
        return redirect()->back()->with('success', 'Xóa vai trò thành công!');
    }
}
