<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoaiCauHoi extends Model
{
    use HasFactory;

    protected $table = 'loai_cau_hoi';

    protected $fillable = [
        'ten_loai',
        'slug',
        'thu_tu',
    ];
}
