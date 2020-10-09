<?php

namespace App\Http\Livewire\RealTime;

use App\Models\RealTimeEntry;
use App\Models\Route;
use App\Models\RouteVehicle;
use App\Models\Vehicle;
use Carbon\Carbon;
use DateTimeZone;
use Livewire\Component;

class RouteData extends Component
{
    public $route;

    protected $listeners = ['routeSelected'];

    public function getCurrentVehicleCountProperty()
    {
        $entries = RealTimeEntry::where('created_at', '>=', now()->subMinutes(5))
            ->when($this->route, function ($query, $route) {
                $query->whereRouteWithChildren($this->route);
            })
            ->get();

        return $entries->unique('vehicle_real_time_id')
            ->count();
    }

    public function getTotalVehicleCountProperty()
    {
        return Vehicle::count();
    }

    public function getRouteVehiclesByHourProperty()
    {
        $startOfDay = today(new DateTimeZone(config('app.display_timezone')))
            ->startOfDay()
            ->setTimezone(config('app.timezone'));

        $endOfDay = today(new DateTimeZone(config('app.display_timezone')))
            ->endOfDay()
            ->setTimezone(config('app.timezone'));

        return RouteVehicle::where('created_at', '>=', $startOfDay)
            ->where('created_at', '<=', $endOfDay)
            ->when($this->route, function ($query, $route) {
                $query->whereRouteWithChildren($this->route);
            })
            ->get()
            ->groupBy('created_at')
            ->mapWithKeys(function ($value, $key) {
                $hour = Carbon::parse($key)
                    ->setTimezone(config('app.display_timezone'))
                    ->hour;

                return [
                    $hour => $value,
                ];
            });
    }

    public function getStatsByHourProperty()
    {
        $statsByHour = collect();

        foreach (range(0, 23) as $hour) {
            $vehicleCount = count($this->routeVehiclesByHour->get($hour, []));

            $statsByHour->put(
                $hour,
                [
                    'vehicle_count' => $vehicleCount,
                    'vehicle_percentage' => 100 * $vehicleCount / $this->totalVehicleCount,
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
