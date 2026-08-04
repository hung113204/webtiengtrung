<?php

namespace App\Imports;

use App\Models\TuVung;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Illuminate\Support\Facades\Storage;

class TuVungImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    private $baiHocId;
    private $importedCount = 0;
    private $duplicateCount = 0;

    public function __construct($baiHocId = null)
    {
        $this->baiHocId = $baiHocId;
    }

    public function getImportedCount()
    {
        return $this->importedCount;
    }

    public function getDuplicateCount()
    {
        return $this->duplicateCount;
    }

    public function model(array $row)
    {
        // Skip empty rows
        if (empty($row['chu_han']) || empty($row['pinyin']) || empty($row['nghia_tieng_viet'])) {
            return null;
        }

        $baiHocId = $this->baiHocId;

        // Ưu tiên đọc tên bài học từ cột 'bai_hoc' trong Excel
        if (!empty($row['bai_hoc'])) {
            $baiHoc = \App\Models\BaiHoc::where('ten_bai_hoc', trim($row['bai_hoc']))->first();
            if ($baiHoc) {
                $baiHocId = $baiHoc->id;
            }
        }

        if (!$baiHocId) {
            return null; // Bỏ qua nếu không xác định được bài học
        }

        // Kiểm tra từ vựng đã tồn tại chưa
        $exists = TuVung::where('tu_han', $row['chu_han'])
                        ->where('id_bai_hoc', $baiHocId)
                        ->exists();

        if ($exists) {
            $this->duplicateCount++;
            return null; // Bỏ qua từ trùng
        }

        $amThanh = null;
        // Auto generate TTS
        $text = urlencode($row['chu_han']);
        $url = "https://translate.google.com/translate_tts?ie=UTF-8&tl=zh-CN&client=tw-ob&q={$text}";
        try {
            $audioContent = file_get_contents($url);
            if ($audioContent) {
                $fileName = 'uploads/tuvung/audio/tts_' . time() . '_' . rand(1000, 9999) . '.mp3';
                Storage::disk('public')->put($fileName, $audioContent);
                $amThanh = $fileName;
            }
        } catch (\Exception $e) {
            // Ignore error
        }

        $this->importedCount++;

        return new TuVung([
            'id_bai_hoc' => $baiHocId,
            'tu_han' => $row['chu_han'],
            'phien_am' => $row['pinyin'],
            'nghia_tieng_viet' => $row['nghia_tieng_viet'],
            'vi_du' => $row['cau_vi_du'] ?? null,
            'am_thanh' => $amThanh,
        ]);
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function batchSize(): int
    {
        return 100;
    }
}
