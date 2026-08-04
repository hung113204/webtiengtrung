<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TienDoHoc extends Model
{
    use HasFactory;

    protected $table = 'tien_do_hoc';

    protected $fillable = [
        'id_nguoi_dung',
        'id_bai_hoc',
        'phan_tram_hoan_thanh',
        'da_hoan_thanh',
        'lan_hoc_cuoi',
    ];

    protected $casts = [
        'da_hoan_thanh' => 'boolean',
        'lan_hoc_cuoi' => 'datetime',
    ];

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'id_nguoi_dung');
    }

    public function baiHoc()
    {
        return $this->belongsTo(BaiHoc::class, 'id_bai_hoc');
    }
}
