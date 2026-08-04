<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nguoi_dung', function (Blueprint $table) {
            if (Schema::hasColumn('nguoi_dung', 'avatar_url')) {
                $table->dropColumn('avatar_url');
            }
        });
    }

    public function down(): void
    {
        Schema::table('nguoi_dung', function (Blueprint $table) {
            if (!Schema::hasColumn('nguoi_dung', 'avatar_url')) {
                $table->string('avatar_url', 255)->nullable()->after('anh_dai_dien');
            }
        });
    }
};
