<?php

namespace App\Http\Livewire\RealTime\Data;

use App\Models\RealTimeEntry;
use App\Models\Route;
use App\Models\Vehicle;
use Carbon\Carbon;
use Livewire\Component;

class ActiveVehicles extends Component
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

    public function getCurrentActiveVehicleCountProperty()
    {
        return RealTimeEntry::where('created_at', '>=', now()->subMinutes(5))
            ->when($this->route, function ($query, $route) {
                $query->whereIn('route_real_time_id', $this->route->toFlatTree()->pluck('real_time_id'));
            })
            ->distinct('vehicle_real_time_id')
            ->count();
    }

    public function getMonthlyActiveVehicleCountProperty()
    {
        $count = Vehicle::activeInPastMonth()->count();

        return max([$count, 1]);
    }

    public function getHourlyActiveVehicleCountsProperty()
    {
        return RealTimeEntry::query()
            ->selectRaw('date_trunc(\'hour\', created_at) as hour')
            ->selectRaw('count(distinct vehicle_real_time_id) as count')
            ->whereBetween('created_at', [$this->startTime, $this->endTime])
            ->when($this->route, function ($query, $route) {
                $query->whereIn('route_real_time_id', $this->route->toFlatTree()->pluck('real_time_id'));
            })
            ->groupBy('hour')
            ->get()
            ->mapWithKeys(function ($vehicleCount) {
                $hour = Carbon::parse($vehicleCount->hour)
                    ->setTimezone(config('app.display_timezone'))
                    ->hour;

                return [$hour => $vehicleCount->count];
            });
    }

    public function getStatsByHourProperty()
    {
        $statsByHour = collect();

        foreach (range(0, 23) as $hour) {
            $vehicleCount = $this->hourlyActiveVehicleCounts->get($hour, 0);

            $statsByHour->put(
                $hour,
                [
                    'vehicle_count' => $vehicleCount,
                    'vehicle_percentage' => $vehicleCount / $this->monthlyActiveVehicleCount,
                ]
            );
        }

        return $statsByHour;
    }

    public function render()
    {
        return view('livewire.real-time.data.active-vehicles', [
            'route' => $this->route,
        ]);
    }
}
