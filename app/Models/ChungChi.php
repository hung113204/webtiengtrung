<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChungChi extends Model
{
    use HasFactory;

    protected $table = 'chung_chi';

    protected $fillable = [
        'id_nguoi_dung',
        'id_cap_do_hsk',
        'ngay_cap',
        'ma_chung_chi',
    ];

    protected $casts = [
        'ngay_cap' => 'date',
    ];

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'id_nguoi_dung');
    }

    public function capDoHsk()
    {
        return $this->belongsTo(CapDoHSK::class, 'id_cap_do_hsk');
    }
}
