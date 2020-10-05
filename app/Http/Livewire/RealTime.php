<?php

namespace App\Http\Livewire;

use App\Models\Route;
use Livewire\Component;

class RealTime extends Component
{
    public $route;

    public function mount($routeShortName = null)
    {
        if (! $routeShortName) {
            return;
        }

        $this->route = Route::main()
             ->where('short_name', $routeShortName)
             ->first();
    }

    public function render()
    {
        return view('livewire.real-time');
    }
}
