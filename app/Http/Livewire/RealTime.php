<?php

namespace App\Http\Livewire;

use App\Models\Route;
use Carbon\Carbon;
use DateTimeZone;
use Livewire\Component;

class RealTime extends Component
{
    public $routeShortName;

    public $date;

    protected $queryString = ['date'];

    public function getCarbonDateProperty()
    {
        if (! $this->date) {
            return today(new DateTimeZone(config('app.local_timezone')));
        }

        return new Carbon($this->date, new DateTimeZone(config('app.local_timezone')));
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

    public function mount($routeShortName = null)
    {
        $this->routeShortName = $routeShortName;
    }

    public function render()
    {
        return view('livewire.real-time');
    }
}
