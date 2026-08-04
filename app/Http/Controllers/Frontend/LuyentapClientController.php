<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LuyentapClientController extends Controller
{
    /**
     * Hiển thị trang Phòng Luyện Thi HSK (Dashboard Học Viên)
     */
    public function index(Request $request)
    {
        $query = \App\Models\DeThi::with('mucDo')->where('trang_thai', 1);

        if ($request->has('level') && $request->level != '') {
            $query->where('id_muc_do', $request->level);
        }

        $deThis = $query->get();
            
        // Lấy danh sách Mức độ để làm bộ lọc
        $mucDos = \App\Models\MucDo::orderBy('thu_tu')->get();
        
        return view('frontend.luyentapclient.index', compact('deThis', 'mucDos'));
    }

    /**
     * Hiển thị trang hướng dẫn trước khi làm bài
     */
    public function instruction($id)
    {
        $deThi = \App\Models\DeThi::with('mucDo')->findOrFail($id);
        return view('frontend.luyentapclient.instruction', compact('deThi'));
    }

    /**
     * Hiển thị giao diện làm bài thi HSK (Full màn hình)
     */
    public function exam($id)
    {
        $deThi = \App\Models\DeThi::with(['cauHois' => function($q) {
            $q->orderBy('chi_tiet_de_thi.thu_tu');
        }, 'cauHois.dapAns', 'mucDo'])->findOrFail($id);

        return view('frontend.luyentapclient.show', compact('deThi'));
    }

    /**
     * Nộp bài thi
     */
    public function submit(Request $request, $id)
    {
        $deThi = \App\Models\DeThi::with('cauHois.dapAns')->findOrFail($id);
        $answers = $request->input('answers', []);
        
        $soCauDung = 0;
        $soCauSai = 0;
        $chiTiet = [];

        foreach ($deThi->cauHois as $cauHoi) {
            $isCorrect = false;
            $userAns = $answers[$cauHoi->id] ?? null;
            // Giả định bài HSK có 1 đáp án đúng nhất (dapAns->first) hoặc check type
            $correctDapAn = $cauHoi->dapAns->first();
            
            if (!$userAns) {
                $soCauSai++;
                $chiTiet[] = [
                    'id_cau_hoi' => $cauHoi->id,
                    'id_dap_an' => null,
                    'dap_an_tu_luan' => null,
                    'dung' => false
                ];
                continue;
            }

            $part = $cauHoi->getPart();
            if ($part == 'writing') {
                // Tự luận / Sắp xếp
                $expected = preg_replace('/\s+/', '', str_replace('/', '', $correctDapAn->noi_dung ?? ''));
                $actual = preg_replace('/\s+/', '', str_replace('/', '', $userAns));
                $isCorrect = ($expected === $actual && $actual !== '');
                
                $chiTiet[] = [
                    'id_cau_hoi' => $cauHoi->id,
                    'id_dap_an' => null,
                    'dap_an_tu_luan' => $userAns,
                    'dung' => $isCorrect
                ];
            } else {
                // Trắc nghiệm (hoặc điền từ phần đọc)
                // Phải so sánh ID với đáp án đúng hoặc so sánh chuỗi
                $isCorrect = false;
                if (is_numeric($userAns)) {
                    $isCorrect = ($correctDapAn && $correctDapAn->id == $userAns);
                } else {
                    $expected = preg_replace('/\s+/', '', $correctDapAn->noi_dung ?? '');
                    $actual = preg_replace('/\s+/', '', $userAns);
                    $isCorrect = ($expected === $actual && $actual !== '');
                }

                $chiTiet[] = [
                    'id_cau_hoi' => $cauHoi->id,
                    'id_dap_an' => is_numeric($userAns) ? (int)$userAns : null,
                    'dap_an_tu_luan' => is_numeric($userAns) ? null : $userAns,
                    'dung' => $isCorrect
                ];
            }

            if ($isCorrect) {
                $soCauDung++;
            } else {
                $soCauSai++;
            }
        }
        
        $tongDiem = 0;
        $totalQs = count($deThi->cauHois);
        if ($totalQs > 0) {
            $tongDiem = ($soCauDung / $totalQs) * 100; // Tính theo thang điểm 100 tạm thời
        }

        $timeSpent = (int) $request->input('time_spent', 0);
        $ketThuc = now();
        $batDau = $ketThuc->copy()->subSeconds($timeSpent);

        $phien = \App\Models\PhienLuyenThi::create([
            'id_de_thi' => $id,
            'id_nguoi_dung' => auth()->id() ?? 1, // Fallback if no auth
            'thoi_gian_bat_dau' => $batDau,
            'thoi_gian_ket_thuc' => $ketThuc,
            'tong_diem' => $tongDiem,
            'so_cau_dung' => $soCauDung,
            'so_cau_sai' => $soCauSai,
            'trang_thai' => 1
        ]);

        foreach ($chiTiet as $ct) {
            $ct['id_phien_luyen_thi'] = $phien->id;
            \App\Models\ChiTietLuyenThi::create($ct);
        }

        return response()->json([
            'success' => true,
            'redirect' => route('frontend.dashboard.luyentap.result', $phien->id)
        ]);
    }

    /**
     * Xem kết quả bài thi
     */
    public function result(Request $request, $phien_id)
    {
        $phien = \App\Models\PhienLuyenThi::with(['deThi', 'chiTietLuyenThis'])->findOrFail($phien_id);
        
        $deThi = \App\Models\DeThi::with(['cauHois' => function($q) {
            $q->orderBy('chi_tiet_de_thi.thu_tu');
        }, 'cauHois.dapAns', 'mucDo'])->findOrFail($phien->id_de_thi);

        // Map answers
        $userAnswers = [];
        foreach ($phien->chiTietLuyenThis as $ct) {
            $userAnswers[$ct->id_cau_hoi] = $ct;
        }

        // --- Tính toán thống kê ---
        $listeningQs = $deThi->cauHois->filter(fn($q) => $q->getPart() === 'listening')->values();
        $readingQs   = $deThi->cauHois->filter(fn($q) => $q->getPart() === 'reading')->values();
        $writingQs   = $deThi->cauHois->filter(fn($q) => $q->getPart() === 'writing')->values();
        $orderedCauHois = collect()->merge($listeningQs)->merge($readingQs)->merge($writingQs);

        // Attach global index
        $orderedCauHois->each(function($q, $idx) {
            $q->global_index = $idx + 1;
        });

        $partStats = [
            'listening' => ['label' => 'Nghe', 'total' => $listeningQs->count(), 'correct' => 0],
            'reading'   => ['label' => 'Đọc',  'total' => $readingQs->count(),   'correct' => 0],
            'writing'   => ['label' => 'Viết', 'total' => $writingQs->count(),   'correct' => 0],
        ];
        
        $soCauChuaLam = 0;
        foreach ($orderedCauHois as $q) {
            $ua = $userAnswers[$q->id] ?? null;
            if ($ua && $ua->dung) {
                $partStats[$q->getPart()]['correct']++;
            }
            if (!$ua || (is_null($ua->id_dap_an) && is_null($ua->dap_an_tu_luan))) {
                $soCauChuaLam++;
            }
        }

        // --- Lọc (Filter) ---
        $filter = $request->get('filter', 'all');
        $filteredCauHois = $orderedCauHois;
        
        if ($filter === 'wrong') {
            $filteredCauHois = $orderedCauHois->filter(function($q) use ($userAnswers) {
                $ua = $userAnswers[$q->id] ?? null;
                return $ua ? !$ua->dung : true;
            });
        } elseif (in_array($filter, ['listening', 'reading', 'writing'])) {
            $filteredCauHois = $orderedCauHois->filter(fn($q) => $q->getPart() === $filter);
        }

        // --- Phân trang (Pagination) ---
        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $perPage = 20; // 20 câu hỏi mỗi trang
        $paginatedItems = $filteredCauHois->slice(($page - 1) * $perPage, $perPage)->values();

        $paginatedCauHois = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedItems, 
            $filteredCauHois->count(), 
            $perPage, 
            $page, 
            [
                'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
                'query' => $request->query()
            ]
        );

        return view('frontend.luyentapclient.result', compact(
            'phien', 'deThi', 'userAnswers', 'partStats', 'soCauChuaLam', 'paginatedCauHois'
        ));
    }
}
