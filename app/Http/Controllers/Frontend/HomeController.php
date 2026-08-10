<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KhoaHoc;
use App\Models\NguoiDung;
use App\Models\BaiHoc;
use App\Models\DeThi;
use App\Models\DanhGia;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        // Cache 5 phút để tránh việc cập nhật không hiển thị (trước đây là 1 tiếng)
        $featuredCourses = Cache::remember('home_featured_courses', 60*5, function () {
            return KhoaHoc::with(['capDoHSK', 'giaoViens.nguoiDung'])
                ->withAvg(['danhGias' => function($q) { $q->where('trang_thai', 1); }], 'so_sao')
                ->withCount(['danhGias' => function($q) { $q->where('trang_thai', 1); }])
                ->withCount('baiHocs')
                ->where('trang_thai', 1)
                ->where('noi_bat', 1)
                ->take(4)
                ->get();
        });

        $stats = Cache::remember('home_stats', 60*60, function () {
            return [
                'students' => NguoiDung::count(),
                'lessons' => BaiHoc::count(),
                'exams' => DeThi::count(),
                'satisfaction' => 98
            ];
        });

        $testimonials = Cache::remember('home_testimonials', 60*60, function () {
            return DanhGia::with(['nguoiDung', 'khoaHoc'])
                ->where('trang_thai', 1)
                ->orderBy('id', 'desc')
                ->get();
        });

        $loTrinh = Cache::remember('home_lotrinh', 60*60, function () {
            return \App\Models\LoTrinh::with('giaiDoans')
                ->where('trang_thai', 1)
                ->orderBy('thu_tu', 'asc')
                ->first();
        });

        $banner = \App\Models\Banner::where('is_active', true)->orderBy('thu_tu')->first();

        return view('frontend.home', compact('featuredCourses', 'stats', 'testimonials', 'loTrinh', 'banner'));
    }

    public function dangKyHocThu(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $exists = \App\Models\DangKyHocThu::where('email', $request->email)->exists();
        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email này đã được đăng ký học thử trước đó. Vui lòng kiểm tra lại hòm thư của bạn.'
            ], 400);
        }

        try {
            \App\Models\DangKyHocThu::create([
                'email' => $request->email,
                'trang_thai' => 0
            ]);

            // Gửi email
            \Illuminate\Support\Facades\Mail::to($request->email)->send(new \App\Mail\DangKyHocThuMail($request->email));

            return response()->json([
                'status' => 'success',
                'message' => 'Đăng ký thành công! Vui lòng kiểm tra hòm thư email của bạn (bao gồm cả thư mục Spam/Quảng cáo).'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra, vui lòng thử lại sau.'
            ], 500);
        }
    }
}
