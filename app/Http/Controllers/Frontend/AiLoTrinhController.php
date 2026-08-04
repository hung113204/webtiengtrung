<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\HoSoHocVien;
use App\Models\KhoaHoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiLoTrinhController extends Controller
{
    /**
     * Hiển thị trang lộ trình AI
     */
    public function index()
    {
        $user = Auth::user();
        $hoSo = HoSoHocVien::where('id_nguoi_dung', $user->id)->first();

        // Nếu chưa có hồ sơ (chưa chọn mục tiêu), báo cho người dùng
        if (!$hoSo) {
            return redirect()->route('frontend.dashboard')->with('error', 'Bạn cần cập nhật mục tiêu học tập để AI tạo lộ trình.');
        }

        // Nếu đã có lộ trình, load danh sách khóa học tương ứng
        $detailedPath = [];
        if (!empty($hoSo->lo_trinh_ai)) {
            $khoaHocs = KhoaHoc::where('trang_thai', 1)->get();
            $detailedPath = collect($hoSo->lo_trinh_ai)->map(function ($item) use ($khoaHocs) {
                $course = $khoaHocs->firstWhere('id', $item['course_id']);
                return [
                    'course' => $course,
                    'reason' => $item['reason']
                ];
            })->filter(function ($item) {
                return !is_null($item['course']);
            })->values();
        }

        return view('frontend.dashboardclient.lotrinh_ai', compact('hoSo', 'detailedPath'));
    }

    /**
     * Tạo lộ trình bằng Google Gemini API
     */
    public function generate(Request $request)
    {
        $user = Auth::user();
        $hoSo = HoSoHocVien::where('id_nguoi_dung', $user->id)->first();

        if (!$hoSo) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy hồ sơ học viên.'], 404);
        }

        $apiKey = env('GEMINI_API_KEY');
        if (empty($apiKey)) {
            return response()->json(['status' => 'error', 'message' => 'Hệ thống chưa cấu hình API Key của AI (GEMINI_API_KEY).'], 500);
        }

        // Lấy danh sách khóa học đang hoạt động
        $khoaHocs = KhoaHoc::with(['capDoHSK', 'danhMucKhoaHoc'])
            ->where('trang_thai', 1)
            ->select('id', 'ten_khoa_hoc', 'slug', 'mo_ta_ngan', 'mo_ta', 'id_cap_do_hsk', 'id_danh_muc_khoa_hoc')
            ->get();

        if ($khoaHocs->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'Chưa có khóa học nào trong hệ thống để gợi ý.'], 400);
        }

        // Prepare a simplified array for the AI prompt
        $courseContext = $khoaHocs->map(function ($kh) {
            return [
                'id' => $kh->id,
                'ten_khoa_hoc' => $kh->ten_khoa_hoc,
                'mo_ta_ngan' => strip_tags($kh->mo_ta_ngan),
                'cap_do_hsk' => $kh->capDoHSK->ten_cap_do ?? 'Không xác định',
                'danh_muc' => $kh->danhMucKhoaHoc->ten_danh_muc ?? 'Chung'
            ];
        });

        // Xây dựng Prompt
        $trinhDo = $hoSo->trinh_do_hien_tai ?? 'Chưa rõ';
        $mucTieu = $hoSo->muc_tieu_hoc_tap ?? 'Giao tiếp cơ bản';

        $coursesJson = json_encode($courseContext, JSON_UNESCAPED_UNICODE);

        $prompt = "Bạn là một chuyên gia giáo dục tiếng Trung. Học viên của tôi có trình độ hiện tại là '{$trinhDo}' và mục tiêu học tập là '{$mucTieu}'. 
Dưới đây là danh sách các khóa học hiện có trong hệ thống của tôi:
{$coursesJson}

Dựa vào trình độ và mục tiêu của học viên, hãy đề xuất một lộ trình học tập gồm một số khóa học phù hợp NHẤT từ danh sách trên. Sắp xếp chúng theo thứ tự nên học từ trước đến sau.
Chỉ trả về kết quả dưới dạng một mảng JSON (không bọc trong markdown, chỉ xuất ra JSON thuần túy) với định dạng như sau:
[
  {
    \"course_id\": 1,
    \"reason\": \"Lý do ngắn gọn vì sao nên học khóa này\"
  }
]";

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key=' . $apiKey, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'responseMimeType' => 'application/json' // Ép Gemini trả về JSON
                ]
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                
                // Trích xuất JSON từ text
                $responseText = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? '[]';
                
                // Xử lý chuỗi JSON nếu Gemini vẫn bọc trong markdown
                $responseText = preg_replace('/```json\s*/', '', $responseText);
                $responseText = preg_replace('/```/', '', $responseText);

                // Parse the response
                $path = json_decode(trim($responseText), true);

                if (json_last_error() !== JSON_ERROR_NONE || empty($path)) {
                    Log::error('Gemini Invalid JSON: ' . $responseText);
                    return response()->json(['status' => 'error', 'message' => 'AI trả về dữ liệu không hợp lệ.'], 500);
                }

                // Lưu vào DB
                $hoSo->lo_trinh_ai = $path;
                $hoSo->save();

                // Lấy thêm thông tin chi tiết của các khóa học để hiển thị
                $detailedPath = collect($path)->map(function ($item) use ($khoaHocs) {
                    $course = $khoaHocs->firstWhere('id', $item['course_id']);
                    return [
                        'course' => $course,
                        'reason' => $item['reason']
                    ];
                })->filter(function ($item) {
                    return !is_null($item['course']);
                })->values();

                // Tạo HTML để render trực tiếp nếu cần thiết, hoặc trả JSON cho Vue/JS xử lý
                $view = view('frontend.dashboardclient.partials.lotrinh_ai_timeline', ['detailedPath' => $detailedPath])->render();

                return response()->json([
                    'status' => 'success', 
                    'message' => 'Tạo lộ trình thành công!',
                    'html' => $view
                ]);
            } else {
                Log::error('Gemini API Error: ' . $response->body());
                return response()->json(['status' => 'error', 'message' => 'Có lỗi xảy ra khi gọi AI API. Vui lòng thử lại.'], 500);
            }

        } catch (\Exception $e) {
            Log::error('Gemini AI Generation Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Không thể kết nối đến AI Server.'], 500);
        }
    }
}
