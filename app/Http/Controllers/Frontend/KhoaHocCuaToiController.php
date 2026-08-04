<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DangKyKhoaHoc;
use App\Models\BinhLuan;
use App\Models\TienDoHoc;

class KhoaHocCuaToiController extends Controller
{
    /**
     * Display the list of registered courses.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();

        // Lấy tất cả khóa học đã đăng ký kèm theo các bài học (chỉ lấy bài đã xuất bản)
        $khoaHocDangKys = DangKyKhoaHoc::with(['khoaHoc.capDoHsk', 'khoaHoc.giaoViens.nguoiDung', 'khoaHoc.baiHocs' => function ($query) {
            $query->where('bai_hoc.trang_thai', 'published');
        }])
            ->where('id_nguoi_dung', $user->id)
            ->where('trang_thai', 'Đã duyệt')
            ->get()
            ->unique('id_khoa_hoc');

        // Lấy tiến độ học của user
        $tienDoHocs = TienDoHoc::where('id_nguoi_dung', $user->id)
            ->where('da_hoan_thanh', true)
            ->pluck('da_hoan_thanh', 'id_bai_hoc');

        $soHoanThanh = 0;
        $soDangHoc = 0;

        foreach ($khoaHocDangKys as $dk) {
            if ($dk->khoaHoc) {
                $totalLessons = $dk->khoaHoc->baiHocs->count();
                $completedLessons = 0;

                foreach ($dk->khoaHoc->baiHocs as $baiHoc) {
                    if (isset($tienDoHocs[$baiHoc->id])) {
                        $completedLessons++;
                    }
                }

                $dk->phan_tram_hoan_thanh = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;

                if ($dk->phan_tram_hoan_thanh == 100) {
                    // Cập nhật trạng thái ảo để hiển thị
                    $dk->is_completed = true;
                    $soHoanThanh++;
                } else {
                    $dk->is_completed = false;
                    $soDangHoc++;
                }
            } else {
                $dk->phan_tram_hoan_thanh = 0;
                $dk->is_completed = false;
            }
        }

        $tongSo = $khoaHocDangKys->count();

        // Lấy danh sách ID khóa học yêu thích
        $yeuThichIds = \App\Models\YeuThichKhoaHoc::where('id_nguoi_dung', $user->id)
            ->pluck('id_khoa_hoc')
            ->toArray();

        return view('frontend.khoahoccuatoidashboard.index', compact(
            'khoaHocDangKys',
            'tongSo',
            'soDangHoc',
            'soHoanThanh',
            'yeuThichIds'
        ));
    }

    /**
     * Logic thông minh để xác định bài học tiếp theo cần học
     */
    public function resume($courseSlug)
    {
        $user = Auth::user();
        $khoaHoc = \App\Models\KhoaHoc::where('slug', $courseSlug)->firstOrFail();
        
        $khoaHoc->load(['chuongHocs' => function($q) {
            $q->orderBy('thu_tu')->with(['baiHocs' => function($q2) {
                $q2->orderBy('thu_tu')->where('trang_thai', 'published');
            }]);
        }]);

        $allLessons = $khoaHoc->chuongHocs->flatMap->baiHocs;

        if ($allLessons->isEmpty()) {
            return redirect()->route('frontend.dashboard.khoahoc')->with('error', 'Khóa học chưa có bài học nào.');
        }

        // Lấy tiến độ học gần nhất của user cho khóa này
        $lastTienDo = \App\Models\TienDoHoc::where('id_nguoi_dung', $user->id)
            ->whereIn('id_bai_hoc', $allLessons->pluck('id'))
            ->orderBy('lan_hoc_cuoi', 'desc')
            ->first();

        if ($lastTienDo) {
            // Nếu bài gần nhất chưa hoàn thành, nhảy thẳng vào bài đó
            if (!$lastTienDo->da_hoan_thanh) {
                $lesson = $allLessons->firstWhere('id', $lastTienDo->id_bai_hoc);
                if ($lesson) {
                    return redirect()->route('frontend.dashboard.khoahoc.show', ['courseSlug' => $courseSlug, 'lessonSlug' => $lesson->slug]);
                }
            } else {
                // Nếu bài gần nhất ĐÃ hoàn thành, tìm bài tiếp theo chưa hoàn thành
                $lessonIds = $allLessons->pluck('id')->toArray();
                $currentIndex = array_search($lastTienDo->id_bai_hoc, $lessonIds);
                
                if ($currentIndex !== false && $currentIndex < count($lessonIds) - 1) {
                    $nextLesson = $allLessons[$currentIndex + 1];
                    return redirect()->route('frontend.dashboard.khoahoc.show', ['courseSlug' => $courseSlug, 'lessonSlug' => $nextLesson->slug]);
                } else {
                    $lesson = $allLessons->firstWhere('id', $lastTienDo->id_bai_hoc);
                    return redirect()->route('frontend.dashboard.khoahoc.show', ['courseSlug' => $courseSlug, 'lessonSlug' => $lesson->slug]);
                }
            }
        }

        // Nếu chưa học bài nào, nhảy vào bài đầu tiên
        $firstLesson = $allLessons->first();
        return redirect()->route('frontend.dashboard.khoahoc.show', ['courseSlug' => $courseSlug, 'lessonSlug' => $firstLesson->slug]);
    }

    /**
     * Giao diện học tập Video
     */
    public function show($courseSlug, $lessonSlug = null)
    {
        $user = Auth::user();

        // 1. Kiểm tra khóa học và quyền truy cập (Đã đăng ký và Đã duyệt)
        $khoaHoc = \App\Models\KhoaHoc::where('slug', $courseSlug)->firstOrFail();
        
        $isEnrolled = DangKyKhoaHoc::where('id_nguoi_dung', $user->id)
            ->where('id_khoa_hoc', $khoaHoc->id)
            ->where('trang_thai', 'Đã duyệt') // hoặc 'hoan_thanh'
            ->exists();

        if (!$isEnrolled) {
            return redirect()->route('khoahoc.show', $courseSlug)
                ->with('error', 'Bạn chưa đăng ký hoặc chưa được duyệt vào khóa học này.');
        }

        // 2. Load danh sách chương & bài học (Playlist)
        $khoaHoc->load(['chuongHocs' => function($q) {
            $q->orderBy('thu_tu')->with(['baiHocs' => function($q2) {
                $q2->orderBy('thu_tu')->where('trang_thai', 'published')->withCount('cauHois');
            }]);
        }]);

        // 3. Lấy bài học hiện tại
        if ($lessonSlug) {
            $baiHoc = \App\Models\BaiHoc::where('slug', $lessonSlug)
                ->where('trang_thai', 'published')
                ->firstOrFail();
        } else {
            // Lấy bài học đầu tiên của khóa học
            $firstChapter = $khoaHoc->chuongHocs->first();
            if (!$firstChapter || $firstChapter->baiHocs->isEmpty()) {
                return redirect()->route('frontend.dashboard.khoahoc')->with('error', 'Khóa học chưa có bài học nào.');
            }
            $baiHoc = $firstChapter->baiHocs->first();
            // Redirect để URL có lesson slug
            return redirect()->route('frontend.dashboard.khoahoc.show', ['courseSlug' => $courseSlug, 'lessonSlug' => $baiHoc->slug]);
        }

        // 4. Load thông tin phụ của bài học (tài liệu, thảo luận, câu hỏi quiz nếu có)
        $baiHoc->load([
            'chuongHoc.khoaHoc',
            'cauHois.dapAns',
            'binhLuans' => function($q) {
                $q->whereNull('parent_id')
                  ->where('trang_thai', 1)
                  ->with(['nguoiDung', 'replies' => function($r) {
                      $r->where('trang_thai', 1);
                  }])
                  ->orderByDesc('created_at');
            }
        ]);

        // Tìm bài trước / Bài sau để làm Next/Prev button
        $allLessons = $khoaHoc->chuongHocs->flatMap->baiHocs;
        $currentIndex = $allLessons->search(function ($item) use ($baiHoc) {
            return $item->id === $baiHoc->id;
        });
        
        $prevLesson = $currentIndex > 0 ? $allLessons[$currentIndex - 1] : null;
        $nextLesson = $currentIndex < $allLessons->count() - 1 ? $allLessons[$currentIndex + 1] : null;

        $tienDo = TienDoHoc::firstOrCreate(
            ['id_nguoi_dung' => $user->id, 'id_bai_hoc' => $baiHoc->id],
            ['phan_tram_hoan_thanh' => 0, 'da_hoan_thanh' => false, 'lan_hoc_cuoi' => now()]
        );
        $tienDo->update(['lan_hoc_cuoi' => now()]);

        // Tính tiến độ toàn khóa học
        $tongSoBai = $allLessons->count();
        $danhSachBaiDaHoc = TienDoHoc::where('id_nguoi_dung', $user->id)
            ->whereIn('id_bai_hoc', $allLessons->pluck('id'))
            ->where('da_hoan_thanh', true)
            ->pluck('id_bai_hoc')
            ->toArray();
        $soBaiDaHoc = count($danhSachBaiDaHoc);
        $phanTramKhoaHoc = $tongSoBai > 0 ? round(($soBaiDaHoc / $tongSoBai) * 100) : 0;

        return view('frontend.khoahoccuatoidashboard.show', compact(
            'khoaHoc', 'baiHoc', 'allLessons', 'prevLesson', 'nextLesson', 'tienDo',
            'tongSoBai', 'soBaiDaHoc', 'phanTramKhoaHoc', 'danhSachBaiDaHoc'
        ));
    }

    public function quiz($courseSlug, $lessonSlug)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $khoaHoc = \App\Models\KhoaHoc::where('slug', $courseSlug)->firstOrFail();
        $baiHoc = \App\Models\BaiHoc::where('slug', $lessonSlug)
            ->where('trang_thai', 'published')
            ->firstOrFail();

        $baiHoc->load('cauHois.dapAns', 'chuongHoc');

        if ($baiHoc->cauHois->count() === 0) {
            return redirect()->route('frontend.dashboard.khoahoc.show', ['courseSlug' => $courseSlug, 'lessonSlug' => $lessonSlug])->with('error', 'Bài học này không có câu hỏi kiểm tra.');
        }

        // Lấy bài học tiếp theo để chuyển hướng sau khi hoàn thành
        $khoaHoc->load(['chuongHocs' => function($q) {
            $q->orderBy('thu_tu')->with(['baiHocs' => function($q2) {
                $q2->orderBy('thu_tu')->where('trang_thai', 'published')->withCount('cauHois');
            }]);
        }]);
        $allLessons = $khoaHoc->chuongHocs->flatMap->baiHocs;
        $currentIndex = $allLessons->search(function ($item) use ($baiHoc) {
            return $item->id === $baiHoc->id;
        });
        $nextLesson = $currentIndex < $allLessons->count() - 1 ? $allLessons[$currentIndex + 1] : null;

        $tienDo = \App\Models\TienDoHoc::firstOrCreate(
            ['id_nguoi_dung' => $user->id, 'id_bai_hoc' => $baiHoc->id],
            ['phan_tram_hoan_thanh' => 0, 'da_hoan_thanh' => false, 'lan_hoc_cuoi' => now()]
        );

        if ($tienDo->da_hoan_thanh) {
            return redirect()->route('frontend.dashboard.khoahoc.show', ['courseSlug' => $courseSlug, 'lessonSlug' => $lessonSlug])
                ->with('success', 'Bạn đã hoàn thành bài kiểm tra này rồi!');
        }

        return view('frontend.khoahoccuatoidashboard.quiz', compact('khoaHoc', 'baiHoc', 'nextLesson', 'tienDo'));
    }

    public function pronunciation($courseSlug, $lessonSlug)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $khoaHoc = \App\Models\KhoaHoc::where('slug', $courseSlug)->firstOrFail();
        $baiHoc = \App\Models\BaiHoc::where('slug', $lessonSlug)
            ->where('trang_thai', 'published')
            ->firstOrFail();

        $baiHoc->load('cauHois', 'chuongHoc');

        if ($baiHoc->cauHois->count() === 0) {
            return redirect()->route('frontend.dashboard.khoahoc.show', ['courseSlug' => $courseSlug, 'lessonSlug' => $lessonSlug])->with('error', 'Bài học này không có câu hỏi thực hành.');
        }

        // Lấy bài học tiếp theo để chuyển hướng sau khi hoàn thành
        $khoaHoc->load(['chuongHocs' => function($q) {
            $q->orderBy('thu_tu')->with(['baiHocs' => function($q2) {
                $q2->orderBy('thu_tu')->where('trang_thai', 'published')->withCount('cauHois');
            }]);
        }]);
        $allLessons = $khoaHoc->chuongHocs->flatMap->baiHocs;
        $currentIndex = $allLessons->search(function ($item) use ($baiHoc) {
            return $item->id === $baiHoc->id;
        });
        $nextLesson = $currentIndex < $allLessons->count() - 1 ? $allLessons[$currentIndex + 1] : null;

        $tienDo = \App\Models\TienDoHoc::firstOrCreate(
            ['id_nguoi_dung' => $user->id, 'id_bai_hoc' => $baiHoc->id],
            ['phan_tram_hoan_thanh' => 0, 'da_hoan_thanh' => false, 'lan_hoc_cuoi' => now()]
        );

        if ($tienDo->da_hoan_thanh) {
            return redirect()->route('frontend.dashboard.khoahoc.show', ['courseSlug' => $courseSlug, 'lessonSlug' => $lessonSlug])
                ->with('success', 'Bạn đã hoàn thành bài thực hành này rồi!');
        }

        return view('frontend.khoahoccuatoidashboard.pronunciation', compact('khoaHoc', 'baiHoc', 'nextLesson', 'tienDo'));
    }

    public function postComment(Request $request, $id)
    {
        $request->validate([
            'noi_dung' => 'required|string|max:1000',
        ]);

        BinhLuan::create([
            'id_nguoi_dung' => Auth::id(),
            'id_bai_hoc' => $id,
            'noi_dung' => $request->noi_dung,
            'trang_thai' => 1, // Auto approve or pending depending on settings
        ]);

        return back()->with('success', 'Bình luận của bạn đã được gửi.');
    }

    public function updateProgress(Request $request, $id)
    {
        $tienDo = TienDoHoc::firstOrCreate(
            ['id_nguoi_dung' => Auth::id(), 'id_bai_hoc' => $id],
            ['phan_tram_hoan_thanh' => 0, 'da_hoan_thanh' => false]
        );

        $daHoanThanh = $request->boolean('da_hoan_thanh');
        $chuaHoanThanhTruocDo = !$tienDo->da_hoan_thanh; // Lưu trạng thái cũ
        
        $tienDo->update([
            'da_hoan_thanh' => $daHoanThanh,
            'phan_tram_hoan_thanh' => $daHoanThanh ? 100 : 0,
            'lan_hoc_cuoi' => now(),
        ]);

        if ($daHoanThanh && Auth::check()) {
            $user = Auth::user();
            $user->capNhatStreak();
            
            if ($chuaHoanThanhTruocDo) {
                $user->tangXP(50); // Thưởng 50 XP khi hoàn thành bài
            }
        }

        return response()->json(['success' => true, 'message' => 'Đã cập nhật tiến độ']);
    }
}
