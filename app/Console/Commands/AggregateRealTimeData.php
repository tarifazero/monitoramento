<?php

namespace App\Console\Commands;

use App\Models\RealTimeEntry;
use App\Models\Route;
use App\Models\RouteVehicle;
use App\Models\Vehicle;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AggregateRealTimeData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aggregate:realtime:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Aggregate real time data (hourly resolution)';

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
        $aggregatables = RealTimeEntry::where('created_at', '<', now()->startOfHour())
            ->cursor();

        foreach ($aggregatables as $aggregatable) {
            $route = Route::where('json_id', $aggregatable->route_json_id)
                ->first();

            if (! $route) {
                Log::warning('Cannot aggregate missing route.', ['json_id' => $aggregatable->route_json_id]);
                $aggregatable->delete();

                continue;
            }

            $vehicle = Vehicle::firstOrCreate([
                'json_id' => $aggregatable->vehicle_json_id,
            ]);

            RouteVehicle::firstOrCreate([
                'route_id' => $route->id,
                'vehicle_id' => $vehicle->id,
                'created_at' => $aggregatable->created_at->startOfHour(),
            ]);

            $aggregatable->delete();
        }

        return 0;
    }
}
