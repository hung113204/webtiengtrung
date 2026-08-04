<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Quản lý người dùng, vai trò, cài đặt, hóa đơn (Admin only)
            ['ten_quyen' => 'Quản lý Người dùng', 'slug' => 'manage_users', 'nhom_quyen' => 'Hệ thống'],
            ['ten_quyen' => 'Quản lý Vai trò', 'slug' => 'manage_roles', 'nhom_quyen' => 'Hệ thống'],
            ['ten_quyen' => 'Quản lý Hóa đơn', 'slug' => 'manage_invoices', 'nhom_quyen' => 'Tài chính'],
            ['ten_quyen' => 'Cấu hình Hệ thống', 'slug' => 'manage_settings', 'nhom_quyen' => 'Hệ thống'],
            
            // Quản lý khóa học, bài học (Admin & Teacher)
            ['ten_quyen' => 'Quản lý Khóa học', 'slug' => 'manage_courses', 'nhom_quyen' => 'Khóa học'],
            ['ten_quyen' => 'Quản lý Bài học', 'slug' => 'manage_lessons', 'nhom_quyen' => 'Khóa học'],
            ['ten_quyen' => 'Quản lý Đề thi', 'slug' => 'manage_exams', 'nhom_quyen' => 'Kiểm tra'],
            ['ten_quyen' => 'Quản lý Câu hỏi', 'slug' => 'manage_questions', 'nhom_quyen' => 'Kiểm tra'],
            ['ten_quyen' => 'Chấm điểm Luyện viết', 'slug' => 'manage_writing', 'nhom_quyen' => 'Kiểm tra'],
        ];

        foreach ($permissions as $perm) {
            \App\Models\Quyen::firstOrCreate(['slug' => $perm['slug']], $perm);
        }

        // Gán quyền cho Giáo viên (id_vai_tro = 2 thường là Giảng Viên)
        $teacherRole = \App\Models\VaiTro::where('slug', 'giang-vien')->orWhere('level', 2)->first();
        if ($teacherRole) {
            $teacherPerms = \App\Models\Quyen::whereIn('slug', [
                'manage_courses', 'manage_lessons', 'manage_exams', 'manage_questions', 'manage_writing'
            ])->pluck('id')->toArray();
            
            $teacherRole->quyens()->syncWithoutDetaching($teacherPerms);
        }
    }
}
