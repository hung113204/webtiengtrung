<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('nguoi_dung', function (Blueprint $table) {
            $table->integer('streak_hien_tai')->default(0)->comment('Số ngày học liên tiếp hiện tại');
            $table->integer('streak_cao_nhat')->default(0)->comment('Kỷ lục chuỗi ngày học dài nhất');
            $table->date('ngay_hoat_dong_cuoi')->nullable()->comment('Ngày gần nhất có hoạt động');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nguoi_dung', function (Blueprint $table) {
            $table->dropColumn(['streak_hien_tai', 'streak_cao_nhat', 'ngay_hoat_dong_cuoi']);
        });
    }
};
