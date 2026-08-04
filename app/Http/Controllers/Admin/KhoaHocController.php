<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KhoaHoc;
use App\Models\DanhMucKhoaHoc;
use App\Models\CapDoHSK;
use App\Http\Requests\KhoaHocRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KhoaHocController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = KhoaHoc::with(['danhMucKhoaHoc', 'capDoHSK'])
            ->withCount('baiHocs')
            ->withSum('baiHocs', 'thoi_luong_giay')
            ->orderBy('id', 'desc');

        if ($request->filled('search')) {
            $query->where('ten_khoa_hoc', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('danh_muc_id')) {
            $query->where('id_danh_muc_khoa_hoc', $request->danh_muc_id);
        }

        if ($request->filled('cap_do_id')) {
            $query->where('id_cap_do_hsk', $request->cap_do_id);
        }

        $danhsach = $query->paginate(20)->withQueryString();
        $danhMucs = DanhMucKhoaHoc::all();
        $capDos = CapDoHSK::all();

        return view('admin.khoahoc.index', compact('danhsach', 'danhMucs', 'capDos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $danhMucs = DanhMucKhoaHoc::all();
        $capDos = CapDoHSK::all();
        $khoahoc = new KhoaHoc();
        return view('admin.khoahoc.create', compact('danhMucs', 'capDos', 'khoahoc'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(KhoaHocRequest $request)
    {
        $data = $request->validated();

        $data['noi_bat'] = $request->has('noi_bat') ? 1 : 0;
        $data['trang_thai'] = $request->has('trang_thai') ? 1 : 0;

        if ($request->hasFile('anh_bia')) {
            $data['anh_bia'] = $request->file('anh_bia')->store('uploads/khoahoc', 'public');
        }

        KhoaHoc::create($data);

        return redirect()->route('admin.khoahoc.index')->with('success', 'Thêm khóa học thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(KhoaHoc $khoahoc)
    {
        $khoahoc->load(['danhMucKhoaHoc', 'capDoHSK']);
        return view('admin.khoahoc.show', compact('khoahoc'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KhoaHoc $khoahoc)
    {
        $danhMucs = DanhMucKhoaHoc::all();
        $capDos = CapDoHSK::all();
        return view('admin.khoahoc.edit', compact('khoahoc', 'danhMucs', 'capDos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(KhoaHocRequest $request, KhoaHoc $khoahoc)
    {
        $data = $request->validated();

        $data['noi_bat'] = $request->has('noi_bat') ? 1 : 0;
        $data['trang_thai'] = $request->has('trang_thai') ? 1 : 0;

        if ($request->input('xoa_anh_bia') == '1') {
            if ($khoahoc->anh_bia && Storage::disk('public')->exists($khoahoc->anh_bia)) {
                Storage::disk('public')->delete($khoahoc->anh_bia);
            }
            $data['anh_bia'] = null;
        }

        if ($request->hasFile('anh_bia')) {
            if ($khoahoc->anh_bia && Storage::disk('public')->exists($khoahoc->anh_bia)) {
                Storage::disk('public')->delete($khoahoc->anh_bia);
            }
            $data['anh_bia'] = $request->file('anh_bia')->store('uploads/khoahoc', 'public');
        }

        $khoahoc->update($data);

        return redirect()->route('admin.khoahoc.index')->with('success', 'Cập nhật khóa học thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KhoaHoc $khoahoc)
    {
        if ($khoahoc->anh_bia && Storage::disk('public')->exists($khoahoc->anh_bia)) {
            Storage::disk('public')->delete($khoahoc->anh_bia);
        }
        $khoahoc->delete();

        return redirect()->route('admin.khoahoc.index')->with('success', 'Xóa khóa học thành công!');
    }
}
