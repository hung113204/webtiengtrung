<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ChuongHocRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Xử lý phân quyền qua Middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ten_chuong' => 'required|string|max:255',
            'id_khoa_hoc' => 'required|exists:khoa_hoc,id',
            'thu_tu' => 'nullable|integer|min:0',
            'trang_thai' => 'required|boolean',
            'mo_ta' => 'nullable|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'ten_chuong.required' => 'Vui lòng nhập tên chương học.',
            'id_khoa_hoc.required' => 'Vui lòng chọn khóa học.',
            'id_khoa_hoc.exists' => 'Khóa học không tồn tại.',
            'trang_thai.required' => 'Vui lòng chọn trạng thái.',
        ];
    }
}
