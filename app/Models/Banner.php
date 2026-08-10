<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'badge_text',
        'title_prefix',
        'title_highlight',
        'subtitle',
        'description',
        'button_primary_text',
        'button_primary_link',
        'button_secondary_text',
        'button_secondary_link',
        'is_active',
        'thu_tu',
        'grid_char_1',
        'grid_char_2',
        'grid_char_3',
        'grid_char_4'
    ];
}
