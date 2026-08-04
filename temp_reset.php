<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\NguoiDung;
use Illuminate\Support\Facades\Hash;

$u = NguoiDung::where('email', 'nguyenminh123@gmail.com')->first();
if ($u) {
    $u->mat_khau = Hash::make('admin123');
    $u->save();
    echo "Password updated successfully for nguyenminh123@gmail.com\n";
} else {
    echo "User not found\n";
}
