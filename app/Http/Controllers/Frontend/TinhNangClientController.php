<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TinhNangClientController extends Controller
{
    /**
     * Hiển thị trang Tính năng nổi bật
     */
    public function index()
    {
        $tinhNangs = \App\Models\TinhNang::where('trang_thai', 1)->orderBy('thu_tu')->get();
        return view('frontend.tinhnangclient.index', compact('tinhNangs'));
    }
}
