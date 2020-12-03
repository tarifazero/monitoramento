<?php

namespace App\Http\Livewire\RealTime\Data;

use App\Models\RealTimeEntry;
use App\Models\Trip;
use DateTimeZone;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class TripDelays extends Component
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

    public function getTripsProperty()
    {
        return Trip::forDate(today())
            ->with('stopTimes.stop')
            ->whereRoute($this->route)
            ->get()
            ->sortBy(function ($trip) {
                return $trip->getDepartureStopTime()->departure_time;
            })
            ->groupBy('direction_id');
    }

    public function getClosestArrival($trip)
    {
        $arrivalStopTime = $trip->getArrivalStopTime();

        return RealTimeEntry::whereRoute($this->route)
            ->whereBetween('timestamp', [$this->startTime, $this->endTime])
            ->where('travel_direction', $trip->real_time_direction)
            ->whereNear($arrivalStopTime->stop->latitude, $arrivalStopTime->stop->longitude, 100)
            ->get()
            ->sortBy(function ($entry) use ($arrivalStopTime) {
                return abs(Carbon::createFromFormat(
                    'H:i:s',
                    $arrivalStopTime->arrival_time,
                    config('app.local_timezone')
                )->diffInMinutes($entry->timestamp));
            })
            ->first();
    }

    public function render()
    {
        return view('livewire.real-time.data.trip-delays', [
            'route' => $this->route,
        ]);
    }
}
