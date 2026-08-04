<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HoaDon;
use App\Models\DangKyKhoaHoc;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class HoaDonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = HoaDon::with(['nguoiDung', 'dangKyKhoaHoc.khoaHoc'])->latest();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ma_hoa_don', 'like', "%{$search}%")
                  ->orWhere('ma_giao_dich', 'like', "%{$search}%")
                  ->orWhereHas('nguoiDung', function($userQuery) use ($search) {
                      $userQuery->where('ho_ten', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by Status
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        // Filter by Payment Method
        if ($request->filled('phuong_thuc_thanh_toan')) {
            $query->where('phuong_thuc_thanh_toan', $request->phuong_thuc_thanh_toan);
        }

        // Paginate
        $invoices = $query->paginate(15)->withQueryString();

        // Statistics
        $totalRevenue = HoaDon::where('trang_thai', 'Đã thanh toán')->sum('so_tien');
        $paidCount = HoaDon::where('trang_thai', 'Đã thanh toán')->count();
        $pendingCount = HoaDon::where('trang_thai', 'Chưa thanh toán')->count();
        $canceledCount = HoaDon::where('trang_thai', 'Đã hủy')->count();

        return view('admin.hoadon.index', compact(
            'invoices', 
            'totalRevenue', 
            'paidCount', 
            'pendingCount', 
            'canceledCount'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'trang_thai' => 'required|in:Chưa thanh toán,Đã thanh toán,Đã hủy',
            'phuong_thuc_thanh_toan' => 'required|string|max:100',
            'ma_giao_dich' => 'nullable|string|max:100',
            'so_tien' => 'required|numeric|min:0',
            'ngay_thanh_toan' => 'nullable|date',
        ]);

        $invoice = HoaDon::findOrFail($id);

        $updateData = [
            'trang_thai' => $request->trang_thai,
            'phuong_thuc_thanh_toan' => $request->phuong_thuc_thanh_toan,
            'ma_giao_dich' => $request->ma_giao_dich,
            'so_tien' => $request->so_tien,
        ];

        // Handle payment date logic
        if ($request->trang_thai === 'Đã thanh toán') {
            $updateData['ngay_thanh_toan'] = $request->ngay_thanh_toan ? Carbon::parse($request->ngay_thanh_toan) : now();
        } else {
            $updateData['ngay_thanh_toan'] = null;
        }

        $invoice->update($updateData);

        // Synchronize back to the Course Registration table if exists
        if ($invoice->id_dang_ky) {
            $registration = DangKyKhoaHoc::find($invoice->id_dang_ky);
            if ($registration) {
                $regStatus = 'Chờ duyệt';
                if ($request->trang_thai === 'Đã thanh toán') {
                    $regStatus = 'Đã duyệt';
                } elseif ($request->trang_thai === 'Đã hủy') {
                    $regStatus = 'Đã hủy';
                }

                $registration->update([
                    'trang_thai' => $regStatus,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Cập nhật hóa đơn và đồng bộ trạng thái đăng ký thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $invoice = HoaDon::findOrFail($id);
        $invoice->delete();

        return redirect()->back()->with('success', 'Xóa hóa đơn thành công!');
    }
}
