<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class LoaiCauHoiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('loaicauhoi') ? $this->route('loaicauhoi') : null;

        return [
            'ten_loai' => 'required|string|max:255|unique:loai_cau_hoi,ten_loai,' . $id,
            'slug' => 'nullable|string|max:255|unique:loai_cau_hoi,slug,' . $id,
            'thu_tu' => 'nullable|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'ten_loai.required' => 'Vui lòng nhập tên loại câu hỏi.',
            'ten_loai.unique' => 'Tên loại câu hỏi này đã tồn tại.',
            'ten_loai.max' => 'Tên loại câu hỏi không được vượt quá 255 ký tự.',
        ];
    }
}
