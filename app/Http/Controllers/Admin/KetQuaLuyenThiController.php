<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PhienLuyenThi;
use Illuminate\Http\Request;

class KetQuaLuyenThiController extends Controller
{
    /**
     * Display a listing of the exam attempts.
     */
    public function index(Request $request)
    {
        $query = PhienLuyenThi::with(['deThi', 'nguoiDung']);

        // Search by student name/email or exam name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('nguoiDung', function ($uq) use ($search) {
                    $uq->where('ho_ten', 'LIKE', "%{$search}%")
                       ->orWhere('email', 'LIKE', "%{$search}%");
                })->orWhereHas('deThi', function ($dq) use ($search) {
                    $dq->where('ten_de_thi', 'LIKE', "%{$search}%");
                });
            });
        }

        // Filter by state
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        $attempts = $query->latest()->paginate(15);

        // Statistics helper
        $stats = [
            'total' => PhienLuyenThi::count(),
            'completed' => PhienLuyenThi::where('trang_thai', 'Hoàn thành')->count(),
            'ongoing' => PhienLuyenThi::where('trang_thai', 'Đang làm')->count(),
        ];

        return view('admin.ketqua.index', compact('attempts', 'stats'));
    }

    /**
     * Display details of a specific exam attempt.
     */
    public function show($id)
    {
        $attempt = PhienLuyenThi::with([
            'deThi.baiHoc.capDoHsk',
            'nguoiDung',
            'chiTietLuyenThis.cauHoi.dapAns',
            'chiTietLuyenThis.dapAn',
            'chiTietLuyenThis.cauHoi.loaiCauHoi'
        ])->findOrFail($id);

        $duration = null;
        if ($attempt->thoi_gian_bat_dau && $attempt->thoi_gian_ket_thuc) {
            $duration = $attempt->thoi_gian_bat_dau->diffInMinutes($attempt->thoi_gian_ket_thuc);
        }

        return view('admin.ketqua.show', compact('attempt', 'duration'));
    }

    /**
     * Remove the specified exam attempt from storage.
     */
    public function destroy($id)
    {
        $attempt = PhienLuyenThi::findOrFail($id);
        $attempt->delete();

        return redirect()->route('admin.ketqua.index')->with('success', 'Xóa lịch sử thi thành công!');
    }
}
