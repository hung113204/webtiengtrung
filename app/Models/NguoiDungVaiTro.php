<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class NguoiDungVaiTro extends Pivot
{
    protected $table = 'nguoi_dung_vai_tro';

    // Không cần khai báo primaryKey mặc định vì Pivot thường dùng khóa ghép hoặc id tự tăng đã định nghĩa trong DB
    
    protected $fillable = [
        'id_nguoi_dung',
        'id_vai_tro',
    ];
}
