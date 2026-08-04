<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChiTietHoiThoai extends Model
{
    use HasFactory;

    protected $table = 'chi_tiet_hoi_thoai';

    protected $fillable = [
        'id_hoi_thoai',
        'nhan_vat',
        'noi_dung_tieng_trung',
        'pinyin',
        'nghia_tieng_viet',
        'am_thanh',
        'thu_tu',
    ];

    public function hoiThoai()
    {
        return $this->belongsTo(HoiThoai::class, 'id_hoi_thoai', 'id');
    }
}
