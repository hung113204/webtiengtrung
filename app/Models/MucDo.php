<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MucDo extends Model
{
    protected $fillable = [
        'ten_muc_do',
        'slug',
        'thu_tu'
    ];

    public function cauHois()
    {
        return $this->hasMany(CauHoi::class, 'id_muc_do');
    }
}