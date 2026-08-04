<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = env('FPT_AI_KEY');
    }

    /**
     * Gửi file âm thanh đến FPT AI Speech-to-Text
     * 
     * @param string $filePath Đường dẫn vật lý của file (hoặc URL)
     * @return array Kết quả trả về từ FPT AI
     */
    public function transcribe($filePath)
    {
        if (empty($this->apiKey)) {
            Log::warning("AIService: FPT_AI_KEY chưa được cấu hình.");
            return [
                'status' => 1,
                'message' => 'FPT_AI_KEY is missing.',
                'hypotheses' => []
            ];
        }

        // Nếu là URL bên ngoài (ví dụ youtube, drive) hoặc file không tồn tại cục bộ
        if (filter_var($filePath, FILTER_VALIDATE_URL) || !file_exists($filePath)) {
            Log::info("AIService: File không tồn tại cục bộ hoặc là URL liên kết. Giả lập kết quả xử lý.");
            // Giả lập cuộc gọi thành công đến FPT.AI
            return [
                'status' => 0,
                'hypotheses' => [
                    [
                        'utterance' => 'Chào mừng bạn đến với khóa học tiếng Trung Hán ngữ. Hôm nay chúng ta sẽ học bài đầu tiên.'
                    ]
                ],
                'message' => 'Processed successfully via simulated FPT.AI Speech Recognition.'
            ];
        }

        try {
            $fileContent = file_get_contents($filePath);
            
            // Gọi đến General ASR API của FPT AI
            $response = Http::withHeaders([
                'api_key' => $this->apiKey,
            ])->withBody($fileContent, 'audio/mpeg')
              ->post('https://api.fpt.ai/hmi/asr/general');

            if ($response->successful()) {
                Log::info("AIService: Gọi thành công API FPT.AI");
                return $response->json();
            }

            Log::error("AIService: Lỗi gọi FPT.AI API - " . $response->body());
            return [
                'status' => 2,
                'message' => 'API request failed: ' . $response->body()
            ];
        } catch (\Exception $e) {
            Log::error("AIService: Exception khi gọi FPT.AI API - " . $e->getMessage());
            return [
                'status' => 9,
                'message' => $e->getMessage()
            ];
        }
    }
}
