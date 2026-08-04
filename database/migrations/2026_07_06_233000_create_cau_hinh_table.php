<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cau_hinh', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Insert initial system settings
        DB::table('cau_hinh')->insert([
            ['key' => 'website_name', 'value' => 'Hányǔ Platform - Tiếng Trung Online', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'contact_email', 'value' => 'contact@hanyu.edu.vn', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'meta_description', 'value' => 'Nền tảng học tiếng Trung trực tuyến, luyện thi HSK chuẩn quốc tế.', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'enable_payment', 'value' => '1', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'vnpay_tmncode', 'value' => 'HANYU998', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'vnpay_hashsecret', 'value' => '************************', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'vnpay_environment', 'value' => 'production', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'smtp_host', 'value' => 'smtp.gmail.com', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'smtp_port', 'value' => '587', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'smtp_username', 'value' => 'noreply@hanyu.edu.vn', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'smtp_password', 'value' => '********', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'recaptcha_site_key', 'value' => '', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'recaptcha_secret_key', 'value' => '', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'require_email_verification', 'value' => '1', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cau_hinh');
    }
};
