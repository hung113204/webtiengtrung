<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
         $schedule->command('video:process-pending --limit=5')->everyMinute();
         
         // Tự động reset streak những người không học hôm qua
         $schedule->call(function () {
            \App\Models\NguoiDung::whereDate('ngay_hoat_dong_cuoi', '<', \Carbon\Carbon::yesterday())
                ->where('streak_hien_tai', '>', 0)
                ->update(['streak_hien_tai' => 0]);
        })->dailyAt('00:01');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
