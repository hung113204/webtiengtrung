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
        Schema::create('khoa_hoc_loi_ich', function (Blueprint $table) {
            $table->id();

            $table->foreignId('khoa_hoc_id')
                  ->constrained('khoa_hoc')
                  ->cascadeOnDelete();

            $table->string('noi_dung');

            $table->integer('thu_tu')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('khoa_hoc_loi_ich');
    }
};
