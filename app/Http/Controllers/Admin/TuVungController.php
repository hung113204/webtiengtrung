<?php

namespace App\Http\Controllers\Admin;

use App\Models\TuVung;
use App\Models\BaiHoc;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\TuVungRequest;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TuVungImport;

class TuVungController extends Controller
{
    public function store(TuVungRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('am_thanh')) {
            $data['am_thanh'] = $request->file('am_thanh')->store('uploads/tuvung/audio', 'public');
        } else {
            // Tự động sinh file audio bằng Google Translate TTS
            $text = urlencode($data['tu_han']);
            $url = "https://translate.google.com/translate_tts?ie=UTF-8&tl=zh-CN&client=tw-ob&q={$text}";
            try {
                $audioContent = file_get_contents($url);
                if ($audioContent) {
                    $fileName = 'uploads/tuvung/audio/tts_' . time() . '_' . rand(1000, 9999) . '.mp3';
                    Storage::disk('public')->put($fileName, $audioContent);
                    $data['am_thanh'] = $fileName;
                }
            } catch (\Exception $e) {
                // Bỏ qua nếu lỗi mạng để tiếp tục lưu từ vựng
            }
        }

        if ($request->hasFile('hinh_anh')) {
            $data['hinh_anh'] = $request->file('hinh_anh')->store('uploads/tuvung/images', 'public');
        }

        TuVung::create($data);

        return redirect()->back()->with('success', 'Thêm từ vựng thành công!');
    }

    public function update(TuVungRequest $request, $id)
    {
        $tuVung = TuVung::findOrFail($id);
        $data = $request->validated();

        if ($request->hasFile('am_thanh')) {
            if ($tuVung->am_thanh) {
                Storage::disk('public')->delete($tuVung->am_thanh);
            }
            $data['am_thanh'] = $request->file('am_thanh')->store('uploads/tuvung/audio', 'public');
        } elseif (!$tuVung->am_thanh && $tuVung->tu_han !== $data['tu_han']) {
            // Cập nhật lại audio TTS nếu chưa có audio và chữ Hán thay đổi
            $text = urlencode($data['tu_han']);
            $url = "https://translate.google.com/translate_tts?ie=UTF-8&tl=zh-CN&client=tw-ob&q={$text}";
            try {
                $audioContent = file_get_contents($url);
                if ($audioContent) {
                    $fileName = 'uploads/tuvung/audio/tts_' . time() . '_' . rand(1000, 9999) . '.mp3';
                    Storage::disk('public')->put($fileName, $audioContent);
                    $data['am_thanh'] = $fileName;
                }
            } catch (\Exception $e) {
                // Ignore
            }
        }

        if ($request->hasFile('hinh_anh')) {
            if ($tuVung->hinh_anh) {
                Storage::disk('public')->delete($tuVung->hinh_anh);
            }
            $data['hinh_anh'] = $request->file('hinh_anh')->store('uploads/tuvung/images', 'public');
        }

        $tuVung->update($data);

        return redirect()->back()->with('success', 'Cập nhật từ vựng thành công!');
    }

    public function index()
    {
        $tuVungs = TuVung::with('baiHoc')->orderBy('id', 'desc')->paginate(20);
        $baiHocs = BaiHoc::all();
        return view('admin.tuvung.index', compact('tuVungs', 'baiHocs'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'id_bai_hoc' => 'nullable|exists:bai_hoc,id',
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ], [
            'excel_file.required' => 'Vui lòng tải lên file Excel.',
            'excel_file.mimes' => 'Chỉ chấp nhận file định dạng .xlsx, .xls, hoặc .csv.',
        ]);

        try {
            $import = new TuVungImport($request->id_bai_hoc);
            Excel::import($import, $request->file('excel_file'));

            $imported = $import->getImportedCount();
            $duplicates = $import->getDuplicateCount();

            $message = "Nhập thành công {$imported} từ vựng! (Âm thanh đã được tự động tạo bằng AI).";
            if ($duplicates > 0) {
                $message .= " Đã bỏ qua {$duplicates} từ bị trùng lặp.";
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['excel_file' => 'Lỗi khi nhập file: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $tuVung = TuVung::findOrFail($id);
        
        if ($tuVung->am_thanh) {
            Storage::disk('public')->delete($tuVung->am_thanh);
        }

        if ($tuVung->hinh_anh) {
            Storage::disk('public')->delete($tuVung->hinh_anh);
        }

        $tuVung->delete();

        return redirect()->back()->with('success', 'Xóa từ vựng thành công!');
    }
}
