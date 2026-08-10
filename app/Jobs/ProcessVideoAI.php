<?php

namespace App\Jobs;

use App\Models\Video;
use App\Models\VideoProcessingLog;
use App\Services\AIService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use FFMpeg\Format\Video\X264;

class ProcessVideoAI implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;
    public $timeout = 3600;
    public $backoff = [60];
    protected $video;

    public function __construct(Video $video)
    {
        $this->video = $video;
        $this->onQueue('video-processing');
    }

    public function handle(AIService $aiService)
    {
        $video = $this->video;

        if (!$video->file_path) {
            $video->update(['trang_thai' => 'loi', 'thong_bao_loi' => 'File path trống']);
            return;
        }

        $videoPath = $video->file_path;

        if (!Storage::disk('public')->exists($videoPath)) {
            $video->update(['trang_thai' => 'loi', 'thong_bao_loi' => 'File video không tồn tại']);
            return;
        }

        $fullPath = Storage::disk('public')->path($videoPath);

        $video->update(['trang_thai' => 'dang_xu_ly', 'phan_tram' => 0]);

        try {
            $media = FFMpeg::fromDisk('public')->open($videoPath);
            $stream = $media->getVideoStream();
            if (!$stream) {
                throw new \Exception("Không tìm thấy video stream");
            }

            $dimensions = $stream->getDimensions();
            $height = $dimensions->getHeight();
            $duration = $media->getDurationInSeconds();
            $size = filesize($fullPath);

            $video->update([
                'thoi_luong_giay' => $duration,
                'kich_thuoc' => $size,
            ]);

            // Extract Thumbnail
            try {
                $thumbnailDir = 'uploads/videos/thumbnails';
                Storage::disk('public')->makeDirectory($thumbnailDir);
                $thumbnailPath = $thumbnailDir . '/' . $video->hash_id . '.jpg';
                
                $frameTime = $duration > 10 ? 5 : ($duration / 2);
                
                FFMpeg::fromDisk('public')
                    ->open($videoPath)
                    ->getFrameFromSeconds($frameTime)
                    ->export()
                    ->toDisk('public')
                    ->save($thumbnailPath);
                
                $video->update(['thumbnail_path' => $thumbnailPath]);
            } catch (\Exception $e) {
                Log::warning("Không thể trích xuất thumbnail cho video " . $video->hash_id . ": " . $e->getMessage());
            }

            // HLS transcoding
            $hlsDir = 'uploads/videos/hls/' . $video->hash_id;
            Storage::disk('public')->makeDirectory($hlsDir);
            $hlsPath = $hlsDir . '/' . md5($video->id . time()) . '.m3u8';

            $export = FFMpeg::fromDisk('public')->open($videoPath)->exportForHLS();

            $addFormatWithScale = function($bitrate, $scaleHeight) use ($export, $height) {
                if ($height < $scaleHeight) return;
                $format = (new X264('aac', 'libx264'))->setKiloBitrate($bitrate);
                $format->setAdditionalParameters(['-vf', "scale=-2:{$scaleHeight}"]);
                $export->addFormat($format);
            };

            $addFormatWithScale(800, 360);
            $addFormatWithScale(1200, 480);
            $addFormatWithScale(2000, 720);
            $addFormatWithScale(4000, 1080);

            if ($height < 360) {
                $format = (new X264('aac', 'libx264'))->setKiloBitrate(500);
                $export->addFormat($format);
            }

            $lastUpdate = time();
            $export->onProgress(function ($percentage) use ($video, &$lastUpdate) {
                if (time() - $lastUpdate >= 3) {
                    $video->update(['phan_tram' => $percentage]);
                    $lastUpdate = time();
                }
            })
            ->toDisk('public')
            ->save($hlsPath);

            $video->update(['hls_path' => $hlsPath, 'trang_thai' => 'hoan_thanh', 'phan_tram' => 100]);

            // Dọn dẹp: Xóa file video gốc sau khi convert xong để tiết kiệm dung lượng
            try {
                if (Storage::disk('public')->exists($videoPath)) {
                    Storage::disk('public')->delete($videoPath);
                    // Cập nhật lại file_path là null vì đã xóa
                    $video->update(['file_path' => null]);
                }
            } catch (\Exception $e) {
                Log::warning("Không thể xóa file video gốc sau khi encode HLS " . $video->hash_id . ": " . $e->getMessage());
            }

        } catch (\Exception $e) {
            Log::error("Lỗi xử lý video: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            $video->update([
                'trang_thai' => 'loi',
                'thong_bao_loi' => $e->getMessage()
            ]);
        }
    }

    public function failed(\Throwable $exception)
    {
        Log::error("Job ProcessVideoAI thất bại hoàn toàn: " . $exception->getMessage());
        $this->video->update(['trang_thai' => 'loi', 'thong_bao_loi' => $exception->getMessage()]);
    }
}