<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DangKyKhoaHoc extends Model
{
    use HasFactory;

    protected $table = 'dang_ky_khoa_hoc';

    protected $fillable = [
        'id_nguoi_dung',
        'id_khoa_hoc',
        'ngay_dang_ky',
        'trang_thai',
    ];

    protected $casts = [
        'ngay_dang_ky' => 'datetime',
    ];

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'id_nguoi_dung');
    }

    public function khoaHoc()
    {
        return $this->belongsTo(KhoaHoc::class, 'id_khoa_hoc');
    }

    public function hoaDon()
    {
        return $this->hasOne(HoaDon::class, 'id_dang_ky');
    }
}
