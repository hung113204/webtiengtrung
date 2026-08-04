<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class DeThiRequest extends FormRequest
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
            'ten_de_thi' => 'required|string|max:255',
            'id_bai_hoc' => 'nullable|integer|exists:bai_hoc,id',
            'mo_ta' => 'nullable|string',
            'thoi_gian_lam' => 'required|integer|min:0',
            'diem_dat' => 'nullable|integer|min:0',
            'id_muc_do' => 'nullable|integer|exists:muc_dos,id',
            'loai_de' => 'required|in:Luyện tập,Thi thử,Kiểm tra',
            'trang_thai' => 'required|in:0,1,true,false',
        ];
    }

    /**
     * Prepare data for validation.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'trang_thai' => filter_var($this->trang_thai, FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
            'diem_dat' => $this->diem_dat ?? 0,
        ]);
    }
}
