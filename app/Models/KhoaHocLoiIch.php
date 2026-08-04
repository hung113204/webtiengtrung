<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KhoaHocLoiIch extends Model
{
    use HasFactory;

    /**
     * Tên bảng (nếu không trùng với quy tắc số nhiều của Laravel).
     */
    protected $table = 'khoa_hoc_loi_ich';

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