<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Keyword;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Hapus keywords yang sudah lewat end_date setiap menit tanpa trigger user
        $schedule->call(function () {
            Keyword::whereNotNull('end_date')
                ->whereDate('end_date', '<', Carbon::today())
                ->delete();
        })->name('cleanup-expired-keywords')->everyMinute()->withoutOverlapping();
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
