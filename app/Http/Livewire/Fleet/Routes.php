<?php

namespace App\Http\Livewire\Fleet;

use App\Models\RealTimeEntry;
use App\Models\Route;
use Livewire\Component;

class Routes extends Component
{
    public $search = '';

    public function getRoutesProperty()
    {
        return Route::main()
        ->when($this->search, function($query, $search) {
            $query->where('short_name', $search);
        }, function ($query) {
            $query->inRandomOrder();
        })
        ->limit(4)
        ->get()
        ->map(function ($route) {
            $active_vehicles = RealTimeEntry::select('vehicle_id')
                ->whereRoute($route)
                ->where('timestamp', '>=', now()->subMinutes(5))
                ->distinct()
                ->count();

            return collect([
                'short_name' => $route->short_name,
                'active_vehicles' => $active_vehicles,
            ]);
        });
    }

    public function render()
    {
        return view('livewire.fleet.routes');
    }
}
