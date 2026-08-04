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
        Schema::create('lo_trinh_khoa_hoc', function (Blueprint $table) {
            $table->unsignedBigInteger('id_lo_trinh');
            $table->unsignedBigInteger('id_khoa_hoc');
            $table->integer('thu_tu')->default(0);
            
            $table->primary(['id_lo_trinh', 'id_khoa_hoc']);
            $table->foreign('id_lo_trinh')->references('id')->on('lo_trinh')->onDelete('cascade');
            $table->foreign('id_khoa_hoc')->references('id')->on('khoa_hoc')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lo_trinh_khoa_hoc');
    }
};
