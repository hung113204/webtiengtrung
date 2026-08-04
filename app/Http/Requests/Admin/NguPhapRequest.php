<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class NguPhapRequest extends FormRequest
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
            'tieu_de' => 'required|string|max:255',
            'cau_truc' => 'required|string|max:255',
            'giai_thich' => 'required|string',
            'vi_du' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'id_bai_hoc.required' => 'Vui lòng chọn bài học.',
            'id_bai_hoc.exists' => 'Bài học không hợp lệ.',
            'tieu_de.required' => 'Vui lòng nhập tên điểm ngữ pháp.',
            'tieu_de.max' => 'Tên điểm ngữ pháp không được vượt quá 255 ký tự.',
            'cau_truc.required' => 'Vui lòng nhập cấu trúc ngữ pháp.',
            'cau_truc.max' => 'Cấu trúc ngữ pháp không được vượt quá 255 ký tự.',
            'giai_thich.required' => 'Vui lòng nhập giải thích ngữ pháp.',
        ];
    }
}
