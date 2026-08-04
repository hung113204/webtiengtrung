<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KhoaHoc extends Model
{
    use HasFactory;

    protected $table = 'khoa_hoc';

    protected $fillable = [
        'ten_khoa_hoc',
        'slug',
        'mo_ta_ngan',
        'mo_ta',
        'anh_bia',
        'gia',
        'gia_giam',
        'id_cap_do_hsk',
        'tong_bai_hoc',
        'id_danh_muc_khoa_hoc',
        'tong_thoi_gian',
        'noi_bat',
        'trang_thai',
    ];

    public function danhMucKhoaHoc()
    {
        return $this->belongsTo(DanhMucKhoaHoc::class, 'id_danh_muc_khoa_hoc');
    }

    public function capDoHSK()
    {
        return $this->belongsTo(CapDoHSK::class, 'id_cap_do_hsk');
    }

    public function chuongHocs()
    {
        return $this->hasMany(ChuongHoc::class, 'id_khoa_hoc', 'id');
    }

    public function baiHocs()
    {
        return $this->hasManyThrough(BaiHoc::class, ChuongHoc::class, 'id_khoa_hoc', 'id_chuong', 'id', 'id');
    }

    public function danhGias()
    {
        return $this->hasMany(DanhGia::class, 'id_khoa_hoc', 'id');
    }

    /**
     * Các giáo viên được phân công giảng dạy khóa học này
     */
    public function giaoViens()
    {
        return $this->belongsToMany(HoSoGiaoVien::class, 'phan_cong_giang_day', 'id_khoa_hoc', 'id_giao_vien')
                    ->withPivot('vai_tro_giang_day', 'ngay_phan_cong')
                    ->withTimestamps();
    }

    public function dangKyKhoaHocs()
    {
        return $this->hasMany(DangKyKhoaHoc::class, 'id_khoa_hoc', 'id');
    }
    public function yeuCau()
{
    return $this->hasMany(KhoaHocYeuCau::class, 'khoa_hoc_id')->orderBy('thu_tu');
}
public function loiIch()
{
    return $this->hasMany(KhoaHocLoiIch::class, 'khoa_hoc_id')->orderBy('thu_tu');
}

    /**
     * Mối quan hệ: Khóa học được nhiều người dùng yêu thích.
     */
    public function nguoiDungYeuThichs()
    {
        return $this->belongsToMany(NguoiDung::class, 'yeu_thich_khoa_hocs', 'id_khoa_hoc', 'id_nguoi_dung')->withTimestamps();
    }
}
