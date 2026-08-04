<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NguoiDungRequest extends FormRequest
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
        // Lấy ID người dùng từ route (áp dụng cho trường hợp Update)
        // Lưu ý: Đổi 'nguoidung' thành tên tham số route thực tế của bạn, ví dụ: $this->route('id') hoặc $this->route('user')
        $userId = $this->route('nguoidung') ?? $this->route('id');

        $rules = [
            'ho_ten' => 'required|string|max:255',
            'ten_dang_nhap' => [
                'required',
                'string',
                'max:255',
                Rule::unique('nguoi_dung', 'ten_dang_nhap')->ignore($userId),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('nguoi_dung', 'email')->ignore($userId),
            ],
            'gioi_tinh' => 'nullable|in:Nam,Nữ,Khác',
            'anh_dai_dien' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ngay_sinh' => 'nullable|date',
            'so_dien_thoai' => 'nullable|string|max:20',
            'id_vai_tro'  => 'required|exists:vai_tro,id',
            'trang_thai'  => 'boolean',
            'ghi_chu'     => 'nullable|string',
            // --- Cột Hồ sơ học viên ---
            'trinh_do_hien_tai' => 'nullable|string|max:255',
            'muc_tieu_hoc_tap' => 'nullable|string|max:255',
            'thoi_gian_hoc_du_kien' => 'nullable|string|max:255',
        ];

        // Nếu là thao tác Thêm mới (POST), mật khẩu là bắt buộc
        if ($this->isMethod('POST')) {
            $rules['mat_khau'] = 'required|string|min:6';
        } else {
            // Nếu là thao tác Cập nhật (PUT/PATCH), mật khẩu chỉ kiểm tra khi có nhập
            $rules['mat_khau'] = 'nullable|string|min:6';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'ho_ten.required' => 'Vui lòng nhập họ và tên.',
            'ho_ten.max' => 'Họ tên không được vượt quá 255 ký tự.',
            
            'ten_dang_nhap.required' => 'Vui lòng nhập tên đăng nhập.',
            'ten_dang_nhap.unique' => 'Tên đăng nhập đã tồn tại trong hệ thống.',
            'ten_dang_nhap.max' => 'Tên đăng nhập không được vượt quá 255 ký tự.',
            
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
            'email.unique' => 'Email này đã được đăng ký.',
            
            'mat_khau.required' => 'Vui lòng nhập mật khẩu.',
            'mat_khau.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            
            'vai_tro.required' => 'Vui lòng chọn vai trò.',
            'vai_tro.in' => 'Vai trò không hợp lệ.',
            
            'gioi_tinh.in' => 'Giới tính không hợp lệ.',
            
            'so_dien_thoai.max' => 'Số điện thoại không được vượt quá 20 ký tự.',
        ];
    }
}
