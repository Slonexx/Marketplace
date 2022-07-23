<?php

namespace App\Console;

use App\Jobs\ProcessRequests;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Scheduling\Schedule;
use App\Http\Controllers\CheckSettingsController;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Http;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('add:products')->everyTwoMinutes()->withoutOverlapping()->runInBackground();      
        $schedule->command('add:orders')->everyTwoMinutes()->withoutOverlapping()->runInBackground();      
        $schedule->command('update:orders')->everyFourMinutes()->withoutOverlapping()->runInBackground();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
