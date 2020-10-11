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

    public function getTotalActiveVehicleCountProperty()
    {
        return ActiveVehicleCount::first()->count;
    }

    public function getVehicleCountsByHourProperty()
    {
        $startOfDay = today(new DateTimeZone(config('app.display_timezone')))
            ->startOfDay()
            ->setTimezone(config('app.timezone'));

        $endOfDay = today(new DateTimeZone(config('app.display_timezone')))
            ->endOfDay()
            ->setTimezone(config('app.timezone'));

            return VehiclesByRouteCount::query()
                ->selectRaw('date_trunc(\'hour\', time) as time')
                ->selectRaw('sum(count) as count')
                ->whereBetween('time', [$startOfDay, $endOfDay])
                ->when($this->route, function ($query, $route) {
                    $query->whereIn('route_id', $this->route->toFlatTree()->pluck('id'));
                })
                ->groupBy('time')
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
            $vehicleCount = $this->vehicleCountsByHour->get($hour, 0);

            $statsByHour->put(
                $hour,
                [
                    'vehicle_count' => $vehicleCount,
                    'vehicle_percentage' => $vehicleCount / $this->totalActiveVehicleCount,
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
