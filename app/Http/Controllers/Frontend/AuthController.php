<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\Frontend\RegisterRequest;
use App\Http\Requests\Frontend\LoginRequest;
use App\Models\NguoiDung;
use App\Models\VaiTro;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * Hiển thị trang đăng nhập cho Client (Học viên).
     */
    public function index()
    {
        // Nếu đã đăng nhập, chuyển hướng về trang chủ
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('frontend.loginclient.index');
    }

    /**
     * Hiển thị trang đăng ký cho Client.
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('frontend.registerclient.index');
    }

    /**
     * Xử lý đăng ký Client.
     */
    public function register(RegisterRequest $request)
    {
        // Lấy vai trò học viên (mặc định)
        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $userToken = \Illuminate\Support\Str::random(60);
            $username = \Illuminate\Support\Str::before($request->email, '@') . '_' . rand(1000, 9999);
            
            $user = NguoiDung::create([
                'ho_ten'       => $request->fullName,
                'email'        => $request->email,
                'so_dien_thoai'=> $request->phone,
                'ten_dang_nhap'=> $username,
                'mat_khau'     => \Illuminate\Support\Facades\Hash::make($request->password),
                'id_vai_tro'   => 3,
                'trang_thai'   => 0, // Chưa kích hoạt
                'is_first_login'=> 1,
                'user_token'   => $userToken,
                'ghi_chu'      => null
            ]);

            // Lưu hồ sơ học viên
            if ($request->level || $request->goal) {
                \App\Models\HoSoHocVien::create([
                    'id_nguoi_dung'     => $user->id,
                    'trinh_do_hien_tai' => $request->level,
                    'muc_tieu_hoc_tap'  => $request->goal
                ]);
            }

            // Gửi email xác thực
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\VerifyUserEmail($user, $userToken));

            \Illuminate\Support\Facades\DB::commit();

            $intendedUrl = redirect()->intended(route('frontend.dashboard'))->getTargetUrl();
            return response()->json([
                'status'  => 'success',
                'message' => 'Đăng ký thành công! Vui lòng kiểm tra email để kích hoạt tài khoản.',
                'redirect'=> route('login')
            ], 201);
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Lỗi đăng ký tài khoản (Gửi Email): ' . $e->getMessage());
            
            return response()->json([
                'status'  => 'error',
                'message' => 'Hệ thống đang bảo trì dịch vụ gửi email. Vui lòng thử lại sau hoặc đăng nhập bằng Google. (Tài khoản của bạn chưa được tạo)'
            ], 500);
        }
    }

    /**
     * Kiểm tra email đã tồn tại hay chưa (Ajax)
     */
    public function checkEmail(Request $request)
    {
        $exists = NguoiDung::where('email', $request->email)->exists();
            $intendedUrl = redirect()->intended(route('frontend.dashboard'))->getTargetUrl();
        return response()->json(['exists' => $exists]);
    }

    /**
     * Xác thực email.
     */
    public function verifyEmail($token)
    {
        $user = NguoiDung::where('user_token', $token)->first();

        if (!$user) {
            return view('frontend.registerclient.verify_success', [
                'status' => 'error',
                'title' => 'Liên kết xác thực không hợp lệ',
                'message' => 'Mã xác thực không hợp lệ hoặc đã hết hạn. Vui lòng quay lại trang đăng nhập để tiếp tục.',
            ]);
        }

        if ($user->trang_thai == 1) {
            return view('frontend.registerclient.verify_success', [
                'status' => 'success',
                'title' => 'Tài khoản đã được kích hoạt',
                'message' => 'Tài khoản của bạn đã được kích hoạt từ trước. Bạn có thể quay lại trang đăng nhập để tiếp tục sử dụng Hányǔ Bàn.',
            ]);
        }

        $user->trang_thai = 1;
        $user->email_verified_at = now();
        $user->user_token = null;
        $user->save();

        return view('frontend.registerclient.verify_success', [
            'status' => 'success',
            'title' => 'Xác thực thành công!',
            'message' => 'Tài khoản của bạn đã được kích hoạt thành công. Bạn đã có thể sử dụng đầy đủ các tính năng của Hányǔ Bàn.',
        ]);
    }

    /**
     * Xử lý đăng nhập Client.
     */
    public function login(LoginRequest $request)
    {
        $credentials = [
            'password' => $request->password,
        ];

        // Có thể đăng nhập bằng email hoặc ten_dang_nhap
        $loginField = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'ten_dang_nhap';
        $credentials[$loginField] = $request->email;
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if ($user->trang_thai == 0) {
                Auth::logout();
                $reason = $user->user_token 
                    ? 'Tài khoản chưa được kích hoạt. Vui lòng kiểm tra email của bạn để xác thực.' 
                    : 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.';
                
                return response()->json([
                    'success' => false,
                    'message' => $reason
                ], 403);
            }

            // Đăng nhập thành công, có thể thêm logic kiểm tra quyền học viên ở đây nếu cần
            $request->session()->regenerate();
            
            // Cập nhật thời điểm đăng nhập cuối cùng
            $user->update(['last_login_at' => now()]);
            $user->capNhatStreak();
            
            // Lấy URL định hướng trước đó (nếu có), mặc định về Dashboard
            $intendedUrl = redirect()->intended(route('frontend.dashboard'))->getTargetUrl();
            
            return response()->json([
                'success'  => true,
                'message'  => 'Đăng nhập thành công!',
                'redirect' => $intendedUrl // Chuyển về link cũ hoặc dashboard của học viên
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Email/Tên đăng nhập hoặc mật khẩu không chính xác.'
        ], 401);
    }

    /**
     * Xử lý đăng xuất Client.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Chuyển hướng người dùng sang trang đăng nhập của Google.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Xử lý callback từ Google.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Tìm user theo google_id
            $user = NguoiDung::where('google_id', $googleUser->id)->first();
            
            if ($user) {
                // Đã liên kết google_id
                Auth::login($user);
                $user->update(['last_login_at' => now()]);
                $user->capNhatStreak();
                return redirect()->intended(route('frontend.dashboard'));
            }

            // Tìm user theo email
            $user = NguoiDung::where('email', $googleUser->email)->first();

            if ($user) {
                // Đã có tài khoản bằng email này, liên kết google_id
                $user->update(['google_id' => $googleUser->id]);
                Auth::login($user);
                $user->update(['last_login_at' => now()]);
                $user->capNhatStreak();
                return redirect()->intended(route('frontend.dashboard'));
            }

            // Tạo tài khoản mới
            $userToken = Str::random(60);
            $username = Str::before($googleUser->email, '@') . '_' . rand(1000, 9999);

            $user = NguoiDung::create([
                'ho_ten'       => $googleUser->name,
                'email'        => $googleUser->email,
                'ten_dang_nhap'=> $username,
                'mat_khau'     => Hash::make(Str::random(16)), // Mật khẩu ngẫu nhiên
                'id_vai_tro'   => 3, // Học viên
                'trang_thai'   => 1, // Kích hoạt luôn
                'is_first_login'=> 1,
                'user_token'   => null,
                'google_id'    => $googleUser->id,
                'anh_dai_dien' => $googleUser->avatar
            ]);

            Auth::login($user);
            $user->update(['last_login_at' => now()]);
            $user->capNhatStreak();

            return redirect()->intended(route('frontend.dashboard'));

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Đăng nhập Google thất bại: ' . $e->getMessage());
        }
    }
}



