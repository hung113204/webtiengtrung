<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DapAn extends Model
{
    protected $table = 'dap_an';

    protected $fillable = [
        'id_cau_hoi',
        'noi_dung',
        'pinyin',
        'dung',
    ];

    public function cauHoi()
    {
        return $this->belongsTo(CauHoi::class, 'id_cau_hoi');
    }
}