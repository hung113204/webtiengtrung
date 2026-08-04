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
        Schema::create('dang_ky_khoa_hoc', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_nguoi_dung');
            $table->unsignedBigInteger('id_khoa_hoc');
            $table->dateTime('ngay_dang_ky'); // Thời điểm người dùng nhấn đăng ký
            $table->enum('trang_thai', ['Chờ duyệt', 'Đã duyệt', 'Đã hủy'])->default('Chờ duyệt'); // Trạng thái đăng ký
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('id_nguoi_dung')->references('id')->on('nguoi_dung')->onDelete('cascade');
            $table->foreign('id_khoa_hoc')->references('id')->on('khoa_hoc')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dang_ky_khoa_hoc');
    }
};
