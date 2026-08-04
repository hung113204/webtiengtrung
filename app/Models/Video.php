<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $table = 'videos';

    protected $fillable = [
        'hash_id',
        'ten_video',
        'file_path',
        'hls_path',
        'thumbnail_path',
        'thoi_luong_giay',
        'kich_thuoc',
        'trang_thai',
        'phan_tram',
        'thong_bao_loi',
    ];

    public function baiHocs()
    {
        return $this->hasMany(BaiHoc::class, 'video_id');
    }
}
