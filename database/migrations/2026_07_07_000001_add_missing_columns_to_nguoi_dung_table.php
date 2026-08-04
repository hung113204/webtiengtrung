<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thêm các cột còn thiếu vào bảng nguoi_dung
     * so sánh từ ảnh cấu trúc DB mục tiêu:
     *   - user_token            : token xác thực tạm thời (API / email verify)
     *   - reset_password_token  : token đặt lại mật khẩu
     *   - reset_password_expires_at : hạn sử dụng của token reset password
     *   - email_verified_at     : thời điểm xác thực email (chuẩn Laravel)
     *   - avatar_url            : URL ảnh đại diện bên ngoài (CDN / Google, v.v.)
     *   - last_login_at         : lần đăng nhập cuối cùng
     *   - is_first_login        : đánh dấu lần đầu đăng nhập (1 = chưa đổi mật khẩu)
     *   - deleted_at            : soft delete
     *   - ghi_chu               : ghi chú nội bộ của admin
     */
    public function up(): void
    {
        Schema::table('nguoi_dung', function (Blueprint $table) {

            // Token tạm cho verify email hoặc API đơn giản
            if (!Schema::hasColumn('nguoi_dung', 'user_token')) {
                $table->string('user_token', 80)->nullable()->after('mat_khau');
            }

            // Token + hạn dùng để đặt lại mật khẩu
            if (!Schema::hasColumn('nguoi_dung', 'reset_password_token')) {
                $table->string('reset_password_token', 100)->nullable()->after('user_token');
            }
            if (!Schema::hasColumn('nguoi_dung', 'reset_password_expires_at')) {
                $table->timestamp('reset_password_expires_at')->nullable()->after('reset_password_token');
            }

            // Cột chuẩn Laravel cho xác thực email (thay thế / bổ sung email_xac_thuc_luc)
            if (!Schema::hasColumn('nguoi_dung', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('email');
            }

            // Lần đăng nhập cuối — hữu ích cho báo cáo & phát hiện tài khoản không hoạt động
            if (!Schema::hasColumn('nguoi_dung', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('trang_thai');
            }

            // Đánh dấu lần đầu đăng nhập: 1 = chưa đổi mật khẩu mặc định
            if (!Schema::hasColumn('nguoi_dung', 'is_first_login')) {
                $table->tinyInteger('is_first_login')->default(1)->after('last_login_at');
            }

            // Soft delete — dùng SoftDeletes trait trong Model
            if (!Schema::hasColumn('nguoi_dung', 'deleted_at')) {
                $table->softDeletes(); // tạo cột deleted_at nullable timestamp
            }

            // Ghi chú nội bộ của admin
            if (!Schema::hasColumn('nguoi_dung', 'ghi_chu')) {
                $table->text('ghi_chu')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nguoi_dung', function (Blueprint $table) {
            $table->dropColumn([
                'user_token',
                'reset_password_token',
                'reset_password_expires_at',
                'email_verified_at',
                'last_login_at',
                'is_first_login',
                'deleted_at',
                'ghi_chu',
            ]);
        });
    }
};
