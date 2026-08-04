<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NguoiDung;
use App\Models\VaiTro;
use App\Http\Requests\NguoiDungRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class NguoiDungController extends Controller
{
    /**
     * Hiển thị danh sách người dùng.
     */
    public function index(Request $request)
    {
        $query = NguoiDung::with(['vaiTro', 'hoSoHocVien'])->orderBy('id', 'desc');

        // Lọc theo từ khóa tìm kiếm (tên, email, sđt)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ho_ten', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('so_dien_thoai', 'like', "%{$search}%");
            });
        }

        // Lọc theo vai trò
        if ($request->filled('role_id')) {
            $query->where('id_vai_tro', $request->role_id);
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $status = $request->status === 'active' ? 1 : 0;
            $query->where('trang_thai', $status);
        }

        // Phân trang 20 dòng thay vì get() toàn bộ
        $nguoidungs = $query->paginate(20)->withQueryString();
        $vaiTros = VaiTro::all();
        
        return view('admin.nguoidung.index', compact('nguoidungs', 'vaiTros'));
    }

    /**
     * Lưu thông tin người dùng mới.
     */
    public function store(NguoiDungRequest $request)
    {
        $data = $request->validated();
        
        // Hash mật khẩu
        $data['mat_khau'] = Hash::make($data['mat_khau']);
        
        // Xử lý mặc định trạng thái nếu checkbox không được check
        if (!isset($data['trang_thai'])) {
            $data['trang_thai'] = 0;
        }

        // Xử lý ảnh đại diện
        if ($request->hasFile('anh_dai_dien')) {
            $data['anh_dai_dien'] = $request->file('anh_dai_dien')->store('uploads/avatars', 'public');
        }

        $user = NguoiDung::create($data);

        // Hỗ trợ AJAX (nếu form dùng fetch/AJAX như bên Danh mục khóa học)
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thêm người dùng thành công!'
            ]);
        }

        return redirect()->route('admin.nguoidung.index')
            ->with('success', 'Thêm người dùng thành công!');
    }

    /**
     * Cập nhật thông tin người dùng.
     */
    public function update(NguoiDungRequest $request, string $id)
    {
        $nguoidung = NguoiDung::findOrFail($id);
        $data = $request->validated();

        // Xử lý cập nhật mật khẩu (chỉ cập nhật nếu người quản trị có nhập mật khẩu mới)
        if (!empty($data['mat_khau'])) {
            $data['mat_khau'] = Hash::make($data['mat_khau']);
        } else {
            // Xóa khóa mat_khau khỏi mảng để không ghi đè mật khẩu cũ
            unset($data['mat_khau']);
        }

        // Xử lý mặc định trạng thái nếu checkbox không được check
        if (!isset($data['trang_thai'])) {
            $data['trang_thai'] = 0;
        }

        // Xử lý upload ảnh đại diện mới
        if ($request->hasFile('anh_dai_dien')) {
            // Xóa ảnh cũ nếu có
            if ($nguoidung->anh_dai_dien && \Illuminate\Support\Facades\Storage::disk('public')->exists($nguoidung->anh_dai_dien)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($nguoidung->anh_dai_dien);
            }
            $data['anh_dai_dien'] = $request->file('anh_dai_dien')->store('uploads/avatars', 'public');
        }

        $nguoidung->update($data);

        // Hỗ trợ AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật người dùng thành công!'
            ]);
        }

        return redirect()->route('admin.nguoidung.index')
            ->with('success', 'Cập nhật thông tin thành công!');
    }

    /**
     * Xóa người dùng.
     */
    public function destroy(Request $request, string $id)
    {
        $nguoidung = NguoiDung::findOrFail($id);
        
        // (Tùy chọn) Chặn xóa tài khoản Admin đang đăng nhập
        // if ($nguoidung->id === auth()->id()) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Không thể xóa tài khoản của chính mình!'
        //     ], 403);
        // }
        $nguoidung->forceDelete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa người dùng thành công!'
            ]);
        }

        return redirect()->route('admin.nguoidung.index')
            ->with('success', 'Xóa người dùng thành công!');
    }
}
