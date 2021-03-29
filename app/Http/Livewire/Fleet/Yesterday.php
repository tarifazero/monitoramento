<?php

namespace App\Http\Livewire\Fleet;

use App\Models\Indicators\ActiveFleetHourly;
use App\Models\Indicators\ActiveFleetMonthly;
use Livewire\Component;

class Yesterday extends Component
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

            $opacity = min($value / 100, 1);

            return compact('label', 'value', 'opacity');
        });
    }

    public function getAverageActiveFleetProperty()
    {
        return  round($this->hourlyActiveFleet->average('value'));
    }

    public function render()
    {
        return view('livewire.fleet.yesterday');
    }
}
