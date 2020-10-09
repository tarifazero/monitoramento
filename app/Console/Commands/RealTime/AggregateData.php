<?php

namespace App\Console\Commands\RealTime;

use App\Models\RealTimeEntry;
use App\Models\Route;
use App\Models\TimeSeries\VehicleCount;
use App\Models\Vehicle;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
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
    protected $description = 'Aggregate real time data';

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
        $cutOffTime = now()->startOfHour();

        $entries = RealTimeEntry::where('created_at', '<', $cutOffTime)
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

            VehicleCount::firstOrCreate([
                'time' => $entry->created_at->startOfHour(),
                'route_id' => $route->id,
            ])->increment('count');

            $entry->delete();
        }

        $this->cleanupInvalidEntries($cutOffTime);

        return 0;
    }

    protected function handleMissingRoute($entry)
    {
        Log::warning('Cannot aggregate missing route.', ['real_time_id' => $entry->route_real_time_id]);
        $entry->delete();
    }

    public function cleanupInvalidEntries($cutOffTime)
    {
        RealTimeEntry::withoutGlobalScopes()
            ->where('created_at', '<', $cutOffTime)
            ->delete();
    }
}
