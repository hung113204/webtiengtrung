<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\KhoaHoc;
use App\Models\NguoiDung;
use App\Models\DangKyKhoaHoc;
use App\Models\HoaDon;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Thực hiện các truy vấn thật thay thế mock data
        $totalCourses = KhoaHoc::count();
        $activeUsers = NguoiDung::count();
        $newRegistrationsCount = DangKyKhoaHoc::where('trang_thai', 'Chờ duyệt')->count();

        // Tính doanh thu dự kiến (từ những hóa đơn đã thanh toán)
        $revenue = HoaDon::where('trang_thai', 'Đã thanh toán')->sum('so_tien');

        if ($revenue >= 1000000) {
            $expectedRevenue = number_format($revenue / 1000000, 1) . 'M';
        } else {
            $expectedRevenue = number_format($revenue) . 'đ';
        }

        $stats = [
            'total_courses' => $totalCourses,
            'active_users' => $activeUsers,
            'expected_revenue' => $expectedRevenue,
            'new_registrations' => $newRegistrationsCount,
        ];

        // Lấy 5 đơn đăng ký khóa học gần đây nhất
        $recentRegistrations = DangKyKhoaHoc::with(['nguoiDung', 'khoaHoc.capDoHSK'])
            ->latest()
            ->take(5)
            ->get();

        $recentOrders = $recentRegistrations->map(function($reg) {
            $statusClass = 'bg-warning bg-opacity-10 text-warning-emphasis border border-warning-subtle';
            if ($reg->trang_thai === 'Đã duyệt') {
                $statusClass = 'bg-success bg-opacity-10 text-success border border-success-subtle';
            } elseif ($reg->trang_thai === 'Đã hủy') {
                $statusClass = 'bg-danger bg-opacity-10 text-danger border border-danger-subtle';
            }

            return [
                'name' => $reg->nguoiDung->ho_ten ?? 'Học viên ẩn',
                'email' => $reg->nguoiDung->email ?? '',
                'course_tag' => $reg->khoaHoc->capDoHSK->ten_cap_do ?? 'Khóa học',
                'course_name' => $reg->khoaHoc->ten_khoa_hoc ?? 'Khóa học đã xóa',
                'time' => $reg->created_at ? $reg->created_at->diffForHumans() : '',
                'status_class' => $statusClass,
                'status_text' => $reg->trang_thai,
            ];
        });

        // Dữ liệu cho biểu đồ doanh thu (4 tuần gần nhất)
        $revenueData = [];
        $revenueLabels = [];
        for ($i = 3; $i >= 0; $i--) {
            $startOfWeek = Carbon::now()->subWeeks($i)->startOfWeek();
            $endOfWeek = Carbon::now()->subWeeks($i)->endOfWeek();
            $weekRevenue = HoaDon::where('trang_thai', 'Đã thanh toán')
                                 ->whereBetween('ngay_thanh_toan', [$startOfWeek, $endOfWeek])
                                 ->sum('so_tien');
            $revenueLabels[] = 'Tuần ' . (4 - $i);
            $revenueData[] = round($weekRevenue / 1000000, 2); // Đổi ra triệu VNĐ
        }

        // Cơ cấu học viên theo cấp độ HSK
        $hskDistribution = DB::table('dang_ky_khoa_hoc')
            ->join('khoa_hoc', 'dang_ky_khoa_hoc.id_khoa_hoc', '=', 'khoa_hoc.id')
            ->join('cap_do_hsk', 'khoa_hoc.id_cap_do_hsk', '=', 'cap_do_hsk.id')
            ->select('cap_do_hsk.ten_cap_do', DB::raw('COUNT(dang_ky_khoa_hoc.id_nguoi_dung) as total'))
            ->groupBy('cap_do_hsk.ten_cap_do')
            ->get();
        
        $userStructLabels = $hskDistribution->pluck('ten_cap_do')->toArray();
        $userStructData = $hskDistribution->pluck('total')->toArray();
        if (empty($userStructLabels)) {
            $userStructLabels = ['Chưa có dữ liệu'];
            $userStructData = [1];
        }

        // Học viên mới 6 tháng gần nhất
        $userRegLabels = [];
        $userRegData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $startOfMonth = $month->copy()->startOfMonth();
            $endOfMonth = $month->copy()->endOfMonth();
            $count = NguoiDung::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            
            $userRegLabels[] = 'Tháng ' . $month->format('n');
            $userRegData[] = $count;
        }

        $chartData = [
            'revenue' => [
                'labels' => $revenueLabels,
                'data' => $revenueData
            ],
            'userStruct' => [
                'labels' => $userStructLabels,
                'data' => $userStructData
            ],
            'userReg' => [
                'labels' => $userRegLabels,
                'data' => $userRegData
            ]
        ];

        // Trả về view admin.home và truyền mảng dữ liệu vào
        return view('admin.home', compact('stats', 'recentOrders', 'chartData'));
    }
}
