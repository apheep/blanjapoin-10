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

        // Reset stock harian untuk keyword dengan daily stock reset
        $schedule->call(function () {
            $keywords = Keyword::where('is_daily_stock', true)
                ->where('is_active', 1)
                ->where('status', 'approve')
                ->whereNotNull('daily_stock_limit')
                ->where('daily_stock_limit', '>', 0)
                ->get();

            $resetCount = 0;
            foreach ($keywords as $keyword) {
                $keyword->stock = $keyword->daily_stock_limit;
                $keyword->last_stock_reset = Carbon::now();
                $keyword->save();
                $resetCount++;
            }

            // Log hasil reset (optional, bisa dihapus jika tidak perlu)
            if ($resetCount > 0) {
                \Log::info("Daily stock reset completed. {$resetCount} keywords reset.");
            }
        })->name('reset-daily-stock')->dailyAt('00:00')->withoutOverlapping();
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
