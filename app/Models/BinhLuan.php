<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BinhLuan extends Model
{
    use HasFactory;

    protected $table = 'binh_luan';

    protected $fillable = [
        'id_nguoi_dung',
        'id_bai_hoc',
        'noi_dung',
        'parent_id',
        'trang_thai',
    ];

    protected $casts = [
        'trang_thai' => 'boolean',
    ];

    /**
     * Quan hệ: Bình luận thuộc về một học viên/người dùng.
     */
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'id_nguoi_dung');
    }

    /**
     * Quan hệ: Bình luận thuộc về một bài học.
     */
    public function baiHoc()
    {
        return $this->belongsTo(BaiHoc::class, 'id_bai_hoc');
    }

    /**
     * Quan hệ: Bình luận cha.
     */
    public function parent()
    {
        return $this->belongsTo(BinhLuan::class, 'parent_id');
    }

    /**
     * Quan hệ: Các bình luận con (phản hồi).
     */
    public function replies()
    {
        return $this->hasMany(BinhLuan::class, 'parent_id')->with('nguoiDung');
    }
}
