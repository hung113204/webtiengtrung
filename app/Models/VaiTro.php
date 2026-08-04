<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VaiTro extends Model
{
    use HasFactory;

    protected $table = 'vai_tro';

    protected $fillable = [
        'ten_vai_tro',
        'slug',
        'mo_ta',
        'is_default',
        'level'
    ];

    /**
     * Mối quan hệ nhiều - nhiều với model NguoiDung
     */
    public function nguoiDungs()
    {
        return $this->belongsToMany(
            NguoiDung::class, 
            'nguoi_dung_vai_tro', 
            'id_vai_tro', 
            'id_nguoi_dung'
        )->withTimestamps();
    }

    /**
     * Mối quan hệ nhiều - nhiều với model Quyen
     */
    public function quyens()
    {
        return $this->belongsToMany(Quyen::class, 'vai_tro_quyen', 'id_vai_tro', 'id_quyen');
    }
}
