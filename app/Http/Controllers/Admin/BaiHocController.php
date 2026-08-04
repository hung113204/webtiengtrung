<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BaiHoc;
use App\Models\ChuongHoc;
use App\Models\KhoaHoc;
use App\Models\Video;
use App\Http\Requests\Admin\BaiHocRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BaiHocController extends Controller
{
    public function index()
    {
        // Lấy danh sách bài học
        $baiHocs = BaiHoc::with(['chuongHoc.khoaHoc', 'videoItem'])->orderBy('thu_tu', 'asc')->get();
        
        // Lấy danh sách khóa học
        $khoaHocs = KhoaHoc::with('chuongHocs')->get();
        $capDoHsks = \App\Models\CapDoHSK::all();
        
        // Lấy danh sách video đã hoàn thành xử lý
        $videos = Video::where('trang_thai', 'hoan_thanh')->latest()->get();

        return view('admin.baihoc.index', compact('baiHocs', 'khoaHocs', 'capDoHsks', 'videos'));
    }

    public function store(BaiHocRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['ten_bai_hoc']);
        
        if ($request->hasFile('anh_bia')) {
            $data['anh_bia'] = $request->file('anh_bia')->store('uploads/baihoc/images', 'public');
        }
        
        if ($request->video_type === 'library' && $request->filled('video_id')) {
            $data['video_id'] = $request->video_id;
            
            // Tùy chọn: Copy HLS path từ video sang bài học để giữ tương thích ngược
            $video = Video::find($request->video_id);
            if ($video) {
                $data['video'] = $video->file_path; // Hoặc bỏ qua nếu đã dùng video_id hoàn toàn
                $data['hls_path'] = $video->hls_path;
                $data['thoi_luong_giay'] = $video->thoi_luong_giay;
                $data['kich_thuoc'] = $video->kich_thuoc;
            }
        } elseif ($request->video_type === 'url') {
            $data['video'] = $request->video_url;
            $data['video_id'] = null;
        }

        if ($request->hasFile('audio')) {
            $data['audio'] = $request->file('audio')->store('uploads/baihoc/audios', 'public');
        }

        if ($request->hasFile('tai_lieu')) {
            $data['tai_lieu'] = $request->file('tai_lieu')->store('uploads/baihoc/documents', 'public');
        }

        $data['thoi_luong_giay'] = $data['thoi_luong_giay'] ?? 0;

        BaiHoc::create($data);

        return redirect()->route('admin.baihoc.index')->with('success', 'Thêm bài học thành công!');
    }

    public function show($id)
    {
        $baiHoc = BaiHoc::with(['chuongHoc.khoaHoc', 'tuVungs', 'nguPhaps', 'hoiThoais.chiTietHoiThoais', 'luyenViets', 'videoItem'])->findOrFail($id);
        $videos = Video::where('trang_thai', 'hoan_thanh')->latest()->get();
        return view('admin.baihoc.show', compact('baiHoc', 'videos'));
    }

    public function update(BaiHocRequest $request, $id)
    {
        $baiHoc = BaiHoc::findOrFail($id);
        $data = $request->validated();
        $data['slug'] = Str::slug($data['ten_bai_hoc']);

        if ($request->hasFile('anh_bia')) {
            $data['anh_bia'] = $request->file('anh_bia')->store('uploads/baihoc/images', 'public');
        }

        if ($request->video_type === 'library' && $request->filled('video_id')) {
            $data['video_id'] = $request->video_id;
            
            $video = Video::find($request->video_id);
            if ($video) {
                $data['video'] = $video->file_path;
                $data['hls_path'] = $video->hls_path;
                $data['thoi_luong_giay'] = $video->thoi_luong_giay;
                $data['kich_thuoc'] = $video->kich_thuoc;
            }
        } elseif ($request->video_type === 'url') {
            $data['video'] = $request->video_url;
            $data['video_id'] = null;
        }

        if ($request->hasFile('audio')) {
            $data['audio'] = $request->file('audio')->store('uploads/baihoc/audios', 'public');
        }

        if ($request->hasFile('tai_lieu')) {
            $data['tai_lieu'] = $request->file('tai_lieu')->store('uploads/baihoc/documents', 'public');
        }

        $baiHoc->update($data);

        return redirect()->route('admin.baihoc.index')->with('success', 'Cập nhật bài học thành công!');
    }

    public function updateVideo(Request $request, $id)
    {
        $baiHoc = BaiHoc::findOrFail($id);
        
        $request->validate([
            'video_type' => 'required|in:url,library',
            'video_url' => $request->video_type === 'url' ? 'required|url|max:500' : 'nullable',
            'video_id' => $request->video_type === 'library' ? 'required|exists:videos,id' : 'nullable',
        ]);

        $data = [];

        if ($request->video_type === 'library' && $request->filled('video_id')) {
            $data['video_id'] = $request->video_id;
            
            $video = Video::find($request->video_id);
            if ($video) {
                $data['video'] = $video->file_path;
                $data['hls_path'] = $video->hls_path;
                $data['thoi_luong_giay'] = $video->thoi_luong_giay;
                $data['kich_thuoc'] = $video->kich_thuoc;
            }
        } elseif ($request->video_type === 'url') {
            $data['video'] = $request->video_url;
            $data['video_id'] = null;
        }

        $baiHoc->update($data);

        return redirect()->back()->with('success', 'Cập nhật video bài giảng thành công!');
    }

    public function updateCondition(Request $request, $id)
    {
        $baiHoc = BaiHoc::findOrFail($id);
        
        $request->validate([
            'loai_dieu_kien' => 'required|in:tu_dong,xem_video,kiem_tra,phat_am_ai',
            'phan_tram_video' => 'required_if:loai_dieu_kien,xem_video|integer|min:1|max:100',
        ], [
            'loai_dieu_kien.required' => 'Vui lòng chọn loại điều kiện hoàn thành.',
            'loai_dieu_kien.in' => 'Loại điều kiện không hợp lệ.',
            'phan_tram_video.required_if' => 'Vui lòng nhập tỷ lệ % xem video yêu cầu.',
            'phan_tram_video.integer' => 'Tỷ lệ % phải là số nguyên.',
            'phan_tram_video.min' => 'Tỷ lệ % tối thiểu là 1.',
            'phan_tram_video.max' => 'Tỷ lệ % tối đa là 100.',
        ]);

        $baiHoc->update([
            'loai_dieu_kien' => $request->loai_dieu_kien,
            'phan_tram_video' => $request->loai_dieu_kien === 'xem_video' ? $request->phan_tram_video : 0,
        ]);

        return redirect()->route('admin.baihoc.index')->with('success', 'Cập nhật điều kiện hoàn thành bài học thành công!');
    }

    public function destroy($id)
    {
        $baiHoc = BaiHoc::findOrFail($id);
        $baiHoc->delete();

        return redirect()->route('admin.baihoc.index')->with('success', 'Xóa bài học thành công!');
    }
}