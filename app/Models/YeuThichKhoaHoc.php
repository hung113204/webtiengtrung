<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YeuThichKhoaHoc extends Model
{
    use HasFactory;

    protected $table = 'yeu_thich_khoa_hocs';

    protected $fillable = [
        'id_nguoi_dung',
        'id_khoa_hoc',
    ];

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'id_nguoi_dung');
    }

    public function khoaHoc()
    {
        return $this->belongsTo(KhoaHoc::class, 'id_khoa_hoc');
    }
}