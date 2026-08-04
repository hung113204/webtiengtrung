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
        Schema::create('dap_an', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_cau_hoi');
            $table->text('noi_dung'); // Nội dung đáp án (A, B, C, D...)
            $table->boolean('dung')->default(false); // Đánh dấu đáp án này là đúng hay sai
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('id_cau_hoi')->references('id')->on('cau_hoi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dap_an');
    }
};
