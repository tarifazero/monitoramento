<?php

namespace App\Http\Livewire\RealTime;

use App\Models\Route;
use Livewire\Component;

class RouteData extends Component
{
    public $route;

    public $date;

    public function mount($route = null, $date = null)
    {
        $this->route = $route;
        $this->date = $date;
    }

    public function getLocalizedStartTimeProperty()
    {
        return $this->date
                    ->copy()
                    ->startOfDay()
                    ->setTimezone(config('app.timezone'));
    }

    public function getLocalizedEndTimeProperty()
    {
        return $this->date
                    ->copy()
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
