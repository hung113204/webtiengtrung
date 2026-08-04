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
        Schema::create('tien_do_hoc', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_nguoi_dung');
            $table->unsignedBigInteger('id_bai_hoc');
            $table->decimal('phan_tram_hoan_thanh', 5, 2)->default(0); // Từ 0.00 đến 100.00
            $table->boolean('da_hoan_thanh')->default(false); // True khi phan_tram_hoan_thanh đạt 100
            $table->dateTime('lan_hoc_cuoi')->nullable(); // Thời điểm truy cập bài học lần cuối
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('id_nguoi_dung')->references('id')->on('nguoi_dung')->onDelete('cascade');
            $table->foreign('id_bai_hoc')->references('id')->on('bai_hoc')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tien_do_hoc');
    }
};
