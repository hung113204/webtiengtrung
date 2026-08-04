<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TuVungDashboardController extends Controller
{
    /**
     * Display the vocabulary dashboard for student.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Hiện tại dùng dữ liệu tĩnh cho demo, 
        // Sau này có thể query từ bảng TuVung / NguoiDungHocTuVung
        return view('frontend.tuvungdashboard.index');
    }
}
