<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThongBao extends Model
{
    use HasFactory;

    protected $table = 'thong_bao';

    protected $fillable = [
        'tieu_de',
        'noi_dung',
        'id_nguoi_gui',
    ];

    /**
     * Mối quan hệ: Người gửi thông báo (có thể là một Admin/Giáo viên)
     */
    public function nguoiGui()
    {
        return $this->belongsTo(NguoiDung::class, 'id_nguoi_gui');
    }

    /**
     * Mối quan hệ nhiều-nhiều: Danh sách người dùng nhận thông báo
     */
    public function nguoiDungs()
    {
        return $this->belongsToMany(
            NguoiDung::class,
            'thong_bao_nguoi_dung',
            'id_thong_bao',
            'id_nguoi_dung'
        )->withPivot('da_doc')->withTimestamps();
    }
}
