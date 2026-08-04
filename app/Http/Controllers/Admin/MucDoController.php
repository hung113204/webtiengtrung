<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MucDoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\MucDo::query();
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('ten_muc_do', 'LIKE', "%{$search}%")
                  ->orWhere('slug', 'LIKE', "%{$search}%");
        }

        $mucDos = $query->orderBy('thu_tu')->paginate(10);
        
        return view('admin.mucdocauhoi.index', compact('mucDos'));
    }

    public function store(\App\Http\Requests\Admin\MucDoRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?? \Illuminate\Support\Str::slug($data['ten_muc_do']);
        
        // Auto-increment thu_tu if not provided
        if (!isset($data['thu_tu'])) {
            $data['thu_tu'] = \App\Models\MucDo::max('thu_tu') + 1;
        }

        \App\Models\MucDo::create($data);
        return redirect()->route('admin.mucdocauhoi.index')->with('success', 'Thêm mức độ thành công!');
    }

    public function update(\App\Http\Requests\Admin\MucDoRequest $request, $id)
    {
        $mucDo = \App\Models\MucDo::findOrFail($id);
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?? \Illuminate\Support\Str::slug($data['ten_muc_do']);

        $mucDo->update($data);
        return redirect()->route('admin.mucdocauhoi.index')->with('success', 'Cập nhật mức độ thành công!');
    }

    public function destroy($id)
    {
        $mucDo = \App\Models\MucDo::findOrFail($id);
        
        if ($mucDo->cauHois()->count() > 0) {
            return redirect()->route('admin.mucdocauhoi.index')->withErrors(['error' => 'Không thể xóa mức độ đang có câu hỏi!']);
        }
        
        $mucDo->delete();
        return redirect()->route('admin.mucdocauhoi.index')->with('success', 'Xóa mức độ thành công!');
    }
}
