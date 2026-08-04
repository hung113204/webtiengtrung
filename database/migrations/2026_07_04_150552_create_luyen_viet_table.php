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
        Schema::create('luyen_viet', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_bai_hoc');
            $table->string('chu_han'); // Chữ Hán cần luyện viết
            $table->text('thu_tu_net')->nullable(); // Mô tả thứ tự nét viết
            $table->string('gif_net_viet')->nullable(); // File ảnh GIF hướng dẫn viết từng nét
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
        Schema::dropIfExists('luyen_viet');
    }
};
