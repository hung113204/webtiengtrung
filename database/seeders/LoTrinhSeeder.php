<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LoTrinhSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $loTrinh = \App\Models\LoTrinh::updateOrCreate(
            ['slug' => \Illuminate\Support\Str::slug('LỘ TRÌNH HỌC')],
            [
                'ten_lo_trinh' => 'LỘ TRÌNH HỌC',
                'mo_ta_ngan' => 'Từ 你好 đến thành thạo HSK 6',
                'mo_ta' => 'Lộ trình học tập toàn diện từ cơ bản đến nâng cao.',
                'trang_thai' => 1,
                'thu_tu' => 1,
            ]
        );

        // Delete existing stages to avoid duplicates if run multiple times
        $loTrinh->giaiDoans()->delete();

        $giaiDoans = [
            [
                'icon_text' => '你',
                'ten_giai_doan' => 'Nhập môn',
                'mo_ta' => 'Phát âm & Pinyin',
            ],
            [
                'icon_text' => '1-2',
                'ten_giai_doan' => 'HSK 1–2',
                'mo_ta' => '300 từ cơ bản',
            ],
            [
                'icon_text' => '3-4',
                'ten_giai_doan' => 'HSK 3–4',
                'mo_ta' => 'Giao tiếp trôi chảy',
            ],
            [
                'icon_text' => '5',
                'ten_giai_doan' => 'HSK 5',
                'mo_ta' => 'Đọc báo, xem phim',
            ],
            [
                'icon_text' => '6',
                'ten_giai_doan' => 'HSK 6',
                'mo_ta' => 'Học thuật, chuyên sâu',
            ],
            [
                'icon_text' => '通',
                'ten_giai_doan' => 'Thành thạo',
                'mo_ta' => 'Làm việc & du học',
            ]
        ];

        foreach ($giaiDoans as $index => $gd) {
            $loTrinh->giaiDoans()->create([
                'icon_text' => $gd['icon_text'],
                'ten_giai_doan' => $gd['ten_giai_doan'],
                'mo_ta' => $gd['mo_ta'],
                'thu_tu' => $index + 1,
            ]);
        }
    }
}
