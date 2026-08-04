<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DanhGia extends Model
{
    use HasFactory;

    protected $table = 'danh_gia';

    protected $fillable = [
        'id_nguoi_dung',
        'id_khoa_hoc',
        'so_sao',
        'tieu_de',
        'noi_dung',
        'uu_diem',
        'nhuoc_diem',
        'trang_thai',
    ];

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'id_nguoi_dung');
    }

    public function khoaHoc()
    {
        return $this->belongsTo(KhoaHoc::class, 'id_khoa_hoc');
    }

    public function getAvatarChuCaiAttribute()
    {
        if ($this->nguoiDung && $this->nguoiDung->ho_ten) {
            $words = explode(' ', trim($this->nguoiDung->ho_ten));
            $lastName = end($words);
            return mb_substr($lastName, 0, 1, 'UTF-8');
        }
        return 'H'; // Fallback
    }
}
