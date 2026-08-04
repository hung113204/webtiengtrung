<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhienLuyenThi extends Model
{
    use HasFactory;

    protected $table = 'phien_luyen_thi';

    protected $fillable = [
        'id_de_thi',
        'id_nguoi_dung',
        'thoi_gian_bat_dau',
        'thoi_gian_ket_thuc',
        'tong_diem',
        'so_cau_dung',
        'so_cau_sai',
        'trang_thai',
    ];

    protected $casts = [
        'thoi_gian_bat_dau' => 'datetime',
        'thoi_gian_ket_thuc' => 'datetime',
        'tong_diem' => 'decimal:2',
        'so_cau_dung' => 'integer',
        'so_cau_sai' => 'integer',
    ];

    public function deThi()
    {
        return $this->belongsTo(DeThi::class, 'id_de_thi');
    }

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'id_nguoi_dung');
    }

    public function chiTietLuyenThis()
    {
        return $this->hasMany(ChiTietLuyenThi::class, 'id_phien_luyen_thi');
    }
}
