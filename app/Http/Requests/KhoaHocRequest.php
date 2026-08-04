<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KhoaHocRequest extends FormRequest
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
        $id = $this->route('khoahoc') ? $this->route('khoahoc')->id : null;

        return [
            'ten_khoa_hoc' => 'required|string|max:255',
            'slug' => 'required|string|unique:khoa_hoc,slug,' . $id,
            'mo_ta_ngan' => 'nullable|string|max:500',
            'mo_ta' => 'nullable|string',
            'anh_bia' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'gia' => 'nullable|numeric|min:0',
            'gia_giam' => 'nullable|numeric|min:0|lte:gia',
            'id_cap_do_hsk' => 'required|exists:cap_do_hsk,id',
            'id_danh_muc_khoa_hoc' => 'required|exists:danh_muc_khoa_hoc,id',
            'noi_bat' => 'nullable|boolean',
            'trang_thai' => 'nullable|boolean',
            'xoa_anh_bia' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'ten_khoa_hoc.required' => 'Vui lòng nhập tên khóa học.',
            'ten_khoa_hoc.max' => 'Tên khóa học không được vượt quá 255 ký tự.',
            'slug.required' => 'Vui lòng nhập đường dẫn (slug).',
            'slug.unique' => 'Đường dẫn (slug) này đã được sử dụng.',
            'anh_bia.image' => 'Ảnh bìa phải là một hình ảnh.',
            'anh_bia.mimes' => 'Ảnh bìa phải có định dạng: jpeg, png, jpg, gif, svg.',
            'anh_bia.max' => 'Ảnh bìa không được vượt quá 10MB.',
            'gia.numeric' => 'Giá khóa học phải là một số.',
            'gia.min' => 'Giá khóa học không được nhỏ hơn 0.',
            'gia_giam.numeric' => 'Giá giảm phải là một số.',
            'gia_giam.min' => 'Giá giảm không được nhỏ hơn 0.',
            'gia_giam.lte' => 'Giá giảm không được lớn hơn giá gốc.',
            'id_cap_do_hsk.required' => 'Vui lòng chọn cấp độ HSK.',
            'id_cap_do_hsk.exists' => 'Cấp độ HSK không hợp lệ.',
            'id_danh_muc_khoa_hoc.required' => 'Vui lòng chọn danh mục khóa học.',
            'id_danh_muc_khoa_hoc.exists' => 'Danh mục khóa học không hợp lệ.',
            'tong_bai_hoc.integer' => 'Tổng bài học phải là số nguyên.',
            'tong_thoi_gian.integer' => 'Tổng thời gian phải là số nguyên.',
        ];
    }
}
