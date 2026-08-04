<?php

namespace App\Http\Requests\HoiThoai;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHoiThoaiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'id_bai_hoc' => 'required|exists:bai_hoc,id',
            'tieu_de' => 'nullable|string|max:255',
            'mo_ta' => 'nullable|string',
            'thu_tu' => 'nullable|integer|min:0',
        ];
    }
}
