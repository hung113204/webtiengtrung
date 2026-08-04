<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TuVung extends Model
{
    use HasFactory;

    protected $table = 'tu_vung';

    protected $fillable = [
        'id_bai_hoc',
        'tu_han',
        'phien_am',
        'nghia_tieng_viet',
        'am_thanh',
        'hinh_anh',
        'vi_du',
    ];

    public function baiHoc()
    {
        return $this->belongsTo(BaiHoc::class, 'id_bai_hoc');
    }

    public function tienDo()
    {
        return $this->hasMany(TienDoTuVung::class, 'id_tu_vung', 'id');
    }
}
