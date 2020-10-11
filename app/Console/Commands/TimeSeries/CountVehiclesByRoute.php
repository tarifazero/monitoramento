<?php

namespace App\Console\Commands\TimeSeries;

use App\Models\Route;
use App\Models\TimeSeries\VehiclesByRouteCount;
use App\Models\Vehicle;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CountVehiclesByRoute extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'time-series:count:vehicles-by-route';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Count the currently active vehicles by route and store in database';

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
        // TODO: implement resolution argument for this command
        $cutOffTime = now()->subHour()->startOfHour();

        $routes = Route::cursor();

        foreach ($routes as $route) {
            $vehicleCount = DB::table('route_vehicle')
                ->where('route_id', $route->id)
                ->where('updated_at', '>=', $cutOffTime)
                ->count();

            VehiclesByRouteCount::updateOrCreate([
                'route_id' => $route->id,
                'resolution' => 'hour',
                'time' => $cutOffTime,
            ], [
                'count' => $vehicleCount,
            ]);
        }
    }
}
