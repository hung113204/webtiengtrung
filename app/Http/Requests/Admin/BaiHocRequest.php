<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BaiHocRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Phân quyền sẽ được xử lý ở Middleware hoặc Policy
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ten_bai_hoc' => 'required|string|max:255',
            'mo_ta_ngan' => 'nullable|string|max:500',
            'id_chuong' => 'required|exists:chuong_hoc,id',
            'id_cap_do_hsk' => 'nullable|exists:cap_do_hsk,id',
            'thu_tu' => 'nullable|integer|min:0',
            'trang_thai' => 'required|in:draft,published,archived',
            'noi_dung' => 'nullable|string',
            'mien_phi' => 'boolean',
            'thoi_luong_giay' => 'nullable|integer|min:0',
            'video_type' => 'nullable|in:url,library',
            'video_url' => 'nullable|url|max:500',
            'video_id' => 'nullable|exists:videos,id',
            'anh_bia' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'audio' => 'nullable|file|mimes:mp3,wav,ogg,m4a|max:20480',
            'tai_lieu' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar|max:51200',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'ten_bai_hoc.required' => 'Vui lòng nhập tên bài học.',
            'id_chuong.required' => 'Vui lòng chọn chương học.',
            'id_chuong.exists' => 'Chương học không tồn tại.',
            'trang_thai.required' => 'Vui lòng chọn trạng thái.',
            'trang_thai.in' => 'Trạng thái không hợp lệ.',
            'video_type.required' => 'Vui lòng chọn nguồn video.',
            'video_url.url' => 'Đường dẫn video không hợp lệ.',
            'video_file.file' => 'Video phải là một tệp đính kèm.',
            'video_file.mimetypes' => 'Định dạng video không hỗ trợ. Chỉ hỗ trợ mp4, mov, avi.',
            'video_file.max' => 'Kích thước video không được vượt quá 2GB.',
            'anh_bia.image' => 'File tải lên phải là hình ảnh.',
            'anh_bia.max' => 'Kích thước ảnh không được vượt quá 2MB.',
        ];
    }
}
