<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\KhoaHoc;
use App\Models\DanhMucKhoaHoc;
use App\Models\BaiHoc;
class KhoaHocClientController extends Controller
{
    public function index()
    {
        $slug = request('danh_muc');

        $khoaHocs = KhoaHoc::with(['danhMucKhoaHoc.parent', 'capDoHSK', 'giaoViens'])
            ->withCount('baiHocs')
            ->withAvg(['danhGias' => fn($q) => $q->where('trang_thai', 1)], 'so_sao')
            ->withCount(['danhGias' => fn($q) => $q->where('trang_thai', 1)])
            ->where('trang_thai', 1)
            ->when($slug, function ($q) use ($slug) {
                $q->whereHas('danhMucKhoaHoc', function ($qq) use ($slug) {
                    // Tìm danh mục theo slug
                    $dm = DanhMucKhoaHoc::where('slug', $slug)->first();
                    if ($dm) {
                        // Nếu là danh mục cha → lấy cả khóa học của cha lẫn tất cả con
                        $ids = collect([$dm->id]);
                        if ($dm->children->isNotEmpty()) {
                            $ids = $ids->merge($dm->children->pluck('id'));
                        }
                        $qq->whereIn('id', $ids);
                    } else {
                        $qq->where('slug', $slug);
                    }
                });
            })
            ->orderByDesc('noi_bat')
            ->orderByDesc('created_at')
            ->get();

        $likedCourseIds = auth()->check() ? \App\Models\YeuThichKhoaHoc::where('id_nguoi_dung', auth()->id())->pluck('id_khoa_hoc')->toArray() : [];

        return view('frontend.khoahocclient.index', compact('khoaHocs', 'likedCourseIds'));
    }

    public function show($slug)
    {
        $khoaHoc = KhoaHoc::with([
            'chuongHocs' => fn($q) => $q->orderBy('thu_tu')->with([
                'baiHocs' => fn($q2) => $q2->orderBy('thu_tu'),
            ]),
            'loiIch' => fn($q) => $q->orderBy('thu_tu'),
            'yeuCau' => fn($q) => $q->orderBy('thu_tu'),
            'danhGias' => fn($q) => $q->where('trang_thai', 1)
                                       ->with('nguoiDung')
                                       ->orderByDesc('id')
                                       ->take(6),
            'giaoViens.nguoiDung',
            'capDoHSK',
            'danhMucKhoaHoc',
        ])
        ->withAvg(['danhGias' => fn($q) => $q->where('trang_thai', 1)], 'so_sao')
        ->withCount(['danhGias' => fn($q) => $q->where('trang_thai', 1)])
        ->withCount('dangKyKhoaHocs')
        ->where('trang_thai', 1)
        ->where('slug', $slug)
        ->firstOrFail();

        // Tổng số bài học & thời lượng
        $tongBaiHoc = $khoaHoc->chuongHocs->sum(fn($c) => $c->baiHocs->count());
        $tongThoiLuongGiay = $khoaHoc->chuongHocs->sum(
            fn($c) => $c->baiHocs->sum('thoi_luong_giay')
        );

        $enrollment = null;
        if (auth()->check()) {
            $enrollment = \App\Models\DangKyKhoaHoc::where('id_nguoi_dung', auth()->id())
                ->where('id_khoa_hoc', $khoaHoc->id)
                ->first();
        }

        $isFavorited = auth()->check() ? \App\Models\YeuThichKhoaHoc::where('id_nguoi_dung', auth()->id())->where('id_khoa_hoc', $khoaHoc->id)->exists() : false;

        return view('frontend.khoahocclient.show', compact('khoaHoc', 'tongBaiHoc', 'tongThoiLuongGiay', 'enrollment', 'isFavorited'));
    }
   public function trialLesson($baiHocSlug)
{
    // 1. Lấy bài học theo slug
    $baiHoc = BaiHoc::with([
        'chuongHoc.khoaHoc',
        'tuVungs' => fn($q) => $q->orderBy('id'),
        'nguPhaps' => fn($q) => $q->orderBy('id'),
        'hoiThoais.chiTietHoiThoais',
        'luyenViets',
        'cauHois.dapAns'
    ])
    ->where('slug', $baiHocSlug)
    ->where('trang_thai', 'published')
    ->firstOrFail();

    $khoaHoc = $baiHoc->chuongHoc->khoaHoc;

    // 2. Load danh sách chương và bài học để hiển thị sidebar
    $khoaHoc->load(['chuongHocs' => function($q) {
        $q->orderBy('thu_tu')->with(['baiHocs' => function($q2) {
            $q2->orderBy('thu_tu')->where('trang_thai', 'published');
        }]);
    }]);

    // 3. Tổng số bài học và thời lượng (nếu cần)
    $tongBaiHoc = $khoaHoc->chuongHocs->sum(fn($c) => $c->baiHocs->count());
    $tongThoiLuongGiay = $khoaHoc->chuongHocs->sum(
        fn($c) => $c->baiHocs->sum('thoi_luong_giay')
    );

    // 4. Trả về view hocthu.index (đã tạo từ hướng dẫn trước)
    return view('frontend.hocthu.index', compact('khoaHoc', 'baiHoc', 'tongBaiHoc', 'tongThoiLuongGiay'));
}

    public function storeReview(\Illuminate\Http\Request $request, $slug)
    {
        $request->validate([
            'so_sao' => 'required|integer|min:1|max:5',
            'noi_dung' => 'nullable|string|max:1000',
        ]);

        $khoaHoc = KhoaHoc::where('slug', $slug)->firstOrFail();

        \App\Models\DanhGia::create([
            'id_nguoi_dung' => auth()->id(),
            'id_khoa_hoc' => $khoaHoc->id,
            'so_sao' => $request->so_sao,
            'noi_dung' => $request->noi_dung,
            'trang_thai' => 1, // Auto-approve or you could set to 0 to require admin approval
        ]);

        return back()->with('success', 'Cảm ơn bạn đã đánh giá khóa học!');
    }

    public function register(\Illuminate\Http\Request $request, $slug)
    {
        $khoaHoc = KhoaHoc::where('slug', $slug)->firstOrFail();
        $user = auth()->user();

        // 1. Kiểm tra xem đã đăng ký khóa này chưa
        $daDangKy = \App\Models\DangKyKhoaHoc::where('id_nguoi_dung', $user->id)
            ->where('id_khoa_hoc', $khoaHoc->id)
            ->first();

        if ($daDangKy) {
            if ($daDangKy->trang_thai == 'Đã duyệt') {
                return redirect()->route('frontend.dashboard.khoahoc.show', $khoaHoc->slug)
                    ->with('info', 'Bạn đã đăng ký và đang học khóa này rồi.');
            }

            if ($daDangKy->trang_thai == 'Chờ duyệt') {
                $hoaDon = \App\Models\HoaDon::where('id_dang_ky', $daDangKy->id)
                                            ->where('trang_thai', 'Chưa thanh toán')
                                            ->first();

                if ($hoaDon) {
                    return redirect()->route('khoahoc.checkout', $hoaDon->ma_hoa_don);
                } else {
                    return back()->with('info', 'Khóa học đang chờ duyệt. Vui lòng liên hệ Admin.');
                }
            }
            
            // Nếu trạng thái là Đã hủy, có thể cho phép tạo mới hoặc khôi phục (Ở đây cho phép tạo đăng ký mới bên dưới)
        }

        // 2. Tạo bản ghi đăng ký khóa học
        $dangKy = \App\Models\DangKyKhoaHoc::create([
            'id_nguoi_dung' => $user->id,
            'id_khoa_hoc' => $khoaHoc->id,
            'ngay_dang_ky' => now(),
            'trang_thai' => 'Chờ duyệt',
        ]);

        // 3. Lấy giá khóa học
        $giaToPay = $khoaHoc->gia_giam && $khoaHoc->gia_giam < $khoaHoc->gia 
            ? $khoaHoc->gia_giam 
            : ($khoaHoc->gia ?? 0);

        // 4. Tạo hóa đơn
        $maHoaDon = 'HB' . strtoupper(uniqid()) . $user->id;

        $hoaDon = \App\Models\HoaDon::create([
            'ma_hoa_don' => $maHoaDon,
            'id_dang_ky' => $dangKy->id,
            'id_nguoi_dung' => $user->id,
            'so_tien' => $giaToPay,
            'phuong_thuc_thanh_toan' => 'Chuyển khoản',
            'trang_thai' => 'Chưa thanh toán',
        ]);

        // Trả về JSON cho Ajax hoặc redirect tùy ý. Ở đây có vẻ chúng ta dùng AJAX ở FE checkout.
        // Tuy nhiên nút "Đăng ký khóa học" ở chi tiết khóa học nên redirect tới checkout luôn.
        return redirect()->route('khoahoc.checkout', $hoaDon->ma_hoa_don);
    }

    public function checkout($ma_hoa_don)
    {
        $hoaDon = \App\Models\HoaDon::with(['dangKyKhoaHoc.khoaHoc'])
            ->where('ma_hoa_don', $ma_hoa_don)
            ->where('id_nguoi_dung', auth()->id())
            ->firstOrFail();

        // Nếu đã thanh toán rồi thì redirect về khoá học của tôi
        if ($hoaDon->trang_thai == 'Đã thanh toán') {
            return redirect()->route('frontend.dashboard')->with('success', 'Hóa đơn này đã được thanh toán thành công.');
        }

        $khoaHoc = $hoaDon->dangKyKhoaHoc->khoaHoc;

        return view('frontend.checkout.index', compact('hoaDon', 'khoaHoc'));
    }

    public function processPayment(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'ma_hoa_don' => 'required|string',
            'payment_method' => 'required|string|in:vnpay,manual',
        ]);

        $hoaDon = \App\Models\HoaDon::where('ma_hoa_don', $request->ma_hoa_don)
            ->where('id_nguoi_dung', auth()->id())
            ->firstOrFail();

        if ($request->payment_method === 'manual') {
            return redirect()->route('frontend.dashboard.khoahoc')->with('success', 'Bạn đã chọn chuyển khoản thủ công. Vui lòng thanh toán và chờ Admin duyệt.');
        }

        // Xử lý VNPay
        $tmnCode = \App\Models\CauHinh::where('key', 'vnpay_tmncode')->value('value');
        $hashSecret = \App\Models\CauHinh::where('key', 'vnpay_hashsecret')->value('value');
        $env = \App\Models\CauHinh::where('key', 'vnpay_environment')->value('value') ?? 'sandbox';

        if (!$tmnCode || !$hashSecret) {
            return back()->with('error', 'Cấu hình VNPay chưa hoàn thiện. Vui lòng liên hệ Admin.');
        }

        $vnp_Url = $env === 'production' ? "https://pay.vnpay.vn/vpcpay.html" : "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = route('khoahoc.vnpayReturn');

        $vnp_TxnRef = $hoaDon->ma_hoa_don . '_' . time(); // Thêm time để tránh trùng TxnRef nếu retry
        $vnp_OrderInfo = "Thanh toan khoa hoc ma don: " . $hoaDon->ma_hoa_don;
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $hoaDon->so_tien * 100;
        $vnp_Locale = 'vn';
        $vnp_BankCode = ''; // Để trống để khách chọn trên cổng VNPay
        $vnp_IpAddr = $request->ip();

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $tmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($hashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $hashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return redirect($vnp_Url);
    }

    public function vnpayReturn(\Illuminate\Http\Request $request)
    {
        $vnp_SecureHash = $request->vnp_SecureHash;
        $inputData = array();
        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $hashSecret = \App\Models\CauHinh::where('key', 'vnpay_hashsecret')->value('value');
        $secureHash = hash_hmac('sha512', $hashData, $hashSecret);

        $parts = explode('_', $request->vnp_TxnRef);
        $maHoaDon = $parts[0];

        if ($secureHash == $vnp_SecureHash) {
            if ($request->vnp_ResponseCode == '00') {
                // Thành công
                $hoaDon = \App\Models\HoaDon::where('ma_hoa_don', $maHoaDon)->first();
                if ($hoaDon && $hoaDon->trang_thai != 'Đã thanh toán') {
                    $hoaDon->update([
                        'trang_thai' => 'Đã thanh toán',
                        'phuong_thuc_thanh_toan' => 'VNPay',
                    ]);
                    
                    // Cập nhật DangKyKhoaHoc
                    $dangKy = \App\Models\DangKyKhoaHoc::find($hoaDon->id_dang_ky);
                    if ($dangKy) {
                        $dangKy->update(['trang_thai' => 'Đã duyệt']);
                    }
                }
                
                return redirect()->route('frontend.dashboard.khoahoc')->with('success', 'Thanh toán khóa học thành công!');
            } else {
                return redirect()->route('khoahoc.checkout', $maHoaDon)->with('error', 'Giao dịch thanh toán thất bại (Mã lỗi: ' . $request->vnp_ResponseCode . ').');
            }
        } else {
            return redirect()->route('frontend.dashboard')->with('error', 'Chữ ký VNPay không hợp lệ.');
        }
    }
}
