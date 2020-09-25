<?php

namespace App\Http\Livewire\RealTime;

use App\Models\RealTimeEntry;
use App\Models\Route;
use Livewire\Component;

class RouteData extends Component
{
    public $route;

    protected $listeners = ['routeSelected'];

    public function getVehicleCountProperty()
    {
        $entries = RealTimeEntry::whereRoute($this->route)
            ->where('created_at', '>=', now()->subMinutes(5))
            ->get();

        return $entries->unique('vehicle_json_id')
            ->count();
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
