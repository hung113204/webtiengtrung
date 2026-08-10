<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ThongBaoClientController extends Controller
{
    /**
     * Hiển thị danh sách thông báo của học viên
     */
    public function index()
    {
        $user = auth()->user();
        
        $notifications = $user->thongBaos()
            ->withPivot('da_doc')
            ->orderByDesc('thong_bao.created_at')
            ->paginate(15);
            
        return view('frontend.thongbao.index', compact('notifications'));
    }

    /**
     * Hiển thị chi tiết thông báo
     */
    public function show($id)
    {
        $user = auth()->user();
        
        $notification = $user->thongBaos()->where('thong_bao.id', $id)->firstOrFail();
        
        // Đánh dấu đã đọc
        if (!$notification->pivot->da_doc) {
            $user->thongBaos()->updateExistingPivot($id, ['da_doc' => true]);
        }
        
        return view('frontend.thongbao.show', compact('notification'));
    }
}
