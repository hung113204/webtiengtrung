<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected $apiKey;
    protected $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models';

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
    }

    /**
     * Trích xuất từ vựng tiếng Trung từ văn bản (transcript)
     *
     * @param string $text
     * @return array Trả về mảng các object từ vựng
     */
    public function extractVocabulary($text)
    {
        if (empty($this->apiKey)) {
            Log::error("GeminiService: Chưa cấu hình GEMINI_API_KEY.");
            return [];
            // Log::warning("GeminiService: Chưa cấu hình GEMINI_API_KEY. Trả về dữ liệu mẫu (Mock Data).");
            // return [
            //     ["tu_han" => "你好", "phien_am" => "nǐ hǎo", "nghia_tieng_viet" => "Xin chào", "vi_du" => "你好吗？(Bạn khỏe không?)"],
            //     ["tu_han" => "谢谢", "phien_am" => "xièxie", "nghia_tieng_viet" => "Cảm ơn", "vi_du" => "谢谢你！(Cảm ơn bạn!)"],
            //     ["tu_han" => "再见", "phien_am" => "zàijiàn", "nghia_tieng_viet" => "Tạm biệt", "vi_du" => "明天再见！(Ngày mai gặp lại!)"],
            //     ["tu_han" => "对不起", "phien_am" => "duìbuqǐ", "nghia_tieng_viet" => "Xin lỗi", "vi_du" => "对不起，我迟到了。(Xin lỗi, tôi đến muộn.)"],
            //     ["tu_han" => "没关系", "phien_am" => "méi guānxi", "nghia_tieng_viet" => "Không có gì", "vi_du" => "没关系。(Không sao đâu.)"]
            // ];
        }

        $prompt = "Đây là đoạn kịch bản / phụ đề của một bài giảng tiếng Trung: \n" .
            $text . "\n\n" .
            "Yêu cầu:\n" .
            "1. Tìm và trích xuất tối đa 15 từ vựng quan trọng nhất dành cho người học tiếng Trung từ đoạn văn bản trên.\n" .
            "2. Trả về đúng ĐỊNH DẠNG JSON MẢNG (Array of JSON objects) thuần túy, tuyệt đối KHÔNG có markdown, không có thẻ code block như ```json.\n" .
            "3. Mỗi object bao gồm các key: 'tu_han' (chữ Hán), 'phien_am' (Pinyin), 'nghia_tieng_viet' (Nghĩa tiếng Việt), 'vi_du' (một câu ví dụ ngắn gọn kèm nghĩa tiếng việt trong cùng 1 chuỗi hoặc chỉ ví dụ tiếng Trung).\n" .
            "Ví dụ định dạng đầu ra mong muốn:\n" .
            "[\n  {\"tu_han\": \"你好\", \"phien_am\": \"nǐ hǎo\", \"nghia_tieng_viet\": \"Xin chào\", \"vi_du\": \"你好吗？(Bạn khỏe không?)\"}\n]";

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/gemini-1.5-flash:generateContent?key={$this->apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.2, // Low temperature for consistent JSON output
                    'responseMimeType' => 'application/json', // Force JSON output if supported
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                    $jsonText = $data['candidates'][0]['content']['parts'][0]['text'];
                    
                    // Clean up potential markdown formatting (just in case)
                    $jsonText = preg_replace('/```json/i', '', $jsonText);
                    $jsonText = str_replace('```', '', $jsonText);
                    $jsonText = trim($jsonText);

                    $vocabularies = json_decode($jsonText, true);

                    if (json_last_error() === JSON_ERROR_NONE && is_array($vocabularies)) {
                        return $vocabularies;
                    } else {
                        Log::error("GeminiService: Lỗi parse JSON: " . json_last_error_msg());
                        Log::error("GeminiService Raw Text: " . $jsonText);
                    }
                }
            } else {
                Log::error("GeminiService: Gọi API thất bại: " . $response->body());
            }
        } catch (\Exception $e) {
            Log::error("GeminiService: Exception - " . $e->getMessage());
        }

        return [];
    }
}
