<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThongBaoNguoiDung extends Model
{
    use HasFactory;

    protected $table = 'thong_bao_nguoi_dung';

    protected $fillable = [
        'id_thong_bao',
        'id_nguoi_dung',
        'da_doc',
    ];

    protected $casts = [
        'da_doc' => 'boolean',
    ];

    /**
     * Mối quan hệ: Thông báo liên kết
     */
    public function thongBao()
    {
        return $this->belongsTo(ThongBao::class, 'id_thong_bao');
    }

    /**
     * Mối quan hệ: Người dùng nhận thông báo
     */
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'id_nguoi_dung');
    }
}
