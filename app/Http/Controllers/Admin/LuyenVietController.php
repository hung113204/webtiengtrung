<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LuyenVietRequest;
use App\Models\BaiHoc;
use App\Models\LuyenViet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LuyenVietController extends Controller
{
    public function index(Request $request)
    {
        $query = LuyenViet::with('baiHoc')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('chu_han', 'like', "%{$search}%")
                  ->orWhere('pinyin', 'like', "%{$search}%")
                  ->orWhere('nghia', 'like', "%{$search}%");
            });
        }

        if ($request->filled('bo_thu')) {
            $query->where('bo_thu', 'like', "%{$request->bo_thu}%");
        }

        if ($request->filled('khoa_hoc')) {
            // Lọc theo khóa học cần join qua bảng bai_hoc và chuong_hoc
            // Để đơn giản, giả sử chỉ hiển thị tất cả nếu chưa join chi tiết
        }

        $luyenViets = $query->paginate(20)->withQueryString();
        $baiHocs = BaiHoc::select('id', 'ten_bai_hoc')->get();

        return view('admin.luyenviet.index', compact('luyenViets', 'baiHocs'));
    }

    public function store(LuyenVietRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('gif_net_viet')) {
            $data['gif_net_viet'] = $request->file('gif_net_viet')->store('uploads/luyenviet', 'public');
        }

        LuyenViet::create($data);

        return redirect()->route('admin.luyenviet.index')->with('success', 'Thêm chữ Hán thành công!');
    }

    public function update(LuyenVietRequest $request, $id)
    {
        $luyenViet = LuyenViet::findOrFail($id);
        $data = $request->validated();

        if ($request->hasFile('gif_net_viet')) {
            // Delete old file if exists
            if ($luyenViet->gif_net_viet && Storage::disk('public')->exists($luyenViet->gif_net_viet)) {
                Storage::disk('public')->delete($luyenViet->gif_net_viet);
            }
            $data['gif_net_viet'] = $request->file('gif_net_viet')->store('uploads/luyenviet', 'public');
        }

        $luyenViet->update($data);

        return redirect()->route('admin.luyenviet.index')->with('success', 'Cập nhật chữ Hán thành công!');
    }

    public function destroy($id)
    {
        $luyenViet = LuyenViet::findOrFail($id);
        
        if ($luyenViet->gif_net_viet && Storage::disk('public')->exists($luyenViet->gif_net_viet)) {
            Storage::disk('public')->delete($luyenViet->gif_net_viet);
        }
        
        $luyenViet->delete();

        return redirect()->route('admin.luyenviet.index')->with('success', 'Đã xóa chữ Hán thành công!');
    }
}
