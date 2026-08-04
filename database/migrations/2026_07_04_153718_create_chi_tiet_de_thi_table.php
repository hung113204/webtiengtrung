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
        Schema::create('chi_tiet_de_thi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_de_thi');
            $table->unsignedBigInteger('id_cau_hoi');
            $table->integer('thu_tu')->default(0); // Thứ tự của câu hỏi trong đề
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('id_de_thi')->references('id')->on('de_thi')->onDelete('cascade');
            $table->foreign('id_cau_hoi')->references('id')->on('cau_hoi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chi_tiet_de_thi');
    }
};
