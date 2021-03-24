<?php

namespace App\Http\Livewire;

use App\Models\Indicators\ActiveFleetHourly;
use App\Models\Indicators\ActiveFleetMonthly;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Fleet extends Component
{
    public function getDailyAverageActiveFleetProperty()
    {
        $yesterday = now(config('app.local_timezone'))->subDay();

        return DB::table('indicator_active_fleet_hourly')
            ->selectRaw('date_trunc(\'day\', "timestamp") as day, AVG(value) as value')
            ->whereBetween('timestamp', [
                $yesterday->copy()
                          ->subDays(30)
                          ->startOfDay()
                          ->setTimezone(config('app.timezone')),
                $yesterday->copy()
                          ->endOfDay()
                          ->setTimezone(config('app.timezone')),
            ])
            ->groupBy('day')
            ->orderBy('day', 'DESC')
            ->get()
            ->map(function ($item, $index) {
                $label = $index;

                $value = $this->monthlyActiveFleet
                    ? round(100 * $item->value / $this->monthlyActiveFleet)
                    : 0;

                return compact('label', 'value');
            })
            ->reverse();
    }

    public function render()
    {
        return view('livewire.fleet');
    }
}
