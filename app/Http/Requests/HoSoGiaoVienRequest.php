<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HoSoGiaoVienRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_nguoi_dung' => 'required|exists:nguoi_dung,id',
            'chuyen_mon' => 'nullable|string|max:255',
            'kinh_nghiem' => 'nullable|string|max:255',
            'bang_cap' => 'nullable|string|max:255',
            'gioi_thieu' => 'nullable|string',
            'muc_luong' => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'id_nguoi_dung.required' => 'Vui lòng chọn một người dùng.',
            'id_nguoi_dung.exists' => 'Người dùng không tồn tại trong hệ thống.',
            'muc_luong.numeric' => 'Mức lương phải là một con số.',
            'muc_luong.min' => 'Mức lương không được là số âm.',
        ];
    }
}
