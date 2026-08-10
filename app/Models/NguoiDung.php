<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

class NguoiDung extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * Tên bảng trong cơ sở dữ liệu.
     *
     * @var string
     */
    protected $table = 'nguoi_dung';

    /**
     * Các trường có thể gán dữ liệu hàng loạt (Mass Assignment).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ho_ten',
        'ten_dang_nhap',
        'email',
        'mat_khau',
        'anh_dai_dien',
        'ngay_sinh',
        'gioi_tinh',
        'so_dien_thoai',
        'id_vai_tro',
        'trang_thai',
        'is_first_login',
        'last_login_at',
        'email_verified_at',
        'user_token',
        'google_id',
        'ghi_chu',
        'streak_hien_tai',
        'streak_cao_nhat',
        'ngay_hoat_dong_cuoi',
        'diem_xp',
        'dong_bang_chuoi',
    ];

    /**
     * Các trường cần ẩn khi trả về JSON.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'mat_khau',
        'remember_token',
        'user_token',
        'reset_password_token',
    ];

    /**
     * Ép kiểu dữ liệu cho các trường.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_xac_thuc_luc'          => 'datetime',
            'email_verified_at'            => 'datetime',
            'last_login_at'                => 'datetime',
            'reset_password_expires_at'    => 'datetime',
            'mat_khau'                     => 'hashed',
            'trang_thai'                   => 'boolean',
            'is_first_login'               => 'boolean',
            'ngay_sinh'                    => 'date',
            'ngay_hoat_dong_cuoi'          => 'date',
        ];
    }

    /**
     * Ghi đè trường mật khẩu mặc định của Laravel Auth (từ 'password' sang 'mat_khau').
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->mat_khau;
    }

    /**
     * Ghi đè tên cột mật khẩu mặc định của Laravel Auth.
     *
     * @return string
     */
    public function getAuthPasswordName()
    {
        return 'mat_khau';
    }

    /**
     * Kiểm tra người dùng có phải là Admin hay không (Quyền lực >= 90).
     * 
     * @return bool
     */
    public function isAdmin()
    {
        return $this->vaiTro && ($this->vaiTro->level >= 90 || $this->vaiTro->slug === 'admin' || $this->vaiTro->level === 1);
    }

    /**
     * Kiểm tra người dùng có quyền Giảng viên trở lên (Quyền lực >= 50 hoặc level <= 2).
     * Bao gồm cả Admin.
     * 
     * @return bool
     */
    public function isTeacher()
    {
        return $this->vaiTro && ($this->vaiTro->level >= 50 || in_array($this->vaiTro->slug, ['admin', 'giang-vien', 'teacher']) || ($this->vaiTro->level <= 2 && $this->vaiTro->level > 0));
    }

    /**
     * Kiểm tra xem người dùng có quyền cụ thể nào đó thông qua Vai trò (RBAC) không.
     * Admin (id_vai_tro = 1) mặc định có tất cả các quyền.
     * 
     * @param string $permissionSlug
     * @return bool
     */
    public function hasPermission($permissionSlug)
    {
        if ($this->isAdmin()) {
            return true;
        }

        if (!$this->vaiTro) {
            return false;
        }

        // Lấy danh sách slug các quyền của vai trò hiện tại
        return $this->vaiTro->quyens()->where('slug', $permissionSlug)->exists();
    }

    /**
     * Mối quan hệ: Một người dùng có một vai trò chính xác định.
     */
    public function vaiTro()
    {
        return $this->belongsTo(VaiTro::class, 'id_vai_tro');
    }

    /**
     * Mối quan hệ: Một người dùng có thể có một hồ sơ giáo viên.
     */
    public function hoSoGiaoVien()
    {
        return $this->hasOne(HoSoGiaoVien::class, 'id_nguoi_dung');
    }

    /**
     * Mối quan hệ nhiều - nhiều: Một người dùng có thể có nhiều vai trò bổ sung (nếu dùng hệ thống phân quyền nâng cao).
     */
    public function vaiTros()
    {
        return $this->belongsToMany(
            VaiTro::class, 
            'nguoi_dung_vai_tro', 
            'id_nguoi_dung', 
            'id_vai_tro'
        )->withTimestamps();
    }

    /**
     * Mối quan hệ: Một người dùng có nhiều tiến độ video.
     */
    public function tienDoVideos()
    {
        return $this->hasMany(TienDoVideo::class, 'id_nguoi_dung', 'id');
    }

    /**
     * Mối quan hệ: Một người dùng có nhiều tiến độ học tập.
     */
    public function tienDoHocs()
    {
        return $this->hasMany(TienDoHoc::class, 'id_nguoi_dung', 'id');
    }

    /**
     * Mối quan hệ: Một người dùng đăng ký nhiều khóa học.
     */
    public function dangKyKhoaHocs()
    {
        return $this->hasMany(DangKyKhoaHoc::class, 'id_nguoi_dung', 'id');
    }

    /**
     * Mối quan hệ: Một người dùng có nhiều bình luận.
     */
    public function binhLuans()
    {
        return $this->hasMany(BinhLuan::class, 'id_nguoi_dung', 'id');
    }

    /**
     * Mối quan hệ: Một người dùng có một hồ sơ học viên.
     */
    public function hoSoHocVien()
    {
        return $this->hasOne(HoSoHocVien::class, 'id_nguoi_dung');
    }

    /**
     * Mối quan hệ: Một người dùng có nhiều tiến độ từ vựng.
     */
    public function tienDoTuVungs()
    {
        return $this->hasMany(TienDoTuVung::class, 'id_nguoi_dung', 'id');
    }

    /**
     * Mối quan hệ: Một người dùng có thể yêu thích nhiều khóa học.
     */
    public function khoaHocYeuThichs()
    {
        return $this->belongsToMany(KhoaHoc::class, 'yeu_thich_khoa_hocs', 'id_nguoi_dung', 'id_khoa_hoc')->withTimestamps();
    }

    /**
     * Cập nhật số ngày học liên tiếp (Streak)
     *
     * @return bool
     */
    public function capNhatStreak()
    {
        $hom_nay = Carbon::today();
        $ngay_cuoi = $this->ngay_hoat_dong_cuoi 
            ? Carbon::parse($this->ngay_hoat_dong_cuoi)->startOfDay() 
            : null;

        if (!$ngay_cuoi) {
            $this->streak_hien_tai = 1;
            $this->streak_cao_nhat = 1;
            $this->ngay_hoat_dong_cuoi = $hom_nay;
        } elseif ($ngay_cuoi->equalTo($hom_nay)) {
            return false; 
        } elseif ($ngay_cuoi->equalTo($hom_nay->copy()->subDay())) {
            $this->streak_hien_tai += 1;
            if ($this->streak_hien_tai > $this->streak_cao_nhat) {
                $this->streak_cao_nhat = $this->streak_hien_tai;
            }
            $this->ngay_hoat_dong_cuoi = $hom_nay;
        } else {
            // Bỏ lỡ ít nhất 1 ngày
            $so_ngay_bo_lo = $hom_nay->diffInDays($ngay_cuoi) - 1;
            
            // Kiểm tra đóng băng chuỗi (Streak Freeze)
            if ($this->dong_bang_chuoi >= $so_ngay_bo_lo) {
                $this->dong_bang_chuoi -= $so_ngay_bo_lo; // Trừ số lượt đóng băng tương ứng
                $this->streak_hien_tai += 1; // Vẫn cộng 1 vì hôm nay đã hoạt động lại
                
                if ($this->streak_hien_tai > $this->streak_cao_nhat) {
                    $this->streak_cao_nhat = $this->streak_hien_tai;
                }
            } else {
                // Không đủ số lượt đóng băng -> Reset chuỗi
                $this->streak_hien_tai = 1;
            }
            
            $this->ngay_hoat_dong_cuoi = $hom_nay;
        }

        $this->save();
        return true;
    }

    /**
     * Lấy streak thực tế (tự reset về 0 nếu hôm qua không học)
     * Dùng ở frontend: {{ auth()->user()->streak_thuc_te }}
     *
     * @return int
     */
    public function getStreakThucTeAttribute()
    {
        if (!$this->ngay_hoat_dong_cuoi) {
            return 0;
        }
        
        $ngay_cuoi = Carbon::parse($this->ngay_hoat_dong_cuoi)->startOfDay();
        $hom_nay = Carbon::today();
        
        if ($ngay_cuoi->lt($hom_nay->copy()->subDay())) {
            $so_ngay_bo_lo = $hom_nay->diffInDays($ngay_cuoi) - 1;
            if ($this->dong_bang_chuoi >= $so_ngay_bo_lo) {
                return $this->streak_hien_tai;
            }
            return 0;
        }
        
        return $this->streak_hien_tai;
    }

    /**
     * Tăng điểm XP cho người dùng
     *
     * @param int $soDiem
     * @return bool
     */
    public function tangXP($soDiem)
    {
        if ($soDiem > 0) {
            $this->diem_xp += $soDiem;
            return $this->save();
        }
        return false;
    }

    /**
     * Mối quan hệ: Một người dùng có nhiều thông báo thông qua bảng trung gian.
     */
    public function thongBaos()
    {
        return $this->belongsToMany(ThongBao::class, 'thong_bao_nguoi_dung', 'id_nguoi_dung', 'id_thong_bao')
                    ->withPivot('da_doc')
                    ->withTimestamps();
    }
}
