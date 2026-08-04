<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CapDoHSKRequest extends FormRequest
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
        $id = $this->route('capdohsk') ? $this->route('capdohsk')->id : null;

        return [
            'ten_cap_do' => 'required|string|max:50|unique:cap_do_hsk,ten_cap_do,' . $id,
            'slug' => 'required|string|unique:cap_do_hsk,slug,' . $id,
            'so_tu_vung' => 'nullable|integer|min:0',
            'so_ngu_phap' => 'nullable|integer|min:0',
            'mo_ta' => 'nullable|string',
            'thu_tu' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'ten_cap_do.required' => 'Tên cấp độ không được để trống.',
            'ten_cap_do.max' => 'Tên cấp độ không được vượt quá 50 ký tự.',
            'ten_cap_do.unique' => 'Tên cấp độ đã tồn tại.',
            'slug.required' => 'Slug không được để trống.',
            'slug.unique' => 'Slug đã tồn tại.',
            'so_tu_vung.integer' => 'Số lượng từ vựng phải là số.',
            'so_ngu_phap.integer' => 'Số lượng ngữ pháp phải là số.',
            'thu_tu.required' => 'Thứ tự không được để trống.',
            'thu_tu.integer' => 'Thứ tự phải là một số nguyên.',
            'thu_tu.min' => 'Thứ tự phải lớn hơn hoặc bằng 1.',
        ];
    }
}
