<?php

namespace App\Http\Livewire\RealTime;

use App\Models\RealTimeEntry;
use App\Models\Route;
use App\Models\RouteVehicle;
use Livewire\Component;

class RouteData extends Component
{
    public $route;

    protected $listeners = ['routeSelected'];

    public function getCurrentVehicleCountProperty()
    {
        $entries = RealTimeEntry::whereRouteWithChildren($this->route)
            ->where('created_at', '>=', now()->subMinutes(5))
            ->get();

        return $entries->unique('vehicle_json_id')
            ->count();
    }

    public function getVehicleCountByHourProperty()
    {
        $countByHour = collect();

        foreach (range(0, 23) as $hour) {
            $countByHour->put(
                $hour,
                RouteVehicle::whereRouteWithChildren($this->route)
                    ->where('created_at', today()->hour($hour))
                    ->count()
            );
        }

        return $countByHour;
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
