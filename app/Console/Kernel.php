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
                 ->withoutOverlapping(5);

        $schedule->command('real-time:fetch:routes')
                 ->daily();

        $schedule->command('gtfs:fetch')
                 ->daily()
                 ->onSuccess(function () {
                     Artisan::call('gtfs:process:routes');
                     Artisan::call('gtfs:process:calendar-dates');
                     Artisan::call('gtfs:process:trips');
                     Artisan::call('gtfs:process:stops');
                     Artisan::call('gtfs:process:stop-times');
                 });
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
