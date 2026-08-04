<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LuyenViet extends Model
{
    use HasFactory;

    protected $table = 'luyen_viet';

    protected $fillable = [
        'id_bai_hoc',
        'chu_han',
        'pinyin',
        'nghia',
        'so_net',
        'bo_thu',
        'thu_tu_net',
        'gif_net_viet',
    ];

    public function baiHoc()
    {
        return $this->belongsTo(BaiHoc::class, 'id_bai_hoc');
    }
}
