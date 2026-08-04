<?php

namespace App\Imports;

use App\Models\CauHoi;
use App\Models\DapAn;
use App\Models\BaiHoc;
use App\Models\LoaiCauHoi;
use App\Models\MucDo;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class CauHoiImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    private $baiHocId;
    private $loaiCauHoiId;
    private $mucDoId;
    
    private $importedCount = 0;
    private $errorCount = 0;

    public function __construct($baiHocId = null, $loaiCauHoiId = null, $mucDoId = null)
    {
        $this->baiHocId = $baiHocId;
        $this->loaiCauHoiId = $loaiCauHoiId;
        $this->mucDoId = $mucDoId;
    }

    public function getImportedCount()
    {
        return $this->importedCount;
    }

    public function getErrorCount()
    {
        return $this->errorCount;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Kiểm tra các trường bắt buộc (ít nhất phải có nội dung câu hỏi)
            if (empty($row['noi_dung'])) {
                $this->errorCount++;
                continue;
            }

            $currentBaiHocId = $this->baiHocId;
            $currentLoaiCauHoiId = $this->loaiCauHoiId;
            $currentMucDoId = $this->mucDoId;

            // Xử lý Bài học từ file nếu form không chọn
            if (!$currentBaiHocId && !empty($row['bai_hoc'])) {
                $baiHoc = BaiHoc::where('ten_bai_hoc', trim($row['bai_hoc']))->first();
                if ($baiHoc) {
                    $currentBaiHocId = $baiHoc->id;
                }
            }

            // Xử lý Loại câu hỏi từ file nếu form không chọn
            if (!$currentLoaiCauHoiId && !empty($row['loai_cau_hoi'])) {
                $loai = LoaiCauHoi::where('ten_loai', trim($row['loai_cau_hoi']))->first();
                if ($loai) {
                    $currentLoaiCauHoiId = $loai->id;
                }
            }

            // Xử lý Mức độ từ file nếu form không chọn
            if (!$currentMucDoId && !empty($row['muc_do'])) {
                $mucDo = MucDo::where('ten_muc_do', trim($row['muc_do']))->first();
                if ($mucDo) {
                    $currentMucDoId = $mucDo->id;
                }
            }

            // Nếu vẫn thiếu thông tin bắt buộc thì bỏ qua
            if (!$currentBaiHocId || !$currentLoaiCauHoiId || !$currentMucDoId) {
                $this->errorCount++;
                continue;
            }

            // Tạo Câu hỏi
            $cauHoi = CauHoi::create([
                'id_bai_hoc' => $currentBaiHocId,
                'id_loai_cau_hoi' => $currentLoaiCauHoiId,
                'id_muc_do' => $currentMucDoId,
                'noi_dung' => $row['noi_dung'],
                'pinyin' => $row['pinyin'] ?? null,
                'dich_nghia' => $row['dich_nghia'] ?? null,
                'giai_thich' => $row['giai_thich'] ?? null,
            ]);

            // Tạo Đáp án
            $dapAnDungLetter = strtoupper(trim($row['dap_an_dung'] ?? ''));

            // Nếu không điền đáp án đúng, nhưng chỉ có mỗi dap_an_a (như câu Sắp xếp, Điền khuyết)
            if (empty($dapAnDungLetter) && !empty($row['dap_an_a']) && empty($row['dap_an_b']) && empty($row['dap_an_c']) && empty($row['dap_an_d'])) {
                $dapAnDungLetter = 'A';
            }

            $dapAnFields = [
                'A' => ['noi_dung' => 'dap_an_a', 'pinyin' => 'pinyin_a'],
                'B' => ['noi_dung' => 'dap_an_b', 'pinyin' => 'pinyin_b'],
                'C' => ['noi_dung' => 'dap_an_c', 'pinyin' => 'pinyin_c'],
                'D' => ['noi_dung' => 'dap_an_d', 'pinyin' => 'pinyin_d']
            ];

            foreach ($dapAnFields as $letter => $fields) {
                if (!empty($row[$fields['noi_dung']])) {
                    DapAn::create([
                        'id_cau_hoi' => $cauHoi->id,
                        'noi_dung' => $row[$fields['noi_dung']],
                        'pinyin' => $row[$fields['pinyin']] ?? null,
                        'dung' => ($letter === $dapAnDungLetter) ? 1 : 0

                    ]);
                }
            }

            $this->importedCount++;
        }
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
