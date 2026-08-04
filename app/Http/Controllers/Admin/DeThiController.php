<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeThi;
use App\Models\BaiHoc;
use App\Models\CapDoHSK;
use App\Models\CauHoi;
use App\Models\MucDo;
use App\Http\Requests\Admin\DeThiRequest;
use Illuminate\Http\Request;

class DeThiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DeThi::with(['baiHoc.capDoHsk', 'cauHois', 'mucDo']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('ten_de_thi', 'LIKE', "%{$search}%");
        }

        if ($request->filled('cap_do_hsk')) {
            $query->whereHas('baiHoc', function ($q) use ($request) {
                $q->where('id_cap_do_hsk', $request->cap_do_hsk);
            });
        }

        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        $deThis = $query->latest()->paginate(10);
        $capDoHskList = CapDoHSK::orderBy('thu_tu')->get();
        $baiHocs = BaiHoc::orderBy('thu_tu')->get();
        $mucDos = MucDo::orderBy('thu_tu')->get();

        // Calculate statistics
        $totalExams = DeThi::count();
        $completedAttempts = \Illuminate\Support\Facades\Schema::hasTable('phien_luyen_thi') 
            ? \Illuminate\Support\Facades\DB::table('phien_luyen_thi')->where('trang_thai', 'Hoàn thành')->count() 
            : 0;
        $avgTime = round(DeThi::avg('thoi_gian_lam') ?? 0);

        return view('admin.dethi.index', compact('deThis', 'capDoHskList', 'baiHocs', 'mucDos', 'totalExams', 'completedAttempts', 'avgTime'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DeThiRequest $request)
    {
        $data = $request->validated();
        $data['so_cau'] = 0; // Mặc định ban đầu 0 câu hỏi

        DeThi::create($data);

        return redirect()->route('admin.dethi.index')->with('success', 'Tạo đề thi mới thành công!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DeThiRequest $request, $id)
    {
        $dethi = DeThi::findOrFail($id);
        $data = $request->validated();

        $dethi->update($data);

        return redirect()->route('admin.dethi.index')->with('success', 'Cập nhật thông tin đề thi thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $dethi = DeThi::findOrFail($id);
        $dethi->delete();

        return redirect()->route('admin.dethi.index')->with('success', 'Xóa đề thi thành công!');
    }

    /**
     * Get list of questions for attaching (AJAX).
     */
    public function getQuestions($id)
    {
        $dethi = DeThi::findOrFail($id);
        
        $attachedQuestions = $dethi->cauHois()
            ->with(['loaiCauHoi', 'mucDo'])
            ->orderBy('chi_tiet_de_thi.thu_tu')
            ->get();

        $allQuestions = CauHoi::with(['loaiCauHoi', 'mucDo'])->get();

        $attached = $attachedQuestions->map(function ($q) {
            return [
                'id' => $q->id,
                'noi_dung' => $q->noi_dung,
                'pinyin' => $q->pinyin,
                'dich_nghia' => $q->dich_nghia,
                'loai' => $q->loaiCauHoi->ten_loai ?? 'N/A',
                'muc_do' => $q->mucDo->ten_muc_do ?? 'N/A',
                'part' => $this->getQuestionPart($q),
                'thu_tu' => $q->pivot->thu_tu,
            ];
        });

        $available = $allQuestions->whereNotIn('id', $attachedQuestions->pluck('id'))->map(function ($q) {
            return [
                'id' => $q->id,
                'noi_dung' => $q->noi_dung,
                'pinyin' => $q->pinyin,
                'dich_nghia' => $q->dich_nghia,
                'loai' => $q->loaiCauHoi->ten_loai ?? 'N/A',
                'muc_do' => $q->mucDo->ten_muc_do ?? 'N/A',
                'part' => $this->getQuestionPart($q),
            ];
        })->values();

        return response()->json([
            'status' => 'success',
            'dethi' => [
                'id' => $dethi->id,
                'ten_de_thi' => $dethi->ten_de_thi,
                'so_cau' => $dethi->so_cau,
                'thoi_gian_lam' => $dethi->thoi_gian_lam,
            ],
            'attached' => $attached,
            'available' => $available
        ]);
    }

    /**
     * Attach a question to the exam (AJAX).
     */
    public function attachQuestion(Request $request, $id)
    {
        $dethi = DeThi::findOrFail($id);
        $questionId = $request->input('id_cau_hoi');

        if (!$dethi->cauHois()->where('id_cau_hoi', $questionId)->exists()) {
            $maxThuTu = $dethi->chiTietDeThis()->max('thu_tu') ?? 0;
            $dethi->cauHois()->attach($questionId, ['thu_tu' => $maxThuTu + 1]);
            
            // Cập nhật số câu hỏi trong đề
            $dethi->update(['so_cau' => $dethi->cauHois()->count()]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Đã thêm câu hỏi vào đề thi.',
            'so_cau' => $dethi->so_cau
        ]);
    }

    /**
     * Detach a question from the exam (AJAX).
     */
    public function detachQuestion(Request $request, $id)
    {
        $dethi = DeThi::findOrFail($id);
        $questionId = $request->input('id_cau_hoi');

        $dethi->cauHois()->detach($questionId);

        // Đánh lại số thứ tự
        $details = $dethi->chiTietDeThis()->orderBy('thu_tu')->get();
        foreach ($details as $index => $detail) {
            $detail->update(['thu_tu' => $index + 1]);
        }

        // Cập nhật số câu hỏi trong đề
        $dethi->update(['so_cau' => $dethi->cauHois()->count()]);

        return response()->json([
            'status' => 'success',
            'message' => 'Đã gỡ câu hỏi khỏi đề thi.',
            'so_cau' => $dethi->so_cau
        ]);
    }

    /**
     * Reorder questions (AJAX).
     */
    public function reorderQuestions(Request $request, $id)
    {
        $dethi = DeThi::findOrFail($id);
        $questionIds = $request->input('question_ids', []);

        foreach ($questionIds as $index => $qId) {
            $dethi->chiTietDeThis()->where('id_cau_hoi', $qId)->update([
                'thu_tu' => $index + 1
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Đã cập nhật thứ tự câu hỏi.'
        ]);
    }

    /**
     * Determine if a question is listening, writing, or reading.
     */
    private function getQuestionPart($question)
    {
        return $question->getPart();
    }
}
