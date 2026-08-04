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
        Schema::create('danh_gia', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_nguoi_dung');
            $table->unsignedBigInteger('id_khoa_hoc');
            $table->tinyInteger('so_sao')->default(5);
            $table->string('tieu_de', 255)->nullable();
            $table->text('noi_dung');
            $table->text('uu_diem')->nullable();
            $table->text('nhuoc_diem')->nullable();
            $table->boolean('trang_thai')->default(true);
            $table->timestamps();
            
            // Foreign keys if necessary
            // $table->foreign('id_nguoi_dung')->references('id')->on('nguoi_dung')->onDelete('cascade');
            // $table->foreign('id_khoa_hoc')->references('id')->on('khoa_hoc')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('danh_gia');
    }
};
