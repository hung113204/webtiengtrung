<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BinhLuan;
use App\Models\BaiHoc;
use Illuminate\Support\Facades\Auth;

class BinhLuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Chỉ lấy các bình luận gốc (parent_id is null) để sắp xếp hiển thị theo dạng luồng hội thoại
        $query = BinhLuan::with(['nguoiDung', 'baiHoc.chuongHoc.khoaHoc', 'replies.nguoiDung'])
            ->whereNull('parent_id')
            ->latest();

        // Tìm kiếm theo nội dung bình luận hoặc tên/email người dùng
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('noi_dung', 'like', "%{$search}%")
                  ->orWhereHas('nguoiDung', function ($uq) use ($search) {
                      $uq->where('ho_ten', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Lọc theo bài học
        if ($request->filled('id_bai_hoc')) {
            $query->where('id_bai_hoc', $request->id_bai_hoc);
        }

        // Lọc theo trạng thái
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        $binhLuans = $query->paginate(15)->withQueryString();
        $baiHocs = BaiHoc::select('id', 'ten_bai_hoc')->get();

        // Thống kê
        $totalComments = BinhLuan::count();
        $todayComments = BinhLuan::whereDate('created_at', today())->count();
        $repliedComments = BinhLuan::whereNotNull('parent_id')->count();

        return view('admin.binhluan.index', compact(
            'binhLuans', 
            'baiHocs', 
            'totalComments', 
            'todayComments', 
            'repliedComments'
        ));
    }

    /**
     * Update the specified resource in storage (Toggle status).
     */
    public function update(Request $request, string $id)
    {
        $binhLuan = BinhLuan::findOrFail($id);
        
        $binhLuan->update([
            'trang_thai' => !$binhLuan->trang_thai
        ]);

        $statusText = $binhLuan->trang_thai ? 'Hiển thị' : 'Ẩn';
        return redirect()->route('admin.binhluan.index')->with('success', "Đã chuyển trạng thái bình luận sang: {$statusText}!");
    }

    /**
     * Reply to a comment.
     */
    public function reply(Request $request, string $id)
    {
        $request->validate([
            'noi_dung' => 'required|string|max:1000',
        ]);

        $parent = BinhLuan::findOrFail($id);

        BinhLuan::create([
            'id_nguoi_dung' => Auth::id() ?? 1, // Fallback admin user id
            'id_bai_hoc' => $parent->id_bai_hoc,
            'noi_dung' => $request->noi_dung,
            'parent_id' => $parent->id,
            'trang_thai' => true,
        ]);

        return redirect()->route('admin.binhluan.index')->with('success', 'Đã gửi phản hồi bình luận thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $binhLuan = BinhLuan::findOrFail($id);
        $binhLuan->delete();

        return redirect()->route('admin.binhluan.index')->with('success', 'Đã xóa bình luận thành công!');
    }
}
