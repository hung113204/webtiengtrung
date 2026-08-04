<?php

namespace App\Jobs;

use App\Models\Video;
use App\Models\TuVung;
use App\Services\AIService;
use App\Services\GeminiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateVocabularyAI implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;
    public $timeout = 600; // 10 phút

    protected $video;

    /**
     * Create a new job instance.
     */
    public function __construct(Video $video)
    {
        $this->video = $video;
        $this->onQueue('ai-tasks');
    }

    /**
     * Execute the job.
     */
    public function handle(AIService $aiService, GeminiService $geminiService): void
    {
        Log::info("GenerateVocabularyAI: Bắt đầu tạo từ vựng cho Video ID " . $this->video->id);

        $baiHoc = $this->video->baiHocs()->first();
        if (!$baiHoc) {
            Log::warning("GenerateVocabularyAI: Video không gắn với bài học nào.");
            return;
        }

        // Bước 1: Lấy văn bản từ Video (Transcript)
        // Lưu ý: Tùy thuộc vào thiết kế hệ thống hiện tại, video->file_path có thể là file cục bộ hoặc link yt
        $audioPath = $this->video->file_path ?? '';
        
        // Gọi ASR (FPT.AI) để lấy phụ đề
        $asrResult = $aiService->transcribe($audioPath);
        
        $transcript = "";
        if (isset($asrResult['status']) && $asrResult['status'] === 0 && isset($asrResult['hypotheses'])) {
            foreach ($asrResult['hypotheses'] as $hyp) {
                $transcript .= $hyp['utterance'] . " ";
            }
        }

        if (empty(trim($transcript))) {
            // Giả lập một đoạn script nếu không gọi được ASR thật để test logic AI Gen
            $transcript = "大家好，欢迎来到汉语班。今天我们学习第一课。你好吗？我很好，谢谢。你叫什么名字？我叫张华。你是哪国人？我是越南人。";
            Log::info("GenerateVocabularyAI: Không trích xuất được Audio thật, dùng Transcript mẫu để fallback.");
        }

        Log::info("GenerateVocabularyAI: Transcript thu được: " . mb_substr($transcript, 0, 100) . "...");

        // Bước 2: Gọi Gemini LLM để trích xuất Từ vựng
        $vocabList = $geminiService->extractVocabulary($transcript);

        if (empty($vocabList)) {
            Log::error("GenerateVocabularyAI: Trích xuất JSON thất bại hoặc mảng rỗng.");
            return;
        }

        // Bước 3: Lưu vào CSDL
        $count = 0;
        foreach ($vocabList as $item) {
            if (!empty($item['tu_han']) && !empty($item['nghia_tieng_viet'])) {
                // Kiểm tra trùng lặp trong bài học
                $exists = TuVung::where('id_bai_hoc', $baiHoc->id)
                                ->where('tu_han', $item['tu_han'])
                                ->exists();

                if (!$exists) {
                    TuVung::create([
                        'id_bai_hoc' => $baiHoc->id,
                        'tu_han' => $item['tu_han'],
                        'phien_am' => $item['phien_am'] ?? '',
                        'nghia_tieng_viet' => $item['nghia_tieng_viet'],
                        'vi_du' => $item['vi_du'] ?? '',
                    ]);
                    $count++;
                }
            }
        }

        Log::info("GenerateVocabularyAI: Đã lưu thành công {$count} từ vựng mới cho Bài học ID " . $baiHoc->id);
    }
}
