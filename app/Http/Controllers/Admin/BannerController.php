<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = Banner::orderBy('thu_tu')->get();
        return view('admin.banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.banners.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title_prefix' => 'required|string|max:255',
            'title_highlight' => 'required|string|max:255',
            'thu_tu' => 'nullable|integer',
            'grid_char_1' => 'nullable|string|max:10',
            'grid_char_2' => 'nullable|string|max:10',
            'grid_char_3' => 'nullable|string|max:10',
            'grid_char_4' => 'nullable|string|max:10',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active') ? true : false;
        $data['thu_tu'] = $request->input('thu_tu') ?? 0;

        Banner::create($data);

        return redirect()->route('admin.banners.index')->with('success', 'Thêm Banner thành công.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'title_prefix' => 'required|string|max:255',
            'title_highlight' => 'required|string|max:255',
            'thu_tu' => 'nullable|integer',
            'grid_char_1' => 'nullable|string|max:10',
            'grid_char_2' => 'nullable|string|max:10',
            'grid_char_3' => 'nullable|string|max:10',
            'grid_char_4' => 'nullable|string|max:10',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active') ? true : false;
        $data['thu_tu'] = $request->input('thu_tu') ?? 0;

        $banner->update($data);

        return redirect()->route('admin.banners.index')->with('success', 'Cập nhật Banner thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner)
    {
        $banner->delete();
        return redirect()->route('admin.banners.index')->with('success', 'Xóa Banner thành công.');
    }
}
