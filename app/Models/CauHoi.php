<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CauHoi extends Model
{
    use HasFactory;

    protected $table = 'cau_hoi';

    protected $fillable = [
        'id_bai_hoc',
        'id_loai_cau_hoi',
        'noi_dung',
        'pinyin',
        'dich_nghia',
        'hinh_anh',
        'am_thanh',
        'video',
        'giai_thich',
        'am_thanh_giai_thich',
        'id_muc_do',
    ];

    public function baiHoc()
    {
        return $this->belongsTo(BaiHoc::class, 'id_bai_hoc');
    }

    public function loaiCauHoi()
    {
        return $this->belongsTo(LoaiCauHoi::class, 'id_loai_cau_hoi');
    }

    public function dapAns()
    {
        return $this->hasMany(DapAn::class, 'id_cau_hoi');
    }

    public function mucDo()
    {
        return $this->belongsTo(MucDo::class, 'id_muc_do');
    }

    public function getPart()
    {
        $slug = strtolower($this->loaiCauHoi->slug ?? '');
        $name = strtolower($this->loaiCauHoi->ten_loai ?? '');

        if (str_contains($slug, 'nghe') || str_contains($slug, 'listening') || str_contains($slug, 'audio') || str_contains($name, 'nghe')) {
            return 'listening';
        }

        if (str_contains($slug, 'viet') || str_contains($slug, 'writing') || str_contains($slug, 'sap-xep') || str_contains($name, 'viết') || str_contains($name, 'sắp xếp')) {
            return 'writing';
        }

        return 'reading';
    }
}
