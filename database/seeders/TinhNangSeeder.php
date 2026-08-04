<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TinhNangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = [
            [
                'tieu_de' => 'Thuật toán Spaced Repetition (Lặp lại ngắt quãng)',
                'badge_text' => 'Học từ vựng siêu tốc',
                'mo_ta' => 'Đừng học vẹt nữa! Hệ thống Flashcard của chúng tôi sử dụng thuật toán thông minh để tự động nhắc lại từ vựng ngay trước khi bạn sắp quên chúng. Phương pháp này đã được khoa học chứng minh giúp ghi nhớ lâu dài và tiết kiệm đến 50% thời gian học.',
                'danh_sach_bullet' => ['Đồng bộ hóa từ vựng với bài giảng', 'Phân tích chỉ số trí nhớ cá nhân', 'Học mọi lúc mọi nơi trên điện thoại'],
                'image_url' => 'https://images.unsplash.com/photo-1516321497487-e288fb19713f?q=80&w=800&auto=format&fit=crop',
                'vi_tri_anh' => 'right',
                'stat_number' => '+200%',
                'stat_label' => 'Tốc độ ghi nhớ từ',
                'stat_icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>',
                'button_text' => 'Thử Flashcard ngay',
                'button_link' => '#',
                'thu_tu' => 1,
            ],
            [
                'tieu_de' => 'Gia sư AI Chấm điểm Phát âm',
                'badge_text' => 'Giao tiếp lưu loát',
                'mo_ta' => 'Sợ nói sai thanh điệu? Công nghệ AI của chúng tôi sẽ phân tích giọng nói của bạn theo thời gian thực. Hệ thống chỉ ra chính xác từ nào bạn phát âm chưa chuẩn, thanh điệu nào bị lệch và cung cấp hướng dẫn bằng hình ảnh khẩu hình để bạn tự điều chỉnh.',
                'danh_sach_bullet' => ['Chấm điểm trên thang 100 từng câu', 'Phát hiện lỗi sai thanh điệu chi tiết', 'Hội thoại nhập vai (Role-play) với AI'],
                'image_url' => 'https://images.unsplash.com/photo-1590602847861-f357a9332bbc?q=80&w=800&auto=format&fit=crop',
                'vi_tri_anh' => 'left',
                'stat_number' => '98.5%',
                'stat_label' => 'Độ chính xác AI',
                'stat_icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"></path><path d="M19 10v2a7 7 0 0 1-14 0v-2"></path><line x1="12" y1="19" x2="12" y2="23"></line><line x1="8" y1="23" x2="16" y2="23"></line></svg>',
                'button_text' => null,
                'button_link' => null,
                'thu_tu' => 2,
            ],
            [
                'tieu_de' => 'Canvas Luyện Viết Chữ Hán',
                'badge_text' => 'Luyện chữ đẹp',
                'mo_ta' => 'Vượt qua nỗi sợ chữ Hán với khung canvas tương tác. Bạn có thể xem hình ảnh minh họa thứ tự từng nét bút, sau đó tự tay dùng chuột hoặc ngón tay (trên điện thoại) để vẽ lại. Thuật toán sẽ ngay lập tức đối chiếu và chấm điểm nét viết của bạn.',
                'danh_sach_bullet' => ['Hoạt hình thứ tự nét (Stroke order animation)', 'Lưới ô vuông (Tianzi ge) tiêu chuẩn', 'Lưu lại lịch sử luyện viết để theo dõi tiến bộ'],
                'image_url' => 'https://images.unsplash.com/photo-1550592704-6c76defa99ce?q=80&w=800&auto=format&fit=crop',
                'vi_tri_anh' => 'right',
                'stat_number' => null,
                'stat_label' => null,
                'stat_icon' => null,
                'button_text' => 'Viết thử chữ Hán',
                'button_link' => '#',
                'thu_tu' => 3,
            ],
            [
                'tieu_de' => 'Hệ thống Luyện thi HSK Trực tuyến',
                'badge_text' => 'Chinh phục chứng chỉ',
                'mo_ta' => 'Trải nghiệm cảm giác thi thật với hệ thống mô phỏng bài thi HSK 100%. Từ đồng hồ đếm ngược, phần nghe tự động chuyển tiếp đến báo cáo kết quả chi tiết từng phần (Nghe, Đọc, Viết) ngay sau khi nộp bài.',
                'danh_sach_bullet' => ['Ngân hàng đề thi được cập nhật liên tục', 'Giải thích đáp án chi tiết cho mỗi câu hỏi', 'Gợi ý ôn tập theo điểm yếu của bạn'],
                'image_url' => 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?q=80&w=800&auto=format&fit=crop',
                'vi_tri_anh' => 'left',
                'stat_number' => '+500',
                'stat_label' => 'Đề thi thử thực tế',
                'stat_icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>',
                'button_text' => null,
                'button_link' => null,
                'thu_tu' => 4,
            ]
        ];

        foreach ($features as $f) {
            \App\Models\TinhNang::create($f);
        }
    }
}
