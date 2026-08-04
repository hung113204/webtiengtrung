<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NguPhap extends Model
{
    use HasFactory;

    protected $table = 'ngu_phap';

    protected $fillable = [
        'id_bai_hoc',
        'tieu_de',
        'cau_truc',
        'giai_thich',
        'vi_du',
    ];

    public function baiHoc()
    {
        return $this->belongsTo(BaiHoc::class, 'id_bai_hoc');
    }
}
