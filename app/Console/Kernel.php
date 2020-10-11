<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Artisan;

class Kernel extends ConsoleKernel
{
    /** * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('real-time:fetch:entries')
                 ->everyMinute()
                 ->withoutOverlapping();

        $schedule->command('real-time:process:entries')
                 ->hourly()
                 ->after(function () {
                     Artisan::call('time-series:count:routes');
                     Artisan::call('time-series:count:vehicles');
                     Artisan::call('time-series:count:route-vehicles');
                 });

        $schedule->command('real-time:fetch:routes')
                 ->daily();
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
