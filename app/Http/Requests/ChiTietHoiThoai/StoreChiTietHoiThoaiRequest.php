<?php

namespace App\Http\Requests\ChiTietHoiThoai;

use Illuminate\Foundation\Http\FormRequest;

class StoreChiTietHoiThoaiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'id_hoi_thoai' => 'required|exists:hoi_thoai,id',
            'nhan_vat' => 'nullable|string|max:255',
            'noi_dung_tieng_trung' => 'required|string',
            'pinyin' => 'nullable|string|max:255',
            'nghia_tieng_viet' => 'nullable|string',
            'am_thanh' => 'nullable|file|mimes:mp3,wav,ogg,m4a|max:20480',
            'thu_tu' => 'nullable|integer|min:0',
        ];
    }
}
