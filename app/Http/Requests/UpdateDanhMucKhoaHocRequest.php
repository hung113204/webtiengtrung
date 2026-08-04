<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDanhMucKhoaHocRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $danhmuc = $this->route('danhmuc');
        $id = $danhmuc ? $danhmuc->id : null;

        return [
            'parent_id'    => [
                'nullable',
                'exists:danh_muc_khoa_hoc,id',
                // Không cho chọn chính nó làm cha
                function ($attribute, $value, $fail) use ($id) {
                    if ($value && $value == $id) {
                        $fail('Danh mục không thể là cha của chính nó.');
                    }
                },
            ],
            'ten_danh_muc' => 'required|string|max:255',
            'slug'         => 'required|string|max:255|unique:danh_muc_khoa_hoc,slug,' . $id,
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
