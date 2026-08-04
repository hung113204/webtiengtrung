<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoSoGiaoVien extends Model
{
    use HasFactory;

    protected $table = 'ho_so_giao_vien';

    protected $fillable = [
        'id_nguoi_dung',
        'chuyen_mon',
        'kinh_nghiem',
        'bang_cap',
        'gioi_thieu',
        'muc_luong',
    ];

    /**
     * Hồ sơ giáo viên thuộc về 1 Người Dùng
     */
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'id_nguoi_dung');
    }

    /**
     * Các khóa học mà giáo viên được phân công giảng dạy
     */
    public function khoaHocs()
    {
        return $this->belongsToMany(KhoaHoc::class, 'phan_cong_giang_day', 'id_giao_vien', 'id_khoa_hoc')
                    ->withPivot('vai_tro_giang_day', 'ngay_phan_cong')
                    ->withTimestamps();
    }
}
