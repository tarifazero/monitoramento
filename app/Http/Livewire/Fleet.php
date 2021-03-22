<?php

namespace App\Http\Livewire;

use App\Models\Indicators\ActiveFleetMonthly;
use App\Models\Vehicle;
use Livewire\Component;

class Fleet extends Component
{
    public function getActiveFleetProperty()
    {
        return ActiveFleetMonthly::latest('timestamp')->first()->value;
    }

    public function getActiveVehiclesProperty()
    {
        $dateThreshold = now()->subminutes(5);

        return Vehicle::whereHas('realTimeEntries', function ($query) use ($dateThreshold) {
            $query->where('timestamp', '>=', $dateThreshold);
        })->count();
    }

    public function getActiveVehiclesPercentageProperty()
    {
        return round($this->activeVehicles / $this->activeFleet);
    }

    public function render()
    {
        return view('livewire.fleet');
    }
}
