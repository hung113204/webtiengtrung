<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\LoTrinh;
use Illuminate\Support\Facades\Cache;

class LoTrinhClientController extends Controller
{
    public function index()
    {
        // Lấy tất cả lộ trình đang hoạt động, eager-load giai đoạn và khóa học đề xuất
        $loTrinhs = Cache::remember('frontend_lotrinh_list', 60 * 10, function () {
            return LoTrinh::with([
                'giaiDoans.khoaHocs' => function ($q) {
                    $q->where('trang_thai', 1);
                },
            ])
                ->where('trang_thai', 1)
                ->orderBy('thu_tu', 'asc')
                ->get();
        });

        // Lộ trình chính (first) để hiển thị hero title/desc
        $loTrinh = $loTrinhs->first();

        return view('frontend.lotrinhclient.index', compact('loTrinhs', 'loTrinh'));
    }
}
