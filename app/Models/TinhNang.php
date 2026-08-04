<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TinhNang extends Model
{
    use HasFactory;

    protected $table = 'tinh_nangs';

    protected $fillable = [
        'tieu_de',
        'badge_text',
        'mo_ta',
        'danh_sach_bullet',
        'image_url',
        'vi_tri_anh',
        'stat_number',
        'stat_label',
        'stat_icon',
        'button_text',
        'button_link',
        'thu_tu',
        'trang_thai'
    ];

    protected $casts = [
        'danh_sach_bullet' => 'array',
        'trang_thai' => 'boolean',
    ];
}
