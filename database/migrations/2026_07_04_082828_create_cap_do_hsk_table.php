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
        Schema::create('cap_do_hsk', function (Blueprint $table) {
            $table->id();
            $table->string('ten_cap_do', 50);
            $table->string('slug')->unique();           
            $table->unsignedSmallInteger('so_tu_vung')->default(0);
            $table->unsignedSmallInteger('so_ngu_phap')->default(0)->nullable();
            $table->text('mo_ta')->nullable();
            $table->unsignedTinyInteger('thu_tu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cap_do_hsk');
    }
};