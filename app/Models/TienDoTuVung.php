<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TienDoTuVung extends Model
{
    use HasFactory;

    protected $table = 'tien_do_tu_vung';

    protected $fillable = [
        'id_nguoi_dung',
        'id_tu_vung',
        'trang_thai',
        'interval',
        'ease_factor',
        'next_review_at',
        'ghi_chu',
        'da_luu',
    ];

    protected $casts = [
        'next_review_at' => 'datetime',
        'da_luu' => 'boolean',
        'ease_factor' => 'float',
        'interval' => 'integer',
        'trang_thai' => 'integer',
    ];

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'id_nguoi_dung', 'id');
    }

    public function tuVung()
    {
        return $this->belongsTo(TuVung::class, 'id_tu_vung', 'id');
    }
}
