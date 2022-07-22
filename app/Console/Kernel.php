<?php

namespace App\Console;

use App\Http\Controllers\CheckSettingsController;
use App\Jobs\ProcessRequests;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

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
        $havAllRequiredSettings = app(CheckSettingsController::class)->haveSettings();

        if($havAllRequiredSettings == true){
            $process = new ProcessRequests([]);
            $schedule->job($process,'orders', 'database')->everyTwoMinutes()
            ->onSuccess(function(){
                Log::info('Proccess work success');
            })->onFailure(function(){
                Log::info('Proccess work failure');
            });
        }
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
