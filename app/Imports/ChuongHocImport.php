<?php

namespace App\Imports;

use App\Models\ChuongHoc;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ChuongHocImport implements ToModel, WithStartRow
{
    protected $id_khoa_hoc;

    public function __construct($id_khoa_hoc)
    {
        $this->id_khoa_hoc = $id_khoa_hoc;
    }

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
        // Kiểm tra nếu tên chương rỗng thì bỏ qua
        if (empty($row[0])) {
            return null;
        }

        $tenChuong = $row[0];
        $thuTu = isset($row[1]) && is_numeric($row[1]) ? (int)$row[1] : null;
        $trangThai = isset($row[2]) ? (int)$row[2] : 1; // Mặc định là hiển thị
        $moTa = isset($row[3]) ? $row[3] : null;

        // Tự động tính thứ tự tiếp theo nếu không cung cấp
        if ($thuTu === null) {
            $maxThuTu = ChuongHoc::where('id_khoa_hoc', $this->id_khoa_hoc)->max('thu_tu') ?? 0;
            $thuTu = $maxThuTu + 1;
        }

        return new ChuongHoc([
            'id_khoa_hoc' => $this->id_khoa_hoc,
            'ten_chuong'  => $tenChuong,
            'slug'        => Str::slug($tenChuong),
            'trang_thai'  => $trangThai,
            'so_bai_hoc'  => 0,
            'thu_tu'      => $thuTu,
            'mo_ta'       => $moTa,
        ]);
    }
}
