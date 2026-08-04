<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoSoHocVien extends Model
{
    use HasFactory;

    protected $table = 'ho_so_hoc_vien';

    protected $fillable = [
        'id_nguoi_dung',
        'trinh_do_hien_tai',
        'muc_tieu_hoc_tap',
        'muc_tieu_hsk',
        'thoi_gian_hoc_du_kien',
        'lo_trinh_ai'
    ];

    protected $casts = [
        'lo_trinh_ai' => 'array',
    ];

    /**
     * Lấy thông tin người dùng sở hữu hồ sơ này
     */
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'id_nguoi_dung');
    }
}
