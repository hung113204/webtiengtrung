<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TuVungRequest extends FormRequest
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
            'tu_han' => 'required|string|max:255',
            'phien_am' => 'required|string|max:255',
            'nghia_tieng_viet' => 'required|string|max:255',
            'vi_du' => 'nullable|string',
            'am_thanh' => 'nullable|file|mimes:mp3,wav,ogg,m4a|max:10240',
            'hinh_anh' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'tu_han.required' => 'Vui lòng nhập chữ Hán.',
            'phien_am.required' => 'Vui lòng nhập phiên âm.',
            'nghia_tieng_viet.required' => 'Vui lòng nhập nghĩa tiếng Việt.',
            'am_thanh.mimes' => 'Định dạng âm thanh không hợp lệ.',
            'am_thanh.max' => 'File âm thanh không được vượt quá 10MB.',
            'hinh_anh.image' => 'File tải lên phải là hình ảnh.',
            'hinh_anh.max' => 'Hình ảnh không được vượt quá 2MB.',
        ];
    }
}
