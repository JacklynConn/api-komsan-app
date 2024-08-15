<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Slider;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            try {
                $now = Carbon::now();
                $updatedRows = Slider::where('end_date', '<', $now)
                    ->update(['active_status' => 0]);

                // Log the number of updated rows
                Log::info("Scheduled task ran successfully. Updated $updatedRows slider(s).");
            } catch (\Exception $e) {
                // Log the exception message
                Log::error("Error running scheduled task: " . $e->getMessage());
            }
        })->everyTwoMinutes();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
