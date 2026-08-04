<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\DashboardClientController;

use App\Http\Controllers\Frontend\KhoaHocCuaToiController;
use App\Http\Controllers\Frontend\TuVungDashboardController;
use App\Http\Controllers\Frontend\LuyentapClientController;
use App\Http\Controllers\Frontend\AiLoTrinhController;
use App\Http\Controllers\Frontend\YeuThichKhoaHocController;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Các route dành riêng cho trang Dashboard của học viên (Client).
| File này đã được áp dụng middleware 'web' và 'auth' từ routes/web.php.
|
*/

Route::get('/dashboard', [DashboardClientController::class, 'index'])->name('frontend.dashboard');
Route::get('/dashboard/onboarding', [DashboardClientController::class, 'onboarding'])->name('frontend.dashboard.onboarding');
Route::post('/dashboard/onboarding', [DashboardClientController::class, 'saveOnboarding'])->name('frontend.dashboard.onboarding.save');
Route::get('/dashboard/khoa-hoc-cua-toi', [KhoaHocCuaToiController::class, 'index'])->name('frontend.dashboard.khoahoc');
Route::get('/dashboard/khoa-hoc-yeu-thich', [YeuThichKhoaHocController::class, 'index'])->name('frontend.dashboard.yeuthich');
Route::get('/dashboard/khoa-hoc-cua-toi/{courseSlug}/bai-hoc/{lessonSlug?}', [KhoaHocCuaToiController::class, 'show'])->name('frontend.dashboard.khoahoc.show');
Route::get('/dashboard/khoa-hoc-cua-toi/{courseSlug}/tiep-tuc', [KhoaHocCuaToiController::class, 'resume'])->name('frontend.dashboard.khoahoc.resume');
Route::get('/dashboard/khoa-hoc-cua-toi/{courseSlug}/bai-hoc/{lessonSlug}/kiem-tra', [KhoaHocCuaToiController::class, 'quiz'])->name('frontend.dashboard.khoahoc.quiz');
Route::get('/dashboard/khoa-hoc-cua-toi/{courseSlug}/bai-hoc/{lessonSlug}/phat-am', [KhoaHocCuaToiController::class, 'pronunciation'])->name('frontend.dashboard.khoahoc.pronunciation');
Route::post('/dashboard/khoa-hoc-cua-toi/bai-hoc/{id}/binh-luan', [KhoaHocCuaToiController::class, 'postComment'])->name('frontend.dashboard.khoahoc.comment');
Route::post('/dashboard/khoa-hoc-cua-toi/bai-hoc/{id}/tien-do', [KhoaHocCuaToiController::class, 'updateProgress'])->name('frontend.dashboard.khoahoc.progress');
Route::get('/dashboard/tu-vung', [TuVungDashboardController::class, 'index'])->name('frontend.dashboard.tuvung');
Route::get('/dashboard/luyen-thi-hsk', [LuyentapClientController::class, 'index'])->name('frontend.dashboard.luyentap');
Route::get('/dashboard/luyen-thi-hsk/{id}', [LuyentapClientController::class, 'instruction'])->name('frontend.dashboard.luyentap.show');
Route::get('/dashboard/luyen-thi-hsk/{id}/lam-bai', [LuyentapClientController::class, 'exam'])->name('frontend.dashboard.luyentap.exam');
Route::post('/dashboard/luyen-thi-hsk/{id}/submit', [LuyentapClientController::class, 'submit'])->name('frontend.dashboard.luyentap.submit');
Route::get('/dashboard/luyen-thi-hsk/ket-qua/{phien_id}', [LuyentapClientController::class, 'result'])->name('frontend.dashboard.luyentap.result');

// Lộ trình học AI
Route::get('/dashboard/lo-trinh-ai', [AiLoTrinhController::class, 'index'])->name('frontend.dashboard.lotrinh_ai');
Route::post('/dashboard/lo-trinh-ai/generate', [AiLoTrinhController::class, 'generate'])->name('frontend.dashboard.lotrinh_ai.generate');
