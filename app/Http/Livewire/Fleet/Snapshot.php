<?php

namespace App\Http\Livewire\Fleet;

use App\Models\Indicators\ActiveFleetMonthly;
use App\Models\Vehicle;
use Livewire\Component;

class Snapshot extends Component
{
    public function getMonthlyActiveFleetProperty()
    {
        return ActiveFleetMonthly::latest()
            ->first()
            ?->value;
    }

    public function getCurrentActiveFleetProperty()
    {
        $dateThreshold = now()->subminutes(5);

        return Vehicle::whereHas('realTimeEntries', function ($query) use ($dateThreshold) {
            $query->where('timestamp', '>=', $dateThreshold);
        })->count();
    }

    public function getCurrentActiveFleetPercentageProperty()
    {
        if (! $this->monthlyActiveFleet) {
            return 0;
        }

        return round(100 * $this->currentActiveFleet / $this->monthlyActiveFleet);
    }

    public function render()
    {
        return view('livewire.fleet.snapshot');
    }
}
