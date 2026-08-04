<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoaDon extends Model
{
    use HasFactory;

    protected $table = 'hoa_don';

    protected $fillable = [
        'ma_hoa_don',
        'id_dang_ky',
        'id_nguoi_dung',
        'so_tien',
        'phuong_thuc_thanh_toan',
        'ma_giao_dich',
        'trang_thai',
        'ngay_thanh_toan',
    ];

    protected $casts = [
        'ngay_thanh_toan' => 'datetime',
    ];

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'id_nguoi_dung');
    }

    public function dangKyKhoaHoc()
    {
        return $this->belongsTo(DangKyKhoaHoc::class, 'id_dang_ky');
    }
}
