<?php

namespace App\Console\Commands\RealTime;

use App\Models\RealTimeEntry;
use App\Models\Route;
use App\Models\RouteVehicle;
use App\Models\Vehicle;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AggregateData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'real-time:aggregate-data';

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
            $route = Route::where('real_time_id', $entry->route_real_time_id)
                ->first();

            if (! $route) {
                $this->handleMissingRoute($entry);

                continue;
            }

            $vehicle = Vehicle::firstOrCreate([
                'real_time_id' => $entry->vehicle_real_time_id,
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
        Log::warning('Cannot aggregate missing route.', ['real_time_id' => $entry->route_real_time_id]);
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
