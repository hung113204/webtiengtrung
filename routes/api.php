<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Admin API Routes
use App\Http\Controllers\Api\Admin\DashboardController;

Route::prefix('admin')->group(function () {
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats']);
    Route::get('/dashboard/charts', [DashboardController::class, 'getChartData']);
});

// Vocabulary API Routes
use App\Http\Controllers\Api\TuVungController;

Route::prefix('tu-vung')->group(function () {
    Route::get('/', [TuVungController::class, 'getList']);
    Route::post('/ghi-chu', [TuVungController::class, 'updateNote']);
    Route::post('/bookmark', [TuVungController::class, 'toggleBookmark']);
    Route::post('/srs-sync', [TuVungController::class, 'syncSrs']);
});
