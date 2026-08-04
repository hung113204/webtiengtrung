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
        Schema::create('tu_vung', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_bai_hoc');
            $table->string('tu_han'); // Từ chữ Hán
            $table->string('phien_am'); // Pinyin
            $table->string('nghia_tieng_viet');
            $table->string('am_thanh')->nullable(); // File audio đọc từ vựng
            $table->string('hinh_anh')->nullable(); // Hình ảnh minh họa từ vựng
            $table->text('vi_du')->nullable(); // Câu ví dụ (có thể chứa cả chữ Hán, Pinyin và nghĩa)
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
        Schema::dropIfExists('tu_vung');
    }
};
