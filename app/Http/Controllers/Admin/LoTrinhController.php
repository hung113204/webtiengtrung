<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LoTrinh;
use App\Http\Requests\LoTrinhRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class LoTrinhController extends Controller
{
    public function index()
    {
        $loTrinhs = LoTrinh::with('giaiDoans.khoaHocs')->orderBy('thu_tu', 'asc')->get();
        return view('admin.lotrinh.index', compact('loTrinhs'));
    }

    public function store(LoTrinhRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['ten_lo_trinh']);

        if ($request->hasFile('anh_bia')) {
            $data['anh_bia'] = $request->file('anh_bia')->store('uploads/lotrinh', 'public');
        }

        LoTrinh::create($data);

        return redirect()->route('admin.lotrinh.index')->with('success', 'Thêm lộ trình thành công!');
    }

    public function show($id)
    {
        $loTrinh = LoTrinh::with(['giaiDoans.khoaHocs'])->findOrFail($id);
        $allKhoaHocs = \App\Models\KhoaHoc::orderBy('id', 'desc')->get();

        return view('admin.lotrinh.show', compact('loTrinh', 'allKhoaHocs'));
    }

    public function storeGiaiDoan(Request $request, $id)
    {
        $request->validate([
            'icon_text' => 'nullable|string|max:50',
            'ten_giai_doan' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'thu_tu' => 'nullable|integer'
        ]);

        $loTrinh = LoTrinh::findOrFail($id);
        $thuTu = $request->thu_tu ?? ($loTrinh->giaiDoans()->max('thu_tu') + 1);

        $loTrinh->giaiDoans()->create([
            'icon_text' => $request->icon_text,
            'ten_giai_doan' => $request->ten_giai_doan,
            'mo_ta' => $request->mo_ta,
            'thu_tu' => $thuTu
        ]);

        return back()->with('success', 'Đã thêm giai đoạn vào lộ trình!');
    }

    public function updateGiaiDoan(Request $request, $id, $giaiDoanId)
    {
        $request->validate([
            'icon_text' => 'nullable|string|max:50',
            'ten_giai_doan' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'thu_tu' => 'nullable|integer'
        ]);

        $giaiDoan = \App\Models\GiaiDoanLoTrinh::where('id_lo_trinh', $id)->findOrFail($giaiDoanId);
        $giaiDoan->update($request->only(['icon_text', 'ten_giai_doan', 'mo_ta', 'thu_tu']));

        return back()->with('success', 'Đã cập nhật giai đoạn!');
    }

    public function destroyGiaiDoan($id, $giaiDoanId)
    {
        $giaiDoan = \App\Models\GiaiDoanLoTrinh::where('id_lo_trinh', $id)->findOrFail($giaiDoanId);
        $giaiDoan->delete();

        return back()->with('success', 'Đã xóa giai đoạn!');
    }

    public function attachCourse(Request $request, $id, $giaiDoanId)
    {
        $request->validate([
            'id_khoa_hoc' => 'required|exists:khoa_hoc,id'
        ]);
        
        $giaiDoan = \App\Models\GiaiDoanLoTrinh::where('id_lo_trinh', $id)->findOrFail($giaiDoanId);

        if (!$giaiDoan->khoaHocs()->where('khoa_hoc.id', $request->id_khoa_hoc)->exists()) {
            $maxOrder = $giaiDoan->khoaHocs()->max('giai_doan_khoa_hoc.thu_tu') ?? 0;
            $giaiDoan->khoaHocs()->attach($request->id_khoa_hoc, ['thu_tu' => $maxOrder + 1]);
            return back()->with('success', 'Đã thêm khóa học vào giai đoạn!');
        }
        
        return back()->with('error', 'Khóa học này đã có sẵn trong giai đoạn!');
    }

    public function detachCourse($id, $giaiDoanId, $khoaHocId)
    {
        $giaiDoan = \App\Models\GiaiDoanLoTrinh::where('id_lo_trinh', $id)->findOrFail($giaiDoanId);
        $giaiDoan->khoaHocs()->detach($khoaHocId);
        
        return back()->with('success', 'Đã xóa khóa học khỏi giai đoạn!');
    }

    public function update(LoTrinhRequest $request, $id)
    {
        $loTrinh = LoTrinh::findOrFail($id);
        $data = $request->validated();
        $data['slug'] = Str::slug($data['ten_lo_trinh']);

        if ($request->hasFile('anh_bia')) {
            if ($loTrinh->anh_bia) {
                Storage::disk('public')->delete($loTrinh->anh_bia);
            }
            $data['anh_bia'] = $request->file('anh_bia')->store('uploads/lotrinh', 'public');
        }

        $loTrinh->update($data);

        return redirect()->route('admin.lotrinh.index')->with('success', 'Cập nhật lộ trình thành công!');
    }

    public function destroy($id)
    {
        $loTrinh = LoTrinh::findOrFail($id);
        if ($loTrinh->anh_bia) {
            Storage::disk('public')->delete($loTrinh->anh_bia);
        }
        $loTrinh->delete();

        return redirect()->route('admin.lotrinh.index')->with('success', 'Xóa lộ trình thành công!');
    }
}
