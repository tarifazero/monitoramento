<?php

namespace App\Http\Livewire\Fleet;

use App\Models\Indicators\ActiveFleetMonthly;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Historical extends Component
{
    public $daysLimit = 30;

    public function getMonthlyActiveFleetProperty()
    {
        return ActiveFleetMonthly::latest()
            ->first()
            ?->value;
    }

    public function getDailyAverageActiveFleetProperty()
    {
        $timezone = config('app.timezone');
        $local_timezone = config('app.local_timezone');

        return DB::table('indicator_active_fleet_hourly')
            ->selectRaw("date_trunc('day', \"timestamp\" at time zone '{$timezone}' at time zone '{$local_timezone}') as day, AVG(value) as value")
            ->groupBy('day')
            ->orderBy('day', 'DESC')
            ->limit($this->daysLimit)
            ->offset(1)
            ->get()
            ->map(function ($item) {
                $label = (new Carbon($item->day))
                    ->format('d M');

                $value = $this->monthlyActiveFleet
                    ? round(100 * $item->value / $this->monthlyActiveFleet)
                    : 0;

                return compact('label', 'value');
            })
            ->reverse();
    }

    public function getLabelsProperty()
    {
        return $this->dailyAverageActiveFleet->pluck('label');
    }

    public function getValuesProperty()
    {
        return $this->dailyAverageActiveFleet->pluck('value');
    }

    public function setDaysLimit($limit)
    {
        $this->daysLimit = $limit;

        $this->emit('chartUpdated', $this->labels, $this->values);
    }

    public function render()
    {
        return view('livewire.fleet.historical');
    }
}
