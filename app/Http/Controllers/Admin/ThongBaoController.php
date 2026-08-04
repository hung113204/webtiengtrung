<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ThongBao;
use App\Models\NguoiDung;
use App\Models\ThongBaoNguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ThongBaoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ThongBao::with('nguoiGui')->withCount('nguoiDungs')->latest();

        // Search by Title or Content
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('tieu_de', 'like', "%{$search}%")
                  ->orWhere('noi_dung', 'like', "%{$search}%");
            });
        }

        $notifications = $query->paginate(15)->withQueryString();

        // Statistics
        $totalNotifications = ThongBao::count();
        $totalRecipients = ThongBaoNguoiDung::count();
        $totalRead = ThongBaoNguoiDung::where('da_doc', true)->count();
        $readRate = $totalRecipients > 0 ? round(($totalRead / $totalRecipients) * 100, 1) : 0;

        return view('admin.thongbao.index', compact(
            'notifications',
            'totalNotifications',
            'totalRecipients',
            'totalRead',
            'readRate'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = NguoiDung::select('id', 'ho_ten', 'email')->orderBy('ho_ten', 'asc')->get();
        return view('admin.thongbao.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tieu_de' => 'required|string|max:255',
            'noi_dung' => 'required|string',
            'gui_toi' => 'required|string|in:all,custom',
            'id_nguoi_dung' => 'required_if:gui_toi,custom|array',
            'id_nguoi_dung.*' => 'exists:nguoi_dung,id',
        ]);

        DB::transaction(function() use ($request) {
            $thongBao = ThongBao::create([
                'tieu_de' => $request->tieu_de,
                'noi_dung' => $request->noi_dung,
                'id_nguoi_gui' => auth()->id(),
            ]);

            if ($request->gui_toi === 'all') {
                $userIds = NguoiDung::pluck('id')->toArray();
                $thongBao->nguoiDungs()->attach($userIds);
            } else {
                $thongBao->nguoiDungs()->attach($request->id_nguoi_dung);
            }
        });

        return redirect()->route('admin.thongbao.index')->with('success', 'Gửi thông báo thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $notification = ThongBao::with(['nguoiGui', 'nguoiDungs' => function($q) {
            $q->orderBy('ho_ten', 'asc');
        }])->findOrFail($id);

        $recipientsCount = $notification->nguoiDungs->count();
        $readCount = $notification->nguoiDungs->where('pivot.da_doc', true)->count();
        $unreadCount = $recipientsCount - $readCount;

        return view('admin.thongbao.show', compact('notification', 'recipientsCount', 'readCount', 'unreadCount'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $notification = ThongBao::findOrFail($id);
        $notification->delete();

        return redirect()->route('admin.thongbao.index')->with('success', 'Xóa thông báo thành công!');
    }
}
