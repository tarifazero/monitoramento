<?php

namespace App\Http\Livewire\RealTime;

use App\Models\Route;
use DateTimeZone;
use Livewire\Component;

class RouteData extends Component
{
    public $routeId;

    protected $listeners = ['routeSelected'];

    public function getLocalizedStartTimeProperty()
    {
        return today(new DateTimeZone(config('app.display_timezone')))
            ->startOfDay()
            ->setTimezone(config('app.timezone'));
    }

    public function getLocalizedEndTimeProperty()
    {
        return today(new DateTimeZone(config('app.display_timezone')))
            ->endOfDay()
            ->setTimezone(config('app.timezone'));
    }

    public function getRouteProperty()
    {
        return Route::find($this->routeId);
    }

    public function routeSelected($routeId)
    {
        $this->routeId = $routeId;
    }

    public function render()
    {
        return view('livewire.real-time.route-data');
    }
}
