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
        Schema::create('thong_bao', function (Blueprint $table) {
            $table->id();
            $table->string('tieu_de'); // Tiêu đề thông báo
            $table->text('noi_dung'); // Nội dung chi tiết
            $table->unsignedBigInteger('id_nguoi_gui')->nullable(); // Người gửi (có thể null nếu là thông báo tự động từ hệ thống)
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('id_nguoi_gui')->references('id')->on('nguoi_dung')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thong_bao');
    }
};
