<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDanhMucKhoaHocRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'parent_id'    => 'nullable|exists:danh_muc_khoa_hoc,id',
            'ten_danh_muc' => 'required|string|max:255',
            'slug'         => 'required|string|max:255|unique:danh_muc_khoa_hoc,slug',
            'mo_ta'        => 'nullable|string',
            'thu_tu'       => 'nullable|integer',
            'trang_thai'   => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'ten_danh_muc.required' => 'Vui lòng nhập tên danh mục.',
            'ten_danh_muc.max'      => 'Tên danh mục không được vượt quá 255 ký tự.',
            'slug.required'         => 'Đường dẫn (slug) là bắt buộc.',
            'slug.unique'           => 'Đường dẫn (slug) này đã tồn tại, vui lòng chọn tên khác.',
            'parent_id.exists'      => 'Danh mục cha không hợp lệ.',
        ];
    }
}
