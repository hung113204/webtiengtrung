<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DangKyHocThu extends Model
{
    use HasFactory;

    protected $table = 'dang_ky_hoc_thus';

    protected $fillable = [
        'email',
        'trang_thai',
    ];
}
