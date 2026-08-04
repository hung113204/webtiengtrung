<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaiHoc extends Model
{
    use HasFactory;

    protected $table = 'bai_hoc';

    protected $fillable = [
        'id_chuong',
        'id_cap_do_hsk',
        'video_id',
        'ten_bai_hoc',
        'slug',
        'loai_dieu_kien',
        'phan_tram_video',
        'mo_ta_ngan',
        'noi_dung',
        'video',
        'anh_bia',
        'audio',
        'tai_lieu',
        'thoi_luong',
        'kich_thuoc',
        'thumbnail',
        'hls_path',
        'thoi_luong_giay',
        'thu_tu',
        'mien_phi',
        'trang_thai',
        'luot_xem',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'mien_phi' => 'boolean',
    ];

    public function chuongHoc()
    {
        return $this->belongsTo(ChuongHoc::class, 'id_chuong', 'id');
    }

    public function videoItem() // Name it videoItem to avoid conflict with the 'video' column name
    {
        return $this->belongsTo(Video::class, 'video_id', 'id');
    }

    public function capDoHsk()
    {
        return $this->belongsTo(CapDoHSK::class, 'id_cap_do_hsk', 'id');
    }

    public function tuVungs()
    {
        return $this->hasMany(TuVung::class, 'id_bai_hoc', 'id');
    }

    public function tienDoVideos()
    {
        return $this->hasMany(TienDoVideo::class, 'id_bai_hoc', 'id');
    }

    public function hoiThoais()
    {
        return $this->hasMany(HoiThoai::class, 'id_bai_hoc', 'id');
    }

    public function nguPhaps()
    {
        return $this->hasMany(NguPhap::class, 'id_bai_hoc', 'id');
    }

    public function luyenViets()
    {
        return $this->hasMany(LuyenViet::class, 'id_bai_hoc', 'id');
    }

    /**
     * Mối quan hệ: Một bài học có nhiều bình luận.
     */
    public function binhLuans()
    {
        return $this->hasMany(BinhLuan::class, 'id_bai_hoc', 'id');
    }

    public function cauHois()
    {
        return $this->hasMany(CauHoi::class, 'id_bai_hoc', 'id');
    }
}
