<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KhoaHocYeuCauRequest extends FormRequest
{
    /**
     * Xác định quyền của người dùng.
     * Nếu bạn có hệ thống phân quyền, hãy kiểm tra tại đây.
     */
    public function authorize(): bool
    {
        return true; // Cho phép tất cả (hoặc kiểm tra permission)
    }

    /**
     * Quy tắc validation.
     * Dùng chung cho cả store và update.
     */
    public function rules(): array
    {
        // Lấy ID của bản ghi hiện tại (nếu có) để bỏ qua khi kiểm tra unique (nếu cần)
        $id = $this->route('khoa_hoc_yeu_cau') ?: null;

        // Kiểm tra phương thức HTTP để quyết định trường bắt buộc
        $isCreating = $this->isMethod('post');

        return [
            'khoa_hoc_id' => [
                $isCreating ? 'required' : 'sometimes',
                'integer',
                'exists:khoa_hoc,id',
            ],
            'noi_dung' => [
                $isCreating ? 'required' : 'sometimes',
                'string',
                'max:255',
            ],
            'thu_tu' => [
                'nullable',
                'integer',
                'min:1',
            ],
        ];
    }

    /**
     * Tùy chỉnh thông báo lỗi.
     */
    public function messages(): array
    {
        return [
            'khoa_hoc_id.required' => 'ID khóa học là bắt buộc.',
            'khoa_hoc_id.exists'   => 'Khóa học không tồn tại.',
            'noi_dung.required'    => 'Nội dung yêu cầu là bắt buộc.',
            'noi_dung.max'         => 'Nội dung không được vượt quá 255 ký tự.',
            'thu_tu.integer'       => 'Thứ tự phải là số nguyên.',
            'thu_tu.min'           => 'Thứ tự phải lớn hơn hoặc bằng 1.',
        ];
    }
}