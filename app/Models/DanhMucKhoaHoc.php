<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DanhMucKhoaHoc extends Model
{
    use HasFactory;

    protected $table = 'danh_muc_khoa_hoc';

    protected $fillable = [
        'parent_id',
        'ten_danh_muc',
        'slug',
        'mo_ta',
        'thu_tu',
        'trang_thai',
    ];

    // ===================== RELATIONS =====================

    /**
     * Danh mục cha (parent)
     */
    public function parent()
    {
        return $this->belongsTo(DanhMucKhoaHoc::class, 'parent_id');
    }

    /**
     * Các danh mục con (children)
     */
    public function children()
    {
        return $this->hasMany(DanhMucKhoaHoc::class, 'parent_id')
                    ->orderBy('thu_tu');
    }

    /**
     * Một danh mục có thể có nhiều khóa học
     */
    public function khoaHocs()
    {
        return $this->hasMany(KhoaHoc::class, 'id_danh_muc_khoa_hoc');
    }

    // ===================== SCOPES =====================

    /**
     * Chỉ lấy danh mục gốc (không có cha)
     */
    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Chỉ lấy danh mục đang hiển thị
     */
    public function scopeActive($query)
    {
        return $query->where('trang_thai', 1);
    }

    // ===================== HELPERS =====================

    /**
     * Kiểm tra có phải danh mục gốc không
     */
    public function isRoot(): bool
    {
        return is_null($this->parent_id);
    }

    /**
     * Kiểm tra có danh mục con không
     */
    public function hasChildren(): bool
    {
        return $this->children->isNotEmpty();
    }
}
