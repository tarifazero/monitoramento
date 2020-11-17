<?php

namespace App\Http\Livewire;

use App\Models\Route;
use Livewire\Component;

class RealTime extends Component
{
    public $routeShortName;

    public function mount($routeShortName = null)
    {
        $this->routeShortName = $routeShortName;
    }

    public function getRouteProperty()
    {
        if (! $this->routeShortName) {
            return null;
        }

        return Route::main()
             ->where('short_name', $this->routeShortName)
             ->first();
    }

    public function render()
    {
        return view('livewire.real-time');
    }
}
