<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DangKyKhoaHoc;
use App\Models\KhoaHoc;
use App\Models\ChuongHoc;
use App\Models\BaiHoc;
use App\Models\TienDoHoc;
use App\Models\ChungChi;

class TienDoController extends Controller
{
    public function index(Request $request)
    {
        $query = DangKyKhoaHoc::with(['nguoiDung', 'khoaHoc'])->latest();

        // Tìm kiếm theo tên hoặc email học viên
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('nguoiDung', function ($q) use ($search) {
                $q->where('ho_ten', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Lọc theo khóa học
        if ($request->filled('id_khoa_hoc')) {
            $query->where('id_khoa_hoc', $request->id_khoa_hoc);
        }

        // Lọc theo mức độ hoàn thành (0-20, 20-80, 80-100)
        // Lưu ý: Lọc này sẽ thực hiện sau khi truy vấn thô, hoặc ta tính toán trực tiếp.
        // Vì tính toán động, ta có thể lấy danh sách hết rồi lọc hoặc thực hiện subquery.
        // Để đơn giản và chính xác nhất, ta lấy dữ liệu thô và phân trang.

        $registrations = $query->paginate(20)->withQueryString();

        // Tính toán chi tiết tiến độ
        foreach ($registrations as $reg) {
            $chapterIds = ChuongHoc::where('id_khoa_hoc', $reg->id_khoa_hoc)->pluck('id');
            $lessonIds = BaiHoc::whereIn('id_chuong', $chapterIds)->pluck('id');
            
            $totalLessons = $lessonIds->count();
            
            if ($totalLessons > 0) {
                $completedLessons = TienDoHoc::where('id_nguoi_dung', $reg->id_nguoi_dung)
                    ->whereIn('id_bai_hoc', $lessonIds)
                    ->where('da_hoan_thanh', true)
                    ->count();
                
                $reg->completed_lessons = $completedLessons;
                $reg->total_lessons = $totalLessons;
                $reg->progress_percent = round(($completedLessons / $totalLessons) * 100);
            } else {
                $reg->completed_lessons = 0;
                $reg->total_lessons = 0;
                $reg->progress_percent = 0;
            }

            // Thời gian học cuối
            $lastStudy = TienDoHoc::where('id_nguoi_dung', $reg->id_nguoi_dung)
                ->whereIn('id_bai_hoc', $lessonIds)
                ->max('lan_hoc_cuoi');
            
            $reg->last_study_at = $lastStudy ? \Carbon\Carbon::parse($lastStudy) : null;
        }

        // Tính toán thống kê
        $totalStudents = DangKyKhoaHoc::distinct('id_nguoi_dung')->count('id_nguoi_dung');
        $totalCertificates = ChungChi::count();

        // Tính tỷ lệ hoàn thành trung bình
        $allRegs = DangKyKhoaHoc::all();
        $totalPercent = 0;
        $countPercent = 0;
        foreach ($allRegs as $r) {
            $cIds = ChuongHoc::where('id_khoa_hoc', $r->id_khoa_hoc)->pluck('id');
            $lIds = BaiHoc::whereIn('id_chuong', $cIds)->pluck('id');
            $tot = $lIds->count();
            if ($tot > 0) {
                $comp = TienDoHoc::where('id_nguoi_dung', $r->id_nguoi_dung)
                    ->whereIn('id_bai_hoc', $lIds)
                    ->where('da_hoan_thanh', true)
                    ->count();
                $totalPercent += ($comp / $tot) * 100;
            }
            $countPercent++;
        }
        $avgProgress = $countPercent > 0 ? round($totalPercent / $countPercent) : 0;

        $khoaHocs = KhoaHoc::select('id', 'ten_khoa_hoc')->get();

        return view('admin.tiendo.index', compact(
            'registrations', 
            'khoaHocs', 
            'totalStudents', 
            'totalCertificates', 
            'avgProgress'
        ));
    }

    public function show($id)
    {
        $dangKy = DangKyKhoaHoc::with(['nguoiDung', 'khoaHoc.chuongHocs.baiHocs'])->findOrFail($id);
        
        $khoaHoc = $dangKy->khoaHoc;
        
        // Lấy danh sách ID các bài học trong khóa học
        $lessonIds = [];
        foreach ($khoaHoc->chuongHocs as $chuong) {
            foreach ($chuong->baiHocs as $baiHoc) {
                $lessonIds[] = $baiHoc->id;
            }
        }

        // Lấy dữ liệu tiến độ của học viên này cho các bài học trên
        $tienDos = TienDoHoc::where('id_nguoi_dung', $dangKy->id_nguoi_dung)
            ->whereIn('id_bai_hoc', $lessonIds)
            ->get()
            ->keyBy('id_bai_hoc');

        // Tính tổng quan tiến độ của học viên này
        $totalLessons = count($lessonIds);
        $completedLessons = $tienDos->where('da_hoan_thanh', true)->count();
        $progressPercent = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;

        return view('admin.tiendo.show', compact('dangKy', 'khoaHoc', 'tienDos', 'totalLessons', 'completedLessons', 'progressPercent'));
    }
}
