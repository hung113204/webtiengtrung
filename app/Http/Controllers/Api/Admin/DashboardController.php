<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Lấy dữ liệu thống kê tổng quan (Thẻ thông tin)
     */
    public function getStats()
    {
        // Hiện tại đang trả về dữ liệu mẫu (Mock data). 
        // Sau này sẽ query từ Database: Course::count(), User::where('active', 1)->count(), v.v.
        return response()->json([
            'status' => 'success',
            'data' => [
                'total_courses' => 24,
                'active_users' => 1482,
                'expected_revenue' => 48500000,
                'new_registrations' => 18,
            ]
        ]);
    }

    /**
     * Lấy dữ liệu cho biểu đồ (Charts)
     */
    public function getChartData()
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'revenue' => [
                    'labels' => ['Tuần 1', 'Tuần 2', 'Tuần 3', 'Tuần 4'],
                    'values' => [12.5, 15.2, 11.8, 18.5]
                ],
                'user_structure' => [
                    'labels' => ['Giao tiếp', 'Luyện thi HSK', 'Thương mại', 'Khác'],
                    'values' => [45, 30, 15, 10]
                ]
            ]
        ]);
    }
}
