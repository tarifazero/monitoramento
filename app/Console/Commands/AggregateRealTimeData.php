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
        $entries = RealTimeEntry::where('created_at', '<', now()->startOfHour())
            ->cursor();

        foreach ($entries as $entry) {
            $route = Route::where('json_id', $entry->route_json_id)
                ->first();

            if (! $route) {
                $this->handleMissingRoute($entry);

                continue;
            }

            $vehicle = Vehicle::firstOrCreate([
                'json_id' => $entry->vehicle_json_id,
            ]);

            RouteVehicle::firstOrCreate([
                'route_id' => $route->id,
                'vehicle_id' => $vehicle->id,
                'created_at' => $entry->created_at->startOfHour(),
            ]);

            $entry->delete();
        }

        $this->cleanupInvalidEntries();

        return 0;
    }

    protected function handleMissingRoute($entry)
    {
        Log::warning('Cannot aggregate missing route.', ['json_id' => $entry->route_json_id]);
        $entry->delete();
    }

    public function cleanupInvalidEntries()
    {
        RealTimeEntry::withoutGlobalScopes()
            ->where('created_at', '<', now()->startOfHour())
            ->where(function ($query) {
                $query->whereNotIn('event', RealTimeEntry::VALID_EVENTS)
                      ->orWhereNull('travel_direction')
                      ->orWhereNotIn('travel_direction', RealTimeEntry::VALID_TRAVEL_DIRECTIONS);
            })
            ->delete();
    }
}
