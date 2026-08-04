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
        Schema::create('tien_do_tu_vung', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_nguoi_dung');
            $table->unsignedBigInteger('id_tu_vung');
            $table->tinyInteger('trang_thai')->default(0)->comment('0: new, 1: learning, 2: learned');
            $table->integer('interval')->default(0)->comment('Khoảng cách ôn tập theo SM-2');
            $table->float('ease_factor')->default(2.5)->comment('Hệ số dễ SM-2');
            $table->timestamp('next_review_at')->nullable()->comment('Lần ôn tập tiếp theo');
            $table->text('ghi_chu')->nullable()->comment('Ghi chú cá nhân');
            $table->boolean('da_luu')->default(false)->comment('Bookmark / Đánh dấu');
            $table->timestamps();

            // Foreign keys
            $table->foreign('id_nguoi_dung')->references('id')->on('nguoi_dung')->onDelete('cascade');
            $table->foreign('id_tu_vung')->references('id')->on('tu_vung')->onDelete('cascade');
            
            // Unique index để mỗi người dùng chỉ có 1 tiến độ cho 1 từ vựng
            $table->unique(['id_nguoi_dung', 'id_tu_vung']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tien_do_tu_vung');
    }
};
