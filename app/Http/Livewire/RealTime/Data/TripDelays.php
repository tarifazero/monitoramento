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
                return $trip->getArrivalStopTime()->arrival_time;
            });
    }

    public function getClosestArrival($trip)
    {
        $arrivalStopTime = $trip->getArrivalStopTime();

        $arrivalUTCTime = today(new DateTimeZone(config('app.display_timezone')))
                ->setTimeFromTimeString($arrivalStopTime->arrival_time)
                ->setTimeZone('UTC');

        $entry = RealTimeEntry::fetchedBetween($arrivalUTCTime->copy()->subHour(), $arrivalUTCTime->copy()->addHour())
            ->with('realTimeFetch')
            ->whereRoute($this->route)
            ->where('travel_direction', $trip->real_time_direction)
            ->whereNear($arrivalStopTime->stop->latitude, $arrivalStopTime->stop->longitude)
            ->get()
            ->sortBy(function ($entry) use ($arrivalUTCTime) {
                return abs($arrivalUTCTime->diffInSeconds($entry->realTimeFetch->created_at));
            })
            ->first();

        if (! $entry) {
            return;
        }

        return $entry->timestamp;
    }

    public function render()
    {
        return view('livewire.real-time.data.trip-delays', [
            'route' => $this->route,
        ]);
    }
}
