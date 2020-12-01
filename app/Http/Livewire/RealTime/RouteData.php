<?php

namespace App\Http\Livewire\RealTime;

use App\Models\Route;
use DateTimeZone;
use Livewire\Component;

class RouteData extends Component
{
    protected $route;

    public function mount($route = null)
    {
        $this->route = $route;
    }

    public function getLocalizedStartTimeProperty()
    {
        return today(new DateTimeZone(config('app.local_timezone')))
            ->startOfDay()
            ->setTimezone(config('app.timezone'));
    }

    public function getLocalizedEndTimeProperty()
    {
        return today(new DateTimeZone(config('app.local_timezone')))
            ->endOfDay()
            ->setTimezone(config('app.timezone'));
    }

    public function render()
    {
        return view('livewire.real-time.route-data', [
            'route' => $this->route,
        ]);
    }
}
