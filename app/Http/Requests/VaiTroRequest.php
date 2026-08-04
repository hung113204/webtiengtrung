<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VaiTroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('vaitro') ?? null;
        
        return [
            'ten_vai_tro' => 'required|string|max:100',
            'slug' => 'nullable|string|max:100|unique:vai_tro,slug,' . $id,
            'mo_ta' => 'nullable|string',
            'level' => 'nullable|integer',
            'permissions' => 'nullable|array',
            'permissions.*' => 'integer|exists:quyen,id',
        ];
    }

    public function messages(): array
    {
        return [
            'ten_vai_tro.required' => 'Vui lòng nhập tên vai trò.',
            'ten_vai_tro.max' => 'Tên vai trò không được vượt quá 100 ký tự.',
        ];
    }
}
