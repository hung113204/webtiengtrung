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
        Schema::create('phan_cong_giang_day', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_giao_vien');
            $table->unsignedBigInteger('id_khoa_hoc');
            $table->string('vai_tro_giang_day')->nullable()->default('Giảng viên chính');
            $table->timestamp('ngay_phan_cong')->useCurrent();
            $table->timestamps();

            // Foreign keys
            $table->foreign('id_giao_vien')->references('id')->on('ho_so_giao_vien')->onDelete('cascade');
            $table->foreign('id_khoa_hoc')->references('id')->on('khoa_hoc')->onDelete('cascade');
            
            // Unique index to prevent duplicate assignments
            $table->unique(['id_giao_vien', 'id_khoa_hoc']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phan_cong_giang_day');
    }
};
