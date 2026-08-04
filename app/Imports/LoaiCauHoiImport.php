<?php

namespace App\Imports;

use App\Models\LoaiCauHoi;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class LoaiCauHoiImport implements ToModel, WithStartRow
{
    /**
     * Bỏ qua dòng tiêu đề đầu tiên.
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if (empty($row[0])) {
            return null; // Bỏ qua nếu không có tên loại câu hỏi
        }

        $tenLoai = $row[0];
        
        // Tự động tính thứ tự tiếp theo
        $maxThuTu = LoaiCauHoi::max('thu_tu') ?? 0;

        return new LoaiCauHoi([
            'ten_loai' => $tenLoai,
            'slug'     => Str::slug($tenLoai),
            'thu_tu'   => $maxThuTu + 1,
        ]);
    }
}
