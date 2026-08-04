<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChiTietLuyenThi extends Model
{
    use HasFactory;

    protected $table = 'chi_tiet_luyen_thi';

    protected $fillable = [
        'id_phien_luyen_thi',
        'id_cau_hoi',
        'id_dap_an',
        'dap_an_tu_luan',
        'dung',
    ];

    protected $casts = [
        'dung' => 'boolean',
    ];

    public function phienLuyenThi()
    {
        return $this->belongsTo(PhienLuyenThi::class, 'id_phien_luyen_thi');
    }

    public function cauHoi()
    {
        return $this->belongsTo(CauHoi::class, 'id_cau_hoi');
    }

    public function dapAn()
    {
        return $this->belongsTo(DapAn::class, 'id_dap_an');
    }
}
