<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.home');
        }
        return view('admin.login.index');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'mat_khau' => 'required'
        ]);

        $credentials = [
            'password' => $request->mat_khau,
        ];

        // Có thể đăng nhập bằng email hoặc ten_dang_nhap
        $loginField = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'ten_dang_nhap';
        $credentials[$loginField] = $request->email;
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            // Kiểm tra xem có phải admin/giáo viên không
            if (Auth::user()->isAdmin() || Auth::user()->isTeacher()) {
                $request->session()->regenerate();
                // Cập nhật thời điểm đăng nhập cuối cùng
                Auth::user()->update(['last_login_at' => now()]);
                Auth::user()->capNhatStreak();
                return response()->json([
                    'success'  => true,
                    'message'  => 'Đăng nhập thành công',
                    'redirect' => route('admin.home')
                ]);
            }
            
            // Nếu không phải admin thì không cho vào Admin Panel
            Auth::logout();
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền truy cập khu vực quản trị.'
            ], 403);
        }

        return response()->json([
            'success' => false,
            'message' => 'Tài khoản hoặc mật khẩu không chính xác.'
        ], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
