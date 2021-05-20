<?php

namespace App\Console\Commands\Report;

use App\Models\RealTimeEntry;
use App\Models\Route;
use App\Models\Trip;
use Carbon\Carbon;
use Illuminate\Console\Command;

class Arrivals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:arrivals {route} {date}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a report of the arrivals for a route and a date';

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

        $day = Carbon::create($this->argument('date'), config('app.local_timezone'));

        $startOfDay = Carbon::create($this->argument('date'), config('app.local_timezone'))
            ->startOfDay()
            ->setTimezone(config('app.timezone'));

        $endOfDay = Carbon::create($this->argument('date'), config('app.local_timezone'))
            ->endOfDay()
            ->setTimezone(config('app.timezone'));

        $route = Route::where('short_name', $this->argument('route'))
            ->whereNotNull('gtfs_id')
            ->whereNotNull('real_time_id')
            ->orderBy('short_name', 'ASC')
            ->first();

        if (! $route) {
            $this->error('Could not find route');

            return 1;
        }

        $trips = Trip::forDate($day)
            ->with('stopTimes.stop')
            ->where('route_id', $route->id)
            ->get();

        if (! $trips->count()) {
            $this->error('No trips for route and date');

            return 1;
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

        $columns = [
            'vehicle_id',
            'route_id',
            'timestamp',
            'latitude',
            'longitude',
            'speed',
            'travel_direction',
            'distance',
        ];

        $arrivals = $arrivals->map(function ($arrival) use ($columns) {
            return $arrival->only($columns);
        });

        $this->table($columns, $arrivals->toArray());

        return 0;
    }
}
