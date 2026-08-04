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
        Schema::create('quyen', function (Blueprint $table) {
            $table->id();
            $table->string('ten_quyen');
            $table->string('slug')->unique();
            $table->string('nhom_quyen')->nullable();
            $table->timestamps();
        });

        Schema::create('vai_tro_quyen', function (Blueprint $table) {
            $table->unsignedBigInteger('id_vai_tro');
            $table->unsignedBigInteger('id_quyen');
            
            $table->foreign('id_vai_tro')->references('id')->on('vai_tro')->onDelete('cascade');
            $table->foreign('id_quyen')->references('id')->on('quyen')->onDelete('cascade');
            
            $table->primary(['id_vai_tro', 'id_quyen']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vai_tro_quyen');
        Schema::dropIfExists('quyen');
    }
};
