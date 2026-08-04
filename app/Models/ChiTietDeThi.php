<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChiTietDeThi extends Model
{
    use HasFactory;

    protected $table = 'chi_tiet_de_thi';

    protected $fillable = [
        'id_de_thi',
        'id_cau_hoi',
        'thu_tu',
    ];

    protected $casts = [
        'id_de_thi' => 'integer',
        'id_cau_hoi' => 'integer',
        'thu_tu' => 'integer',
    ];

    public function deThi()
    {
        return $this->belongsTo(DeThi::class, 'id_de_thi');
    }

    public function cauHoi()
    {
        return $this->belongsTo(CauHoi::class, 'id_cau_hoi');
    }
}
