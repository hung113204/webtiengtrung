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
        Schema::create('binh_luan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_nguoi_dung');
            $table->unsignedBigInteger('id_bai_hoc');
            $table->text('noi_dung');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->boolean('trang_thai')->default(true); // Hiển thị/Ẩn
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('id_nguoi_dung')->references('id')->on('nguoi_dung')->onDelete('cascade');
            $table->foreign('id_bai_hoc')->references('id')->on('bai_hoc')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('binh_luan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('binh_luan');
    }
};
