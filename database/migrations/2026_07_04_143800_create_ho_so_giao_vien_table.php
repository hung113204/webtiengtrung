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
        Schema::create('ho_so_giao_vien', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_nguoi_dung');
            $table->string('chuyen_mon')->nullable(); // Chuyên ngành giảng dạy
            $table->string('kinh_nghiem')->nullable(); // Số năm kinh nghiệm hoặc mô tả kinh nghiệm
            $table->string('bang_cap')->nullable(); // Bằng cấp, chứng chỉ cao nhất (VD: HSK 6)
            $table->text('gioi_thieu')->nullable(); // Giới thiệu chi tiết bản thân
            $table->decimal('muc_luong', 15, 2)->nullable(); // Mức lương / Lương cơ bản
            $table->timestamps();

            // Ràng buộc khóa ngoại
            $table->foreign('id_nguoi_dung')->references('id')->on('nguoi_dung')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ho_so_giao_vien');
    }
};
