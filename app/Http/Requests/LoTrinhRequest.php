<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoTrinhRequest extends FormRequest
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
            'ten_lo_trinh' => 'required|string|max:255',
            'mo_ta_ngan'   => 'nullable|string|max:255',
            'mo_ta'        => 'nullable|string',
            'anh_bia'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'trang_thai'   => 'nullable|in:0,1',
            'thu_tu'       => 'nullable|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'ten_lo_trinh.required' => 'Vui lòng nhập tên lộ trình.',
            'anh_bia.image' => 'Ảnh bìa phải là một hình ảnh.',
        ];
    }
}
