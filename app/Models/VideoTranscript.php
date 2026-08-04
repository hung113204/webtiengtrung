<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoTranscript extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_bai_hoc',
        'content',
        'language',
        'version',
    ];

    public function baiHoc()
    {
        return $this->belongsTo(BaiHoc::class, 'id_bai_hoc');
    }
}
