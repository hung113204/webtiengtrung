<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiaiDoanLoTrinh extends Model
{
    use HasFactory;

    protected $table = 'giai_doan_lo_trinh';

    protected $fillable = [
        'id_lo_trinh',
        'icon_text',
        'ten_giai_doan',
        'mo_ta',
        'thu_tu',
    ];

    public function loTrinh()
    {
        return $this->belongsTo(LoTrinh::class, 'id_lo_trinh');
    }

    public function khoaHocs()
    {
        return $this->belongsToMany(KhoaHoc::class, 'giai_doan_khoa_hoc', 'id_giai_doan', 'id_khoa_hoc')
                    ->withPivot('thu_tu')
                    ->orderBy('thu_tu');
    }
}
