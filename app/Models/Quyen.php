<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quyen extends Model
{
    use HasFactory;

    protected $table = 'quyen';

    protected $fillable = [
        'ten_quyen',
        'slug',
        'nhom_quyen',
    ];

    public function vaiTros()
    {
        return $this->belongsToMany(VaiTro::class, 'vai_tro_quyen', 'id_quyen', 'id_vai_tro');
    }
}
