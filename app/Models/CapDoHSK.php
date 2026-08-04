<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CapDoHSK extends Model
{
    use HasFactory;

    protected $table = 'cap_do_hsk';

    protected $fillable = [
        'ten_cap_do',
        'slug',
        'so_tu_vung',
        'so_ngu_phap',
        'mo_ta',
        'thu_tu'
    ];

    /**
     * Lấy các khóa học thuộc cấp độ này.
     */
    public function khoaHocs()
    {
        return $this->hasMany(KhoaHoc::class, 'id_cap_do_hsk');
    }

    /**
     * Lấy các bài học thuộc cấp độ này.
     */
    public function baiHocs()
    {
        return $this->hasMany(BaiHoc::class, 'id_cap_do_hsk');
    }

    /**
     * Lấy tất cả từ vựng thuộc cấp độ này (thông qua bài học).
     */
    public function tuVungs()
    {
        return $this->hasManyThrough(TuVung::class, BaiHoc::class, 'id_cap_do_hsk', 'id_bai_hoc');
    }

    /**
     * Lấy tất cả ngữ pháp thuộc cấp độ này (thông qua bài học).
     */
    public function nguPhaps()
    {
        return $this->hasManyThrough(NguPhap::class, BaiHoc::class, 'id_cap_do_hsk', 'id_bai_hoc');
    }
}
