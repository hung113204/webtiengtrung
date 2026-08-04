<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardClientController extends Controller
{
    /**
     * Display the user dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        
        $hoSo = \App\Models\HoSoHocVien::where('id_nguoi_dung', $user->id)->first();

        // TỰ ĐỘNG HÓA LUỒNG HOẠT ĐỘNG
        if (!$hoSo) {
            return redirect()->route('frontend.dashboard.onboarding');
        }

        if (empty($hoSo->lo_trinh_ai)) {
            // Đã có hồ sơ nhưng chưa sinh lộ trình AI -> tự động tạo
            return redirect()->route('frontend.dashboard.lotrinh_ai')->with('auto_generate', true);
        }

        // Lấy danh sách khóa học người dùng đã đăng ký
        $khoaHocDangKys = \App\Models\DangKyKhoaHoc::with(['khoaHoc.baiHocs' => function ($query) {
            $query->where('bai_hoc.trang_thai', 'published');
        }])->where('id_nguoi_dung', $user->id)
           ->where('trang_thai', 'Đã duyệt')
           ->get()
           ->unique('id_khoa_hoc');
           
        $soKhoaHoc = $khoaHocDangKys->count();

        // Lấy tiến độ học của user
        $tienDoHocs = \App\Models\TienDoHoc::where('id_nguoi_dung', $user->id)
            ->where('da_hoan_thanh', true)
            ->pluck('da_hoan_thanh', 'id_bai_hoc');

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
            } else {
                $dk->phan_tram_hoan_thanh = 0;
            }
        }

        // Phân trang thủ công cho Collection (4 khóa học / trang)
        $page = request()->get('page', 1);
        $perPage = 4;
        $totalKhoaHoc = $khoaHocDangKys->count();
        $khoaHocDangKysPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $khoaHocDangKys->forPage($page, $perPage),
            $totalKhoaHoc,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // Lấy các tiến độ học tập gần đây (nếu có)
        $hoatDongs = \App\Models\TienDoHoc::with('baiHoc.chuongHoc.khoaHoc')
            ->where('id_nguoi_dung', $user->id)
            ->orderBy('lan_hoc_cuoi', 'desc')
            ->take(5)
            ->get();

        // Lấy danh sách ID khóa học yêu thích
        $yeuThichIds = \App\Models\YeuThichKhoaHoc::where('id_nguoi_dung', $user->id)
            ->pluck('id_khoa_hoc')
            ->toArray();

        return view('frontend.dashboardclient.index', [
            'user' => $user,
            'hoSo' => $hoSo,
            'khoaHocDangKys' => $khoaHocDangKysPaginated,
            'soKhoaHoc' => $soKhoaHoc,
            'hoatDongs' => $hoatDongs,
            'yeuThichIds' => $yeuThichIds
        ]);
    }

    /**
     * Hiển thị trang Onboarding (Kiểm tra trình độ & mục tiêu)
     */
    public function onboarding()
    {
        $user = Auth::user();
        $hoSo = \App\Models\HoSoHocVien::where('id_nguoi_dung', $user->id)->first();
        
        // Nếu đã có hồ sơ và đã có lộ trình, không cho quay lại onboarding
        if ($hoSo && !empty($hoSo->lo_trinh_ai)) {
            return redirect()->route('frontend.dashboard');
        }

        return view('frontend.dashboardclient.onboarding', compact('user'));
    }

    /**
     * Lưu thông tin Onboarding
     */
    public function saveOnboarding(Request $request)
    {
        $request->validate([
            'level' => 'required|string|max:50',
            'goal'  => 'required|string|max:255',
        ]);

        $user = Auth::user();
        
        \App\Models\HoSoHocVien::updateOrCreate(
            ['id_nguoi_dung' => $user->id],
            [
                'trinh_do_hien_tai' => $request->level,
                'muc_tieu_hoc_tap'  => $request->goal,
            ]
        );

        // Sau khi lưu xong, trả về Dashboard (lúc này Dashboard sẽ tự động push qua trang AI)
        return redirect()->route('frontend.dashboard');
    }
}
