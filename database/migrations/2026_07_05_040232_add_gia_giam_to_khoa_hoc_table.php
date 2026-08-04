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
        Schema::table('khoa_hoc', function (Blueprint $table) {
            $table->decimal('gia_giam', 10, 2)->nullable()->after('gia')->comment('Giá khuyến mãi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('khoa_hoc', function (Blueprint $table) {
            $table->dropColumn('gia_giam');
        });
    }
};
