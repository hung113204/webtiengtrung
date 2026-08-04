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
        Schema::create('chi_tiet_hoi_thoai', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_hoi_thoai');
            $table->string('nhan_vat')->nullable();
            $table->text('noi_dung_tieng_trung');
            $table->string('pinyin')->nullable();
            $table->text('nghia_tieng_viet')->nullable();
            $table->string('am_thanh')->nullable();
            $table->integer('thu_tu')->default(0);
            $table->timestamps();

            $table->foreign('id_hoi_thoai')->references('id')->on('hoi_thoai')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chi_tiet_hoi_thoai');
    }
};
