<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeThi extends Model
{
    use HasFactory;

    protected $table = 'de_thi';

    protected $fillable = [
        'id_bai_hoc',
        'ten_de_thi',
        'mo_ta',
        'thoi_gian_lam',
        'so_cau',
        'diem_dat',
        'id_muc_do',
        'loai_de',
        'trang_thai',
    ];

    protected $casts = [
        'trang_thai' => 'boolean',
        'thoi_gian_lam' => 'integer',
        'so_cau' => 'integer',
        'diem_dat' => 'integer',
    ];

    public function baiHoc()
    {
        return $this->belongsTo(BaiHoc::class, 'id_bai_hoc');
    }

    public function mucDo()
    {
        return $this->belongsTo(MucDo::class, 'id_muc_do');
    }

    public function chiTietDeThis()
    {
        return $this->hasMany(ChiTietDeThi::class, 'id_de_thi');
    }

    public function cauHois()
    {
        return $this->belongsToMany(CauHoi::class, 'chi_tiet_de_thi', 'id_de_thi', 'id_cau_hoi')
                    ->withPivot('thu_tu')
                    ->withTimestamps();
    }
}
