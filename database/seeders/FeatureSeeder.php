<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = [
            [
                'title' => 'Spaced Repetition',
                'description' => 'Flashcard từ vựng lặp lại đúng thời điểm dễ ghi nhớ lâu dài, không học vẹt.',
                'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"/></svg>',
                'order' => 1,
            ],
            [
                'title' => 'Luyện viết chữ Hán',
                'description' => 'Xem hoạt hình thứ tự nét, luyện viết trên canvas và được chấm đúng/sai từng nét.',
                'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19l7-7 3 3-7 7-3-3z"/><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"/><path d="M2 2l7.586 7.586"/><circle cx="11" cy="11" r="2"/></svg>',
                'order' => 2,
            ],
            [
                'title' => 'AI chấm phát âm',
                'description' => 'Ghi âm giọng nói, nhận điểm phát âm và gợi ý sửa lỗi thanh điệu ngay lập tức.',
                'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"/><path d="M19 10v2a7 7 0 0 1-14 0v-2"/><line x1="12" y1="19" x2="12" y2="22"/></svg>',
                'order' => 3,
            ],
            [
                'title' => 'Luyện thi HSK',
                'description' => 'Đề thi thử bám sát cấu trúc thật, có đồng hồ đếm giờ và thống kê điểm mạnh/yếu.',
                'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>',
                'order' => 4,
            ],
        ];

        foreach ($features as $feature) {
            \App\Models\Feature::create($feature);
        }
    }
}
