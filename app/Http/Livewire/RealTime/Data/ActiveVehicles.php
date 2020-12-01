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

    public $route;

    public function mount($startTime, $endTime, $route = null)
    {
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->route = $route;
    }

    public function getCurrentActiveVehicleCountProperty()
    {
        return Vehicle::whereHas('realTimeEntries', function ($query) {
            $query->where('timestamp', '>=', now()->subMinutes(5))
                  ->when($this->route, function ($query, $route) {
                      $query->whereIn('route_id', $this->route->toFlatTree()->pluck('id'));
                  });
        })->count();
    }

    public function getMonthlyActiveVehicleCountProperty()
    {
        return Vehicle::whereHas('realTimeEntries', function ($query) {
            $query->where('timestamp', '>=', now()->subMonth()->startOfMonth())
                  ->when($this->route, function ($query, $route) {
                      $query->whereIn('route_id', $this->route->toFlatTree()->pluck('id'));
                  });
        })->count();

        return max([$count, 1]);
    }

    public function getHourlyActiveVehicleCountsProperty()
    {
        return RealTimeEntry::query()
            ->selectRaw('date_trunc(\'hour\', timestamp) as hour')
            ->selectRaw('count(distinct vehicle_id) as count')
            ->whereBetween('timestamp', [$this->startTime, $this->endTime])
            ->when($this->route, function ($query, $route) {
                $query->whereIn('route_id', $this->route->toFlatTree()->pluck('id'));
            })
            ->groupBy('hour')
            ->get()
            ->mapWithKeys(function ($vehicleCount) {
                $hour = Carbon::parse($vehicleCount->hour)
                    ->setTimezone(config('app.local_timezone'))
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
