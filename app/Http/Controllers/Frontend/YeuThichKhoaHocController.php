<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\KhoaHoc;
use App\Models\YeuThichKhoaHoc;

class YeuThichKhoaHocController extends Controller
{
    /**
     * Hiển thị danh sách khóa học yêu thích trên Dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        $khoaHocYeuThichs = $user->khoaHocYeuThichs()
            ->with(['capDoHsk', 'giaoViens.nguoiDung'])
            ->paginate(6);
            
        return view('frontend.yeuthichdashboard.index', compact('khoaHocYeuThichs'));
    }

    /**
     * Thêm hoặc xóa khóa học khỏi danh sách yêu thích qua AJAX
     */
    public function toggle(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập để thực hiện.'], 401);
        }

        $user = Auth::user();
        $khoaHoc = KhoaHoc::findOrFail($id);

        $exists = YeuThichKhoaHoc::where('id_nguoi_dung', $user->id)
                                ->where('id_khoa_hoc', $khoaHoc->id)
                                ->exists();

        if ($exists) {
            // Đã thích -> Xóa
            YeuThichKhoaHoc::where('id_nguoi_dung', $user->id)
                           ->where('id_khoa_hoc', $khoaHoc->id)
                           ->delete();
            return response()->json(['success' => true, 'status' => 'removed', 'message' => 'Đã bỏ yêu thích khóa học.']);
        } else {
            // Chưa thích -> Thêm
            YeuThichKhoaHoc::create([
                'id_nguoi_dung' => $user->id,
                'id_khoa_hoc' => $khoaHoc->id,
            ]);
            return response()->json(['success' => true, 'status' => 'added', 'message' => 'Đã thêm vào danh sách yêu thích.']);
        }
    }
}
