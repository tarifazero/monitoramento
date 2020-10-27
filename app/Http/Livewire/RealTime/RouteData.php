<?php

namespace App\Http\Livewire\RealTime;

use App\Models\GtfsFetch;
use App\Models\RealTimeEntry;
use App\Models\Route;
use App\Models\Trip;
use App\Models\Vehicle;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class RouteData extends Component
{
    public $route;

    protected $listeners = ['routeSelected'];

    public function getCurrentActiveVehicleCountProperty()
    {
        return RealTimeEntry::where('created_at', '>=', now()->subMinutes(5))
            ->when($this->route, function ($query, $route) {
                $query->whereIn('route_real_time_id', $this->route->toFlatTree()->pluck('real_time_id'));
            })
            ->distinct('vehicle_real_time_id')
            ->count();
    }

    public function getMonthlyActiveVehicleCountProperty()
    {
        $count = Vehicle::activeInPastMonth()->count();

        return $count
            ? $count
            : 2500; // TODO: remove this hardcoded value
    }

    public function getLocalizedTimeConstraintsProperty()
    {
        $startOfDay = today(new DateTimeZone(config('app.display_timezone')))
            ->startOfDay()
            ->setTimezone(config('app.timezone'));

        $endOfDay = today(new DateTimeZone(config('app.display_timezone')))
            ->endOfDay()
            ->setTimezone(config('app.timezone'));

        return [$startOfDay, $endOfDay];
    }

    public function getHourlyActiveVehicleCountsProperty()
    {
        return RealTimeEntry::query()
            ->selectRaw('date_trunc(\'hour\', created_at) as hour')
            ->selectRaw('count(distinct vehicle_real_time_id) as count')
            ->whereBetween('created_at', $this->localizedTimeConstraints)
            ->when($this->route, function ($query, $route) {
                $query->whereIn('route_real_time_id', $this->route->toFlatTree()->pluck('real_time_id'));
            })
            ->groupBy('hour')
            ->get()
            ->mapWithKeys(function ($vehicleCount) {
                $hour = Carbon::parse($vehicleCount->hour)
                    ->setTimezone(config('app.display_timezone'))
                    ->hour;

                return [$hour => $vehicleCount->count];
            });
    }

    public function getStatsByHourProperty()
    {
        $statsByHour = collect();

        foreach (range(0, 23) as $hour) {
            $vehicleCount = $this->hourlyActiveVehicleCounts->get($hour, 0);

            $statsByHour->put(
                $hour,
                [
                    'vehicle_count' => $vehicleCount,
                    'vehicle_percentage' => $vehicleCount / $this->monthlyActiveVehicleCount,
                ]
            );
        }

        return $statsByHour;
    }

    public function getForecastTripsCountProperty()
    {
        return GtfsFetch::latest()
            ->trips()
            ->forDate(today())
            ->when($this->route, function ($query, $route) {
                $query->whereIn('route_id', $this->route->toFlatTree()->pluck('id'));
            })
            ->count();
    }

    public function getExecutedTripsCountProperty()
    {
        $cacheKey = 'executed-trips-count';

        if ($this->route) {
            $cacheKey .= '-route-' . $this->route->id;
        } else {
            $cacheKey .= '-global';
        }

        return Cache::remember($cacheKey, now()->endOfDay(), function () {
            $entries = RealTimeEntry::whereBetween('created_at', $this->localizedTimeConstraints)
                ->orderBy('created_at', 'ASC')
                ->when($this->route, function ($query, $route) {
                    $query->whereIn('route_real_time_id', $this->route->toFlatTree()->pluck('real_time_id'));
                })
                ->get();

            $entriesByVehicle = $entries->groupBy('vehicle_real_time_id');

            $count = 0;

            foreach ($entriesByVehicle as $vehicle => $entries) {
                $entries->reduce(function ($previousTravelDirection, $entry) use (&$count) {
                    if ($entry->travel_direction !== $previousTravelDirection) {
                        $count += 1;
                    }

                    return $entry->travel_direction;
                });
            }

            return $count;
        });
    }

    public function routeSelected(Route $route)
    {
        $this->route = $route;
    }

    public function render()
    {
        return view('livewire.real-time.route-data');
    }
}
