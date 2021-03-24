<?php

namespace App\Http\Livewire;

use App\Models\Indicators\ActiveFleetHourly;
use App\Models\Indicators\ActiveFleetMonthly;
use App\Models\Vehicle;
use Livewire\Component;

class Fleet extends Component
{
    public function getMonthlyActiveFleetProperty()
    {
        return ActiveFleetMonthly::latest()
            ->first()
            ?->value;
    }

    public function getYesterdayProperty()
    {
        return now(config('app.local_timezone'))->subDay();
    }

    public function getHourlyActiveFleetProperty()
    {
        return ActiveFleetHourly::whereBetween('timestamp', [
            $this->yesterday->copy()
                            ->startOfDay()
                            ->setTimezone(config('app.timezone')),
            $this->yesterday->copy()
                            ->endOfDay()
                            ->setTimezone(config('app.timezone')),
        ])
        ->oldest()
        ->get()
        ->map(function ($item) {
            $label = $item->timestamp
                          ->setTimezone(config('app.local_timezone'))
                          ->format('G\h');

            $value = $this->monthlyActiveFleet
                ? round(100 * $item->value / $this->monthlyActiveFleet)
                : 0;

            return compact('label', 'value');
        });
    }

    public function getAverageActiveFleetProperty()
    {
        return round($this->hourlyActiveFleet->average('value'));
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
        return view('livewire.fleet');
    }
}
