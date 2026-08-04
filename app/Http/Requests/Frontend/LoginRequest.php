<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email'    => 'required|string',
            'password' => 'required|string|min:6',
        ];
    }

    /**
     * Tùy chỉnh thông báo lỗi.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'email.required'    => 'Vui lòng nhập Email hoặc Tên đăng nhập.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min'      => 'Vui lòng nhập mật khẩu hợp lệ (tối thiểu 6 ký tự).',
        ];
    }
}
