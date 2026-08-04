<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CauHinh extends Model
{
    use HasFactory;

    protected $table = 'cau_hinh';

    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Lấy giá trị cấu hình theo key
     */
    public static function getByKey(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Cập nhật hoặc tạo mới một cấu hình
     */
    public static function setByKey(string $key, $value): void
    {
        self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
}
