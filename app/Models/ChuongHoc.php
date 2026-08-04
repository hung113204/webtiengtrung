<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChuongHoc extends Model
{
    use HasFactory;

    protected $table = 'chuong_hoc';

    protected $fillable = [
        'id_khoa_hoc',
        'ten_chuong',
        'slug',
        'trang_thai',
        'so_bai_hoc',
        'thu_tu',
        'mo_ta',
    ];

    protected $casts = [
        'trang_thai' => 'boolean',
    ];

    public function khoaHoc()
    {
        return $this->belongsTo(KhoaHoc::class, 'id_khoa_hoc', 'id');
    }

    public function baiHocs()
    {
        return $this->hasMany(BaiHoc::class, 'id_chuong', 'id');
    }
}
