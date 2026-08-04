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
        Schema::create('video_transcripts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_bai_hoc');
            $table->longText('content');
            $table->string('language')->default('zh');
            $table->integer('version')->default(1);
            $table->timestamps();

            $table->foreign('id_bai_hoc')->references('id')->on('bai_hoc')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_transcripts');
    }
};
