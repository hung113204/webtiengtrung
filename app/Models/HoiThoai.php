<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoiThoai extends Model
{
    use HasFactory;

    protected $table = 'hoi_thoai';

    protected $fillable = [
        'id_bai_hoc',
        'tieu_de',
        'mo_ta',
        'thu_tu',
    ];

    public function baiHoc()
    {
        return $this->belongsTo(BaiHoc::class, 'id_bai_hoc', 'id');
    }

    public function chiTietHoiThoais()
    {
        return $this->hasMany(ChiTietHoiThoai::class, 'id_hoi_thoai', 'id');
    }
}
