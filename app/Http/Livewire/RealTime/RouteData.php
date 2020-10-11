<?php

namespace App\Http\Livewire\RealTime;

use App\Models\RealTimeEntry;
use App\Models\Route;
use App\Models\TimeSeries\ActiveVehicleCount;
use App\Models\TimeSeries\VehiclesByRouteCount;
use App\Models\Vehicle;
use Carbon\Carbon;
use DateTimeZone;
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
        $count = ActiveVehicleCount::resolution('month')->first();

        return $count
            ? $count->count
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
        return ActiveVehicleCount::resolution('hour')
            ->whereBetween('time', $this->localizedTimeConstraints)
            ->get()
            ->mapWithKeys(function ($vehicleCount) {
                $hour = Carbon::parse($vehicleCount->time)
                    ->setTimezone(config('app.display_timezone'))
                    ->hour;

                return [$hour => $vehicleCount->count];
            });
    }

    public function getHourlyVehiclesByRouteCountsProperty()
    {
        // TODO: handle sublines (must sum count)
        return VehiclesByRouteCount::resolution('hour')
            ->whereBetween('time', $this->localizedTimeConstraints)
            ->whereIn('route_id', $this->route->toFlatTree()->pluck('id'))
            ->get()
            ->mapWithKeys(function ($vehicleCount) {
                $hour = Carbon::parse($vehicleCount->time)
                    ->setTimezone(config('app.display_timezone'))
                    ->hour;

                return [$hour => $vehicleCount->count];
            });
    }

    public function getStatsByHourProperty()
    {
        $statsByHour = collect();

        foreach (range(0, 23) as $hour) {
            $vehicleCount = $this->route
                ? $this->hourlyVehiclesByRouteCounts->get($hour, 0)
                : $this->hourlyActiveVehicleCounts->get($hour, 0);

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

    public function routeSelected(Route $route)
    {
        $this->route = $route;
    }

    public function render()
    {
        return view('livewire.real-time.route-data');
    }
}
