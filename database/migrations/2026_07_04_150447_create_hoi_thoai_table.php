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
        Schema::create('hoi_thoai', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_bai_hoc');
            $table->string('tieu_de')->nullable(); // Tiêu đề đoạn hội thoại
            $table->text('noi_dung'); // Nội dung hội thoại (có thể chứa HTML để hiển thị cả tiếng Trung, Pinyin, tiếng Việt)
            $table->string('am_thanh')->nullable(); // File audio của đoạn hội thoại
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('id_bai_hoc')->references('id')->on('bai_hoc')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hoi_thoai');
    }
};
