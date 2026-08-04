<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Video;
use App\Jobs\ProcessVideoAI;
use App\Jobs\GenerateVocabularyAI;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VideoController extends Controller
{
    public function index(Request $request)
    {
        $query = Video::latest();

        if ($request->filled('search')) {
            $query->where('ten_video', 'like', '%' . $request->search . '%');
        }

        $videos = $query->paginate(15)->withQueryString();

        return view('admin.videos.index', compact('videos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'video_file' => 'required|file|max:500000', // max 500MB
            'ten_video' => 'nullable|string|max:255',
        ]);

        $file = $request->file('video_file');
        $fileName = $request->filled('ten_video') ? $request->ten_video : $file->getClientOriginalName();
        
        $path = $file->store('uploads/videos/original', 'public');

        $video = Video::create([
            'hash_id' => substr(md5(uniqid(mt_rand(), true)), 0, 8),
            'ten_video' => $fileName,
            'file_path' => $path,
            'trang_thai' => 'dang_cho',
            'phan_tram' => 0,
            'kich_thuoc' => $file->getSize(),
        ]);

        // Dispatch job
        ProcessVideoAI::dispatch($video);

        return redirect()->route('admin.videos.index')->with('success', 'Video đã được tải lên và đang chờ xử lý!');
    }

    public function destroy(Video $video)
    {
        if ($video->file_path && Storage::disk('public')->exists($video->file_path)) {
            Storage::disk('public')->delete($video->file_path);
        }
        
        if ($video->hls_path && Storage::disk('public')->exists(dirname($video->hls_path))) {
            Storage::disk('public')->deleteDirectory(dirname($video->hls_path));
        }

        $video->delete();

        return redirect()->route('admin.videos.index')->with('success', 'Đã xóa video thành công!');
    }

    public function getStatus(Request $request)
    {
        $ids = $request->ids ?? [];
        $videos = Video::whereIn('id', $ids)->get(['id', 'trang_thai', 'phan_tram']);
        
        return response()->json([
            'success' => true,
            'data' => $videos
        ]);
    }

    public function generateVocab(Video $video)
    {
        GenerateVocabularyAI::dispatch($video);
        return back()->with('success', 'Đã yêu cầu AI trích xuất từ vựng cho Video này. Quá trình đang chạy ngầm.');
    }

    public function retry(Video $video)
    {
        if ($video->hls_path && Storage::disk('public')->exists(dirname($video->hls_path))) {
            Storage::disk('public')->deleteDirectory(dirname($video->hls_path));
        }

        $video->update([
            'trang_thai' => 'dang_cho',
            'phan_tram' => 0,
            'thong_bao_loi' => null,
            'hls_path' => null
        ]);

        ProcessVideoAI::dispatch($video);

        return back()->with('success', 'Đã đưa video vào hàng đợi để thử lại.');
    }
}
