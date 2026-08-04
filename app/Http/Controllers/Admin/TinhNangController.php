<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TinhNang;
use Illuminate\Http\Request;

class TinhNangController extends Controller
{
    public function index()
    {
        $tinhNangs = TinhNang::orderBy('thu_tu')->get();
        return view('admin.tinhnang.index', compact('tinhNangs'));
    }

    public function create()
    {
        return view('admin.tinhnang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tieu_de' => 'required|string|max:255',
            'thu_tu' => 'nullable|integer',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240'
        ]);

        $data = $request->except(['_token', 'danh_sach_bullet_raw']);
        
        // Chuyển danh sách bullet từ string sang array
        if ($request->filled('danh_sach_bullet_raw')) {
            $bullets = array_filter(array_map('trim', explode("\n", $request->danh_sach_bullet_raw)));
            $data['danh_sach_bullet'] = $bullets;
        }

        // Upload ảnh
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('features', 'public');
            $data['image_url'] = '/storage/' . $path;
        }

        TinhNang::create($data);

        return redirect()->route('admin.tinhnang.index')->with('success', 'Thêm tính năng thành công!');
    }

    public function edit(TinhNang $tinhNang)
    {
        return view('admin.tinhnang.edit', compact('tinhNang'));
    }

    public function update(Request $request, TinhNang $tinhNang)
    {
        $request->validate([
            'tieu_de' => 'required|string|max:255',
            'thu_tu' => 'nullable|integer',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240'
        ]);

        $data = $request->except(['_token', '_method', 'danh_sach_bullet_raw', 'image_file']);

        if ($request->filled('danh_sach_bullet_raw')) {
            $bullets = array_filter(array_map('trim', explode("\n", $request->danh_sach_bullet_raw)));
            $data['danh_sach_bullet'] = $bullets;
        } else {
            $data['danh_sach_bullet'] = null;
        }

        // Upload ảnh
        if ($request->hasFile('image_file')) {
            if ($tinhNang->image_url && str_starts_with($tinhNang->image_url, '/storage/')) {
                $oldPath = str_replace('/storage/', '', $tinhNang->image_url);
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('image_file')->store('features', 'public');
            $data['image_url'] = '/storage/' . $path;
        }

        // Handle checkbox since unchecked won't send data
        $data['trang_thai'] = $request->has('trang_thai') ? 1 : 0;

        $tinhNang->update($data);

        return redirect()->route('admin.tinhnang.index')->with('success', 'Cập nhật tính năng thành công!');
    }

    public function destroy(TinhNang $tinhNang)
    {
        if ($tinhNang->image_url && str_starts_with($tinhNang->image_url, '/storage/')) {
            $oldPath = str_replace('/storage/', '', $tinhNang->image_url);
            \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
        }
        
        $tinhNang->delete();
        return redirect()->route('admin.tinhnang.index')->with('success', 'Đã xóa tính năng!');
    }
}
