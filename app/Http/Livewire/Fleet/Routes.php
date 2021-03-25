<?php

namespace App\Http\Livewire\Fleet;

use App\Models\Route;
use App\Models\Vehicle;
use Livewire\Component;

class Routes extends Component
{
    public $search = '';

    public function getRoutesProperty()
    {
        return Route::main()
        ->with('children')
        ->when($this->search, function($query, $search) {
            $query->where('short_name', $search);
        }, function ($query) {
            $query->inRandomOrder();
        })
        ->limit(4)
        ->get()
        ->map(function ($route) {
            $active_vehicles = Vehicle::whereHas('realTimeEntries', function ($query) use ($route) {
                $query->where('timestamp', '>=', now()->subMinutes(5))
                      ->whereIn('route_id', $route->toFlatTree()->pluck('id'));
            })->count();

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
