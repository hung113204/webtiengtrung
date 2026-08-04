<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhanCongGiangDay extends Model
{
    use HasFactory;

    protected $table = 'phan_cong_giang_day';

    protected $fillable = [
        'id_giao_vien',
        'id_khoa_hoc',
        'vai_tro_giang_day',
        'ngay_phan_cong',
    ];

    public function giaoVien()
    {
        return $this->belongsTo(HoSoGiaoVien::class, 'id_giao_vien');
    }

    public function khoaHoc()
    {
        return $this->belongsTo(KhoaHoc::class, 'id_khoa_hoc');
    }
}
