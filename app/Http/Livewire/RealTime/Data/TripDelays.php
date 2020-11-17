<?php

namespace App\Http\Livewire\RealTime\Data;

use App\Models\RealTimeEntry;
use App\Models\Trip;
use DateTimeZone;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class TripDelays extends Component
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

    public function getTripsProperty()
    {
        return Cache::remember("route-{$this->route->id}-trips", now()->addMinutes(10), function () {
            return Trip::forDate(today())
                ->when($this->route, function ($query, $route) {
                    $query->whereIn('route_id', $route->toFlatTree()->pluck('id'));
                })
                ->withArrivalTime()
                ->get()
                ->sortBy('arrival_time');
        });
    }

    public function getClosestArrival($trip)
    {
        $arrivalStop = $trip->getArrivalStop();
        $arrivalUTCTime = today(new DateTimeZone(config('app.display_timezone')))
                ->setTimeFromTimeString($trip->arrival_time)
                ->setTimeZone('UTC');

        $entries = RealTimeEntry::whereBetween(
            'created_at',
            [$arrivalUTCTime->copy()->subHour(), $arrivalUTCTime->copy()->addHour()]
        )->when($this->route, function ($query, $route) use ($arrivalStop) {
            $query->whereIn('route_real_time_id', $this->route->toFlatTree()->pluck('real_time_id'));
        })->whereRaw(
            'ST_DWithin(geography(ST_Point(longitude, latitude)), geography(ST_Point(?, ?)), 10)',
            [$arrivalStop->longitude, $arrivalStop->latitude]
        )->count();

        return $entries;
    }

    public function render()
    {
        return view('livewire.real-time.data.trip-delays', [
            'route' => $this->route,
        ]);
    }
}
