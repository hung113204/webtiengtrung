<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\KhoaHocClientController;
use App\Http\Controllers\Frontend\LoTrinhClientController;
use App\Http\Controllers\Frontend\TinhNangClientController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/dang-ky-hoc-thu', [HomeController::class, 'dangKyHocThu'])->name('frontend.dangkyhocthu');
Route::get('/tinh-nang', [TinhNangClientController::class, 'index'])->name('tinhnang.index');

// Frontend: Danh sách khóa học
Route::get('/khoa-hoc', [KhoaHocClientController::class, 'index'])->name('khoahoc.index');
Route::get('/khoa-hoc/{slug}', [KhoaHocClientController::class, 'show'])->name('khoahoc.show');
Route::post('/khoa-hoc/{slug}/danh-gia', [KhoaHocClientController::class, 'storeReview'])->name('khoahoc.review')->middleware('auth');

use App\Http\Controllers\Frontend\YeuThichKhoaHocController;
Route::post('/khoa-hoc/{id}/yeu-thich', [YeuThichKhoaHocController::class, 'toggle'])->name('khoahoc.yeuthich.toggle')->middleware('auth');

// Đăng ký khóa học và Thanh toán
Route::post('/khoa-hoc/{slug}/dang-ky', [KhoaHocClientController::class, 'register'])->name('khoahoc.register')->middleware('auth');
Route::get('/thanh-toan/{ma_hoa_don}', [KhoaHocClientController::class, 'checkout'])->name('khoahoc.checkout')->middleware('auth');
Route::post('/thanh-toan/xu-ly', [KhoaHocClientController::class, 'processPayment'])->name('khoahoc.processPayment')->middleware('auth');
Route::get('/thanh-toan/vnpay-return', [KhoaHocClientController::class, 'vnpayReturn'])->name('khoahoc.vnpayReturn');

// Frontend: Lộ trình học tập
Route::get('/lo-trinh', [LoTrinhClientController::class, 'index'])->name('lotrinh.index');
Route::get('/bai-hoc/{slug}/hocthu', [KhoaHocClientController::class, 'trialLesson'])->name('baihoc.trial');

use App\Http\Controllers\Frontend\AuthController as FrontendAuthController;

// Dashboard Học viên (Frontend)
Route::middleware(['web', 'auth'])->group(base_path('routes/dashboard.php'));

// Frontend Auth Routes
Route::get('login', [FrontendAuthController::class, 'index'])->name('login');
Route::post('login', [FrontendAuthController::class, 'login']);
Route::get('register', [FrontendAuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('register', [FrontendAuthController::class, 'register'])->name('register');
Route::post('check-email', [FrontendAuthController::class, 'checkEmail'])->name('check.email');
Route::get('verify-email/{token}', [FrontendAuthController::class, 'verifyEmail'])->name('verify.email');
Route::post('logout', [FrontendAuthController::class, 'logout'])->name('logout');

// Google Auth Routes
Route::post('keepalive', function () {
    request()->session()->put('last_activity', time());
    return response()->json(['status' => 'ok']);
})->middleware(['web', 'auth'])->name('keepalive');

Route::get('session-check', function () {
    return response()->json(['authenticated' => auth()->check()]);
})->middleware(['web'])->name('session.check');

Route::get('auth/google', [FrontendAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [FrontendAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Include Admin Routes
require base_path('routes/admin.php');
