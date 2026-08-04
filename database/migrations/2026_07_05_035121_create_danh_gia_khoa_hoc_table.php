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
        Schema::create('danh_gia_khoa_hoc', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_nguoi_dung')->comment('Học viên đánh giá');
            $table->unsignedBigInteger('id_khoa_hoc')->comment('Khóa học được đánh giá');
            $table->unsignedTinyInteger('so_sao')->comment('Điểm từ 1 đến 5');
            $table->text('noi_dung')->comment('Nội dung đánh giá');
            $table->boolean('trang_thai')->default(true)->comment('Hiển thị / Ẩn');
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('id_nguoi_dung')
                  ->references('id')
                  ->on('nguoi_dung')
                  ->onDelete('cascade');
                  
            $table->foreign('id_khoa_hoc')
                  ->references('id')
                  ->on('khoa_hoc')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('danh_gia_khoa_hoc');
    }
};
