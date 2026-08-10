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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('badge_text')->nullable();
            $table->string('title_prefix')->nullable();
            $table->string('title_highlight')->nullable();
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->string('button_primary_text')->nullable();
            $table->string('button_primary_link')->nullable();
            $table->string('button_secondary_text')->nullable();
            $table->string('button_secondary_link')->nullable();
            $table->boolean('is_active')->default(false);
            $table->integer('thu_tu')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
