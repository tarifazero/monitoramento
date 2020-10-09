<?php

namespace App\Http\Livewire\RealTime;

use App\Models\RealTimeEntry;
use App\Models\Route;
use App\Models\TimeSeries\VehicleCount;
use App\Models\Vehicle;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Support\Facades\DB;
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

    public function getVehicleCountsByHourProperty()
    {
        $startOfDay = today(new DateTimeZone(config('app.display_timezone')))
            ->startOfDay()
            ->setTimezone(config('app.timezone'));

        $endOfDay = today(new DateTimeZone(config('app.display_timezone')))
            ->endOfDay()
            ->setTimezone(config('app.timezone'));

        $vehicleCounts = DB::table((new VehicleCount)->getTable())
            ->whereBetween('time', [$startOfDay, $endOfDay]);

        if ($this->route) {
            $vehicleCounts = $vehicleCounts->selectRaw('time, count')
                                           ->whereIn('route_id', $this->route->toFlatTree()->pluck('id'));
        } else {
            $vehicleCounts = $vehicleCounts->selectRaw('time, sum(count) as count')
                                           ->groupBy('time');
        }

        return $vehicleCounts
            ->get()
            ->keyBy(function ($vehicleCount) {
                return Carbon::parse($vehicleCount->time)
                    ->setTimezone(config('app.display_timezone'))
                    ->hour;
            })
            ->pluck('count');
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
