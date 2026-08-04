<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HoiThoai;
use App\Models\BaiHoc;
use App\Http\Requests\HoiThoai\StoreHoiThoaiRequest;
use App\Http\Requests\HoiThoai\UpdateHoiThoaiRequest;
use Illuminate\Support\Facades\Storage;

class HoiThoaiController extends Controller
{
    public function index(Request $request)
    {
        $query = HoiThoai::with(['baiHoc', 'chiTietHoiThoais'])->latest();

        if ($request->filled('id_bai_hoc')) {
            $query->where('id_bai_hoc', $request->id_bai_hoc);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('tieu_de', 'like', "%{$search}%")
                  ->orWhere('mo_ta', 'like', "%{$search}%");
        }

        $hoiThoais = $query->paginate(15)->withQueryString();
        $baiHocs = BaiHoc::select('id', 'ten_bai_hoc')->get();

        return view('admin.hoithoai.index', compact('hoiThoais', 'baiHocs'));
    }

    public function create()
    {
        $baiHocs = BaiHoc::select('id', 'ten_bai_hoc')->get();
        return view('admin.hoithoai.create', compact('baiHocs'));
    }

    public function store(StoreHoiThoaiRequest $request)
    {
        $data = $request->validated();
        HoiThoai::create($data);

        return redirect()->route('admin.hoithoai.index')->with('success', 'Đã thêm nhóm hội thoại mới thành công!');
    }

    public function show(string $id)
    {
        $hoiThoai = HoiThoai::with('chiTietHoiThoais')->findOrFail($id);
        return view('admin.chitiethoithoai.index', compact('hoiThoai'));
    }

    public function edit(string $id)
    {
        $hoiThoai = HoiThoai::findOrFail($id);
        $baiHocs = BaiHoc::select('id', 'ten_bai_hoc')->get();
        return view('admin.hoithoai.edit', compact('hoiThoai', 'baiHocs'));
    }

    public function update(UpdateHoiThoaiRequest $request, string $id)
    {
        $hoiThoai = HoiThoai::findOrFail($id);
        $data = $request->validated();
        $hoiThoai->update($data);

        return redirect()->route('admin.hoithoai.index')->with('success', 'Cập nhật nhóm hội thoại thành công!');
    }

    public function destroy(string $id)
    {
        $hoiThoai = HoiThoai::findOrFail($id);
        
        // Delete related audio files for details
        foreach ($hoiThoai->chiTietHoiThoais as $chitiet) {
            if ($chitiet->am_thanh) {
                Storage::disk('public')->delete($chitiet->am_thanh);
            }
        }
        
        $hoiThoai->delete();

        return redirect()->route('admin.hoithoai.index')->with('success', 'Đã xóa hội thoại!');
    }
}
