<?php

namespace App\Console\Commands\Report;

use App\Models\RealTimeEntry;
use App\Models\Route;
use App\Models\Trip;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExecutedTrips extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:executed-trips {date}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a report of the executed trips for a date';

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
        $startOfDay = Carbon::create($this->argument('date'))
            ->startOfDay()
            ->setTimezone(config('app.timezone'));

        $endOfDay = Carbon::create($this->argument('date'))
            ->endOfDay()
            ->setTimezone(config('app.timezone'));

        $routes = Route::whereNotNull('short_name')
            ->whereNotNull('gtfs_id')
            ->whereNotNull('real_time_id')
            ->orderBy('short_name', 'ASC')
            ->get();

        $bar = $this->output->createProgressBar($routes->count());

        $bar->start();

        $routes = $routes->map(function ($route) use ($startOfDay, $endOfDay, $bar) {
            $bar->advance();

            $trips = Trip::forDate($startOfDay)
                ->with('stopTimes.stop')
                ->where('route_id', $route->id)
                ->get();

            if (! $trips->count()) {
                return null;
            }

            $finalStops = $trips->map(function ($trip) {
                if (! $trip->stopTimes->count()) {
                    return null;
                }

                $finalStop = $trip->stopTimes
                                  ->sortBy('stop_sequence')
                                  ->last()
                                  ->stop;

                return [
                    'stop_id' => $finalStop->id,
                    'travel_direction' => $trip->real_time_direction,
                    'latitude' => $finalStop->latitude,
                    'longitude' => $finalStop->longitude,
                ];
            })
            ->filter()
            ->unique('stop_id');

            $arrivals = collect();

            $finalStops->each(function ($finalStop) use ($route, $startOfDay, $endOfDay, &$arrivals) {
                $newArrivals = RealTimeEntry::where('route_id', $route->id)
                    ->whereBetween('timestamp', [$startOfDay, $endOfDay])
                    ->where('travel_direction', $finalStop['travel_direction'])
                    ->whereNear($finalStop['latitude'], $finalStop['longitude'], 100)
                    ->get();

                $arrivals = $arrivals->merge($newArrivals);
            });

            $arrivals = $arrivals->groupBy('vehicle_id')
                                 ->map(function ($arrivals) {
                                     $arrivals = $arrivals->sortBy('timestamp')
                                                          ->values();

                                     return $arrivals->filter(function ($arrival, $index) use ($arrivals) {
                                         if ($index === 0) {
                                             return true;
                                         }

                                         $previousArrival = $arrivals->get($index - 1);

                                         if ($arrival->timestamp->diffInMinutes($previousArrival->timestamp) < 20) {
                                             return false;
                                         }

                                         return true;
                                     });
                                 })
                                 ->flatten(1);

            return [
                $route->short_name,
                $route->long_name,
                $trips->count(),
                $arrivals->count(),
                $finalStops->count(),
                $arrivals->unique('travel_direction')->count(),
                $arrivals->unique('vehicle_id')->count(),
            ];
        })->filter();

        $bar->finish();

        $this->table(
            [
                'Route',
                'Name',
                'Forecast trips',
                'Completed trips',
                'Final stops',
                'Directions',
                'Vehicles',
            ],
            $routes->toArray()
        );

        return 0;
    }
}
