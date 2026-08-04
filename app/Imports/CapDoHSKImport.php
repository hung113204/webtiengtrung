<?php

namespace App\Imports;

use App\Models\CapDoHSK;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class CapDoHSKImport implements ToModel, WithStartRow
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
        // Kiểm tra nếu tên HSK rỗng thì bỏ qua
        if (empty($row[0])) {
            return null;
        }

        $tenCapDo = $row[0];
        $moTa = isset($row[1]) ? $row[1] : null;

        // Tự động tính thứ tự tiếp theo
        $maxThuTu = CapDoHSK::max('thu_tu') ?? 0;

        return new CapDoHSK([
            'ten_cap_do'  => $tenCapDo,
            'slug'        => Str::slug($tenCapDo),
            'so_tu_vung'  => 0,
            'so_ngu_phap' => 0,
            'mo_ta'       => $moTa,
            'thu_tu'      => $maxThuTu + 1
        ]);
    }
}
