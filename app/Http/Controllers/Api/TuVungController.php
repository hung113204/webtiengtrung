<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TuVung;
use App\Models\TienDoTuVung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TuVungController extends Controller
{
    /**
     * Get vocabulary list with user progress
     */
    public function getList(Request $request)
    {
        $userId = Auth::id() ?? 1; // Fallback to 1 for testing if not logged in

        // If lesson ID is provided, filter by it, otherwise get all
        $query = TuVung::with('baiHoc');
        
        if ($request->has('id_bai_hoc')) {
            $query->where('id_bai_hoc', $request->id_bai_hoc);
        }

        $vocabularies = $query->get();

        // Get user progress
        $progress = TienDoTuVung::where('id_nguoi_dung', $userId)
            ->whereIn('id_tu_vung', $vocabularies->pluck('id'))
            ->get()
            ->keyBy('id_tu_vung');

        $result = $vocabularies->map(function ($vocab) use ($progress) {
            $userProgress = $progress->get($vocab->id);
            
            return [
                'id' => $vocab->id,
                'hanzi' => $vocab->tu_han,
                'pinyin' => $vocab->phien_am,
                'meaning' => $vocab->nghia_tieng_viet,
                'exZh' => $vocab->vi_du, // Should be split or parsed if needed, but we'll use as is
                'exVi' => '', // Assuming vi_du contains both or we can mock for now
                'level' => $vocab->baiHoc ? $vocab->baiHoc->ten_bai_hoc : 'General',
                'cat' => $vocab->id_bai_hoc,
                'learned' => $userProgress && $userProgress->trang_thai == 2,
                'bookmarked' => $userProgress ? $userProgress->da_luu : false,
                'note' => $userProgress ? $userProgress->ghi_chu : '',
                // SRS Data
                'interval' => $userProgress ? $userProgress->interval : 0,
                'ease_factor' => $userProgress ? $userProgress->ease_factor : 2.5,
                'next_review_at' => $userProgress ? $userProgress->next_review_at : null,
            ];
        });

        return response()->json($result);
    }

    /**
     * Save user's personal note for a vocabulary
     */
    public function updateNote(Request $request)
    {
        $request->validate([
            'id_tu_vung' => 'required|exists:tu_vung,id',
            'note' => 'nullable|string'
        ]);

        $userId = Auth::id() ?? 1;

        $progress = TienDoTuVung::updateOrCreate(
            ['id_nguoi_dung' => $userId, 'id_tu_vung' => $request->id_tu_vung],
            ['ghi_chu' => $request->note]
        );

        return response()->json(['success' => true, 'note' => $progress->ghi_chu]);
    }

    /**
     * Toggle bookmark state
     */
    public function toggleBookmark(Request $request)
    {
        $request->validate([
            'id_tu_vung' => 'required|exists:tu_vung,id',
        ]);

        $userId = Auth::id() ?? 1;

        $progress = TienDoTuVung::firstOrNew([
            'id_nguoi_dung' => $userId,
            'id_tu_vung' => $request->id_tu_vung
        ]);

        $progress->da_luu = !$progress->da_luu;
        $progress->save();

        return response()->json(['success' => true, 'bookmarked' => $progress->da_luu]);
    }

    /**
     * Sync SRS (Spaced Repetition System) progress after reviewing
     */
    public function syncSrs(Request $request)
    {
        $request->validate([
            'id_tu_vung' => 'required|exists:tu_vung,id',
            'quality' => 'required|integer|min:0|max:5', // 0: forgot, 3: hard, 4: good, 5: easy
        ]);

        $userId = Auth::id() ?? 1;
        $quality = $request->quality;

        $progress = TienDoTuVung::firstOrNew([
            'id_nguoi_dung' => $userId,
            'id_tu_vung' => $request->id_tu_vung
        ]);

        // Basic SM-2 Implementation
        $easeFactor = $progress->ease_factor ?? 2.5;
        $interval = $progress->interval ?? 0;
        $repetitions = ($progress->trang_thai == 2) ? 1 : 0; // simplistic rep tracking

        if ($quality < 3) {
            // Failed
            $repetitions = 0;
            $interval = 1;
        } else {
            // Passed
            $repetitions++;
            if ($repetitions == 1) {
                $interval = 1;
            } else if ($repetitions == 2) {
                $interval = 6;
            } else {
                $interval = round($interval * $easeFactor);
            }
        }

        // Calculate new ease factor
        $easeFactor = $easeFactor + (0.1 - (5 - $quality) * (0.08 + (5 - $quality) * 0.02));
        if ($easeFactor < 1.3) $easeFactor = 1.3;

        $progress->ease_factor = $easeFactor;
        $progress->interval = $interval;
        $progress->trang_thai = ($interval > 21) ? 2 : 1; // 2: learned, 1: learning
        $progress->next_review_at = now()->addDays($interval);
        
        $progress->save();

        if (Auth::check()) {
            $user = Auth::user();
            $user->capNhatStreak();
            
            // Nếu nhớ (quality > 0), thưởng 2 XP
            if ($quality > 0) {
                $user->tangXP(2);
            }
        }

        return response()->json([
            'success' => true, 
            'interval' => $interval,
            'next_review_at' => $progress->next_review_at
        ]);
    }
}
