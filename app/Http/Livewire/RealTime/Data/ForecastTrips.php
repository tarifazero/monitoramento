<?php

namespace App\Http\Livewire\RealTime\Data;

use App\Models\GtfsFetch;
use App\Models\Route;
use Livewire\Component;

class ForecastTrips extends Component
{
    public $startTime;

    public $endTime;

    protected $route;

    public function mount($startTime, $endTime, $route = null)
    {
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->route = $route;
    }

    public function getForecastTripsCountProperty()
    {
        return GtfsFetch::latest()
            ->trips()
            ->forDate(today())
            ->when($this->route, function ($query, $route) {
                $query->whereIn('route_id', $this->route->toFlatTree()->pluck('id'));
            })
            ->count();
    }

    public function render()
    {
        return view('livewire.real-time.data.forecast-trips', [
            'route' => $this->route,
        ]);
    }
}
