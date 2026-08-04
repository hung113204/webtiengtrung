<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoTrinh extends Model
{
    use HasFactory;

    protected $table = 'lo_trinh';

    protected $fillable = [
        'ten_lo_trinh',
        'slug',
        'mo_ta_ngan',
        'mo_ta',
        'anh_bia',
        'trang_thai',
        'thu_tu',
    ];

    public function giaiDoans()
    {
        return $this->hasMany(GiaiDoanLoTrinh::class, 'id_lo_trinh')->orderBy('thu_tu');
    }
}