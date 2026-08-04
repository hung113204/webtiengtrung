<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CauHoiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id_bai_hoc' => 'required|exists:bai_hoc,id',
            'id_loai_cau_hoi' => 'required|exists:loai_cau_hoi,id',
            'noi_dung' => 'required|string',
            'pinyin' => 'nullable|string|max:255',
            'dich_nghia' => 'nullable|string',
            'giai_thich' => 'nullable|string',
            'id_muc_do' => 'required|exists:muc_dos,id',
            
            'hinh_anh' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'am_thanh' => 'nullable|mimes:mp3,wav|max:5120',
            'am_thanh_giai_thich' => 'nullable|mimes:mp3,wav|max:5120',
            'video' => 'nullable|mimes:mp4,mkv,avi|max:20480',

            'dap_an' => 'required|array|min:1',
            'dap_an.*' => 'required|string',
            'dap_an_dung' => 'required|string',
        ];
    }
}
