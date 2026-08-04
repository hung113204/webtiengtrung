<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\DanhMucKhoaHoc;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Chia sẻ danh sách danh mục khóa học đang hoạt động
        // cho tất cả view (dùng trong navbar/sidebar)
        View::composer('*', function ($view) {
            try {
                $navDanhMuc = DanhMucKhoaHoc::roots()
                    ->active()
                    ->with(['children' => fn($q) => $q->active()->orderBy('thu_tu')])
                    ->orderBy('thu_tu')
                    ->get(['id', 'ten_danh_muc', 'slug']);
            } catch (\Exception $e) {
                $navDanhMuc = collect();
            }
            $view->with('navDanhMuc', $navDanhMuc);
        });
    }
}
