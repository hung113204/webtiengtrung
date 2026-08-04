<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class LuyenVietRequest extends FormRequest
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
            'chu_han' => 'required|string|max:255',
            'pinyin' => 'nullable|string|max:255',
            'nghia' => 'nullable|string|max:255',
            'so_net' => 'nullable|integer|min:1',
            'bo_thu' => 'nullable|string|max:255',
            'thu_tu_net' => 'nullable|string',
            'gif_net_viet' => 'nullable|image|mimes:gif|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'id_bai_hoc.required' => 'Vui lòng chọn bài học.',
            'id_bai_hoc.exists' => 'Bài học không tồn tại trong hệ thống.',
            'chu_han.required' => 'Vui lòng nhập chữ Hán.',
            'so_net.integer' => 'Số nét phải là một số.',
            'gif_net_viet.image' => 'File tải lên phải là hình ảnh.',
            'gif_net_viet.mimes' => 'Hình ảnh minh họa phải có định dạng .gif',
            'gif_net_viet.max' => 'Kích thước file GIF không được vượt quá 2MB.',
        ];
    }
}
