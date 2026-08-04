<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CauHoiController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\CauHoi::with(['loaiCauHoi', 'baiHoc', 'dapAns']);
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('noi_dung', 'LIKE', "%{$search}%")
                  ->orWhere('pinyin', 'LIKE', "%{$search}%")
                  ->orWhere('dich_nghia', 'LIKE', "%{$search}%");
            });
        }
        
        if ($request->filled('loai_cau_hoi')) {
            $query->where('id_loai_cau_hoi', $request->loai_cau_hoi);
        }
        
        if ($request->filled('id_muc_do')) {
            $query->where('id_muc_do', $request->id_muc_do);
        }

        $cauHois = $query->latest()->paginate(10);
        $loaiCauHois = \App\Models\LoaiCauHoi::orderBy('thu_tu')->get();
        $mucDos = \App\Models\MucDo::orderBy('thu_tu')->get();
        
        return view('admin.cauhoi.index', compact('cauHois', 'loaiCauHois', 'mucDos'));
    }

    public function create()
    {
        $khoaHocs = \App\Models\KhoaHoc::all();
        $baiHocs = \App\Models\BaiHoc::all();
        $loaiCauHois = \App\Models\LoaiCauHoi::orderBy('thu_tu')->get();
        $mucDos = \App\Models\MucDo::orderBy('thu_tu')->get();
        return view('admin.cauhoi.create', compact('khoaHocs', 'baiHocs', 'loaiCauHois', 'mucDos'));
    }

    public function store(\App\Http\Requests\Admin\CauHoiRequest $request)
    {
        $data = $request->validated();

        // Upload files
        if ($request->hasFile('hinh_anh')) {
            $data['hinh_anh'] = $request->file('hinh_anh')->store('cauhoi/images', 'public');
        }
        if ($request->hasFile('am_thanh')) {
            $data['am_thanh'] = $request->file('am_thanh')->store('cauhoi/audio', 'public');
        }
        if ($request->hasFile('video')) {
            $data['video'] = $request->file('video')->store('cauhoi/video', 'public');
        }
        if ($request->hasFile('am_thanh_giai_thich')) {
            $data['am_thanh_giai_thich'] = $request->file('am_thanh_giai_thich')->store('cauhoi/audio_giai_thich', 'public');
        }

        // Tạo câu hỏi
        $cauHoi = \App\Models\CauHoi::create($data);

        // Lưu đáp án
        $dapAnData = $request->input('dap_an', []);
        $dapAnPinyin = $request->input('dap_an_pinyin', []);
        $dapAnDung = $request->input('dap_an_dung');

        // Fix for single answer (Điền khuyết / Sắp xếp câu)
        if (empty($dapAnData) && $request->filled('dap_an_single')) {
            $dapAnData = ['A' => $request->input('dap_an_single')];
            $dapAnDung = 'A';
        }

        foreach ($dapAnData as $key => $noi_dung) {
            if (!empty($noi_dung)) {
                \App\Models\DapAn::create([
                    'id_cau_hoi' => $cauHoi->id,
                    'noi_dung' => $noi_dung,
                    'pinyin' => $dapAnPinyin[$key] ?? null,
                    'dung' => ($key === $dapAnDung),
                ]);
            }
        }

        return redirect()->route('admin.cauhoi.index')->with('success', 'Thêm câu hỏi mới thành công!');
    }

    public function edit($id)
    {
        $cauHoi = \App\Models\CauHoi::findOrFail($id);
        $khoaHocs = \App\Models\KhoaHoc::all();
        $baiHocs = \App\Models\BaiHoc::all();
        $loaiCauHois = \App\Models\LoaiCauHoi::orderBy('thu_tu')->get();
        $mucDos = \App\Models\MucDo::orderBy('thu_tu')->get();
        
        $cauHoi->load('dapAns', 'loaiCauHoi');

        return view('admin.cauhoi.edit', compact('cauHoi', 'khoaHocs', 'baiHocs', 'loaiCauHois', 'mucDos'));
    }

    public function update(\App\Http\Requests\Admin\CauHoiRequest $request, $id)
    {
        $cauHoi = \App\Models\CauHoi::findOrFail($id);
        $data = $request->validated();

        // Xử lý upload file mới
        if ($request->hasFile('hinh_anh')) {
            if ($cauHoi->hinh_anh) \Illuminate\Support\Facades\Storage::disk('public')->delete($cauHoi->hinh_anh);
            $data['hinh_anh'] = $request->file('hinh_anh')->store('cauhoi/images', 'public');
        }
        if ($request->hasFile('am_thanh')) {
            if ($cauHoi->am_thanh) \Illuminate\Support\Facades\Storage::disk('public')->delete($cauHoi->am_thanh);
            $data['am_thanh'] = $request->file('am_thanh')->store('cauhoi/audio', 'public');
        }
        if ($request->hasFile('video')) {
            if ($cauHoi->video) \Illuminate\Support\Facades\Storage::disk('public')->delete($cauHoi->video);
            $data['video'] = $request->file('video')->store('cauhoi/video', 'public');
        }
        if ($request->hasFile('am_thanh_giai_thich')) {
            if ($cauHoi->am_thanh_giai_thich) \Illuminate\Support\Facades\Storage::disk('public')->delete($cauHoi->am_thanh_giai_thich);
            $data['am_thanh_giai_thich'] = $request->file('am_thanh_giai_thich')->store('cauhoi/audio_giai_thich', 'public');
        }

        // Cập nhật câu hỏi
        $cauHoi->update($data);

        // Xóa đáp án cũ
        $cauHoi->dapAns()->delete();

        // Lưu đáp án mới
        $dapAnData = $request->input('dap_an', []);
        $dapAnPinyin = $request->input('dap_an_pinyin', []);
        $dapAnDung = $request->input('dap_an_dung');

        // Fix for single answer (Điền khuyết / Sắp xếp câu)
        if (empty($dapAnData) && $request->filled('dap_an_single')) {
            $dapAnData = ['A' => $request->input('dap_an_single')];
            $dapAnDung = 'A';
        }

        foreach ($dapAnData as $key => $noi_dung) {
            if (!empty($noi_dung)) {
                \App\Models\DapAn::create([
                    'id_cau_hoi' => $cauHoi->id,
                    'noi_dung' => $noi_dung,
                    'pinyin' => $dapAnPinyin[$key] ?? null,
                    'dung' => ($key === $dapAnDung),
                ]);
            }
        }

        return redirect()->route('admin.cauhoi.index')->with('success', 'Cập nhật câu hỏi thành công!');
    }

    public function import(Request $request)
    {
        $request->validate([
            'id_bai_hoc' => 'nullable|exists:bai_hoc,id',
            'id_loai_cau_hoi' => 'nullable|exists:loai_cau_hoi,id',
            'id_muc_do' => 'nullable|exists:muc_dos,id',
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ], [
            'excel_file.required' => 'Vui lòng tải lên file Excel.',
            'excel_file.mimes' => 'Chỉ chấp nhận file định dạng .xlsx, .xls, hoặc .csv.',
        ]);

        try {
            $import = new \App\Imports\CauHoiImport($request->id_bai_hoc, $request->id_loai_cau_hoi, $request->id_muc_do);
            \Maatwebsite\Excel\Facades\Excel::import($import, $request->file('excel_file'));

            $imported = $import->getImportedCount();
            $errors = $import->getErrorCount();

            $message = "Đã import thành công $imported câu hỏi.";
            if ($errors > 0) {
                $message .= " Bỏ qua $errors dòng không hợp lệ (thiếu thông tin bắt buộc).";
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Lỗi khi import file: ' . $e->getMessage());
        }
    }
}
