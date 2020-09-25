<?php

namespace App\Http\Livewire\RealTime;

use App\Models\Route;
use Livewire\Component;

class RouteSearch extends Component
{
    public $search = '';

    public function getRoutesProperty()
    {
        if (! $this->search) {
            return null;
        }

        return Route::when($this->search, function ($query, $search) {
            $query->where('short_name', 'LIKE', "%{$search}%")
                ->orWhere('long_name', 'LIKE', "%{$search}%");
        })->get();
    }

    public function selectRoute($routeId)
    {
        $this->emit('routeSelected', $routeId);
    }

    public function render()
    {
        return view('livewire.real-time.route-search');
    }
}
