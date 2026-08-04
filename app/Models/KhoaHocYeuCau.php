<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KhoaHocYeuCau extends Model
{
    use HasFactory;

    /**
     * Tên bảng.
     */
    protected $table = 'khoa_hoc_yeu_cau';

    /**
     * Các cột có thể gán giá trị hàng loạt.
     */
    protected $fillable = [
        'khoa_hoc_id',
        'noi_dung',
        'thu_tu',
    ];

    /**
     * Quan hệ belongsTo với model KhoaHoc.
     */
    public function khoaHoc(): BelongsTo
    {
        return $this->belongsTo(KhoaHoc::class, 'khoa_hoc_id');
    }
}