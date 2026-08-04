<?php

namespace App\Imports;

use App\Models\NguoiDung;
use App\Models\HoSoGiaoVien;
use App\Models\VaiTro;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Str;

class GiaoVienImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    private $importedCount = 0;
    private $duplicateCount = 0;
    private $roleId = null;

    public function __construct()
    {
        // Lấy ID vai trò Giảng viên/Giáo viên
        $role = VaiTro::whereIn('slug', ['giang-vien', 'teacher'])->first();
        if ($role) {
            $this->roleId = $role->id;
        } else {
            $this->roleId = 2; // Default fallback
        }
    }

    public function getImportedCount()
    {
        return $this->importedCount;
    }

    public function getDuplicateCount()
    {
        return $this->duplicateCount;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $hoTen = $row['ho_ten'] ?? null;
            $email = $row['email'] ?? null;
            
            if (empty($hoTen) || empty($email)) {
                continue;
            }

            // Kiểm tra user đã tồn tại theo email chưa
            $user = NguoiDung::where('email', trim($email))->first();

            // Nếu user chưa tồn tại, tạo mới
            if (!$user) {
                // Xử lý ngày sinh để làm mật khẩu mặc định. Excel thường lưu ngày dạng số (Excel date) hoặc chuỗi
                $ngaySinh = null;
                $rawDate = $row['ngay_sinh'] ?? null;
                $password = '12345678'; // Mật khẩu dự phòng
                
                if (!empty($rawDate)) {
                    // Cố gắng parse ngày
                    try {
                        $strDate = (string)$rawDate;
                        // Excel serial date is usually around 30000-50000
                        if (is_numeric($rawDate) && $rawDate > 10000 && $rawDate < 100000) {
                            $unixDate = ($rawDate - 25569) * 86400;
                            $ngaySinh = date('Y-m-d', $unixDate);
                            $password = date('dmy', $unixDate);
                        } else {
                            // Xử lý chuỗi ngày, ví dụ: 15081990 hoặc 02111992 (khi nhập trong excel bị mất số 0 đầu)
                            $cleanDate = preg_replace('/[^0-9]/', '', $strDate);
                            if (strlen($cleanDate) == 8 || strlen($cleanDate) == 7) {
                                $cleanDate = str_pad($cleanDate, 8, '0', STR_PAD_LEFT);
                                $d = substr($cleanDate, 0, 2);
                                $m = substr($cleanDate, 2, 2);
                                $y = substr($cleanDate, 4, 4);
                                $ngaySinh = "$y-$m-$d";
                                $password = $d . $m . substr($y, 2, 2);
                            } else {
                                $parsedDate = strtotime(str_replace('/', '-', $strDate));
                                if ($parsedDate) {
                                    $ngaySinh = date('Y-m-d', $parsedDate);
                                    $password = date('dmy', $parsedDate);
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        // Bỏ qua nếu lỗi parse ngày
                    }
                }

                $user = NguoiDung::create([
                    'ho_ten' => trim($hoTen),
                    'email' => trim($email),
                    'ten_dang_nhap' => strtolower(Str::slug(trim($hoTen)) . rand(100, 999)),
                    'mat_khau' => Hash::make($password),
                    'so_dien_thoai' => $row['so_dien_thoai'] ?? null,
                    'ngay_sinh' => $ngaySinh,
                    'id_vai_tro' => $this->roleId,
                    'trang_thai' => 1,
                ]);
            }

            // Kiểm tra xem đã có hồ sơ chưa
            $hoso = HoSoGiaoVien::where('id_nguoi_dung', $user->id)->first();
            
            if ($hoso) {
                $this->duplicateCount++;
                continue; // Bỏ qua nếu đã có hồ sơ
            }

            // Tạo hồ sơ giáo viên
            HoSoGiaoVien::create([
                'id_nguoi_dung' => $user->id,
                'kinh_nghiem' => $row['so_nam_kinh_nghiem'] ?? 'Chưa cập nhật',
                'chuyen_mon' => $row['chuyen_mon'] ?? null,
                'gioi_thieu' => $row['tieu_su'] ?? null,
            ]);

            $this->importedCount++;
        }
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
