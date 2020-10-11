<?php

namespace App\Console\Commands\TimeSeries;

use App\Models\Route;
use App\Models\TimeSeries\RouteCount;
use Illuminate\Console\Command;

class CountRoutes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'time-series:count:routes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Count the currently active routes and store in database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $cutOffTime = today()->subDay()->startOfDay();

        $routeCount = Route::where('updated_at', '>=', $cutOffTime)
            ->count();

        RouteCount::updateOrCreate([
            'time' => $cutOffTime,
        ], [
            'count' => $routeCount,
        ]);
    }
}
