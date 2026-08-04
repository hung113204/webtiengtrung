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
        Schema::create('bai_hoc', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_chuong');
            $table->unsignedBigInteger('id_cap_do_hsk')->nullable(); // Thêm để truy vấn nhanh
            $table->string('ten_bai_hoc');
            $table->string('slug')->unique();
            $table->text('mo_ta_ngan')->nullable(); // Mô tả ngắn
            $table->longText('noi_dung')->nullable(); // Lý thuyết, có thể là HTML
            $table->string('video')->nullable();
            $table->string('audio')->nullable(); // File âm thanh
            $table->string('tai_lieu')->nullable(); // File đính kèm
            $table->integer('thoi_luong_giay')->default(0); // Đổi tên rõ ràng
            $table->integer('thu_tu')->default(0);
            $table->boolean('mien_phi')->default(false);
            $table->enum('trang_thai', ['draft', 'published', 'archived'])->default('draft');
            $table->integer('luot_xem')->default(0);
            $table->json('metadata')->nullable(); // Dữ liệu mở rộng
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('id_chuong')->references('id')->on('chuong_hoc')->onDelete('cascade');
            $table->foreign('id_cap_do_hsk')->references('id')->on('cap_do_hsk')->onDelete('set null');

            // Indexes
            $table->index(['id_chuong', 'thu_tu']);
            $table->index('id_cap_do_hsk');
            $table->index('trang_thai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bai_hoc');
    }
};
