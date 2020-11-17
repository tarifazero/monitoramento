<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trip extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function gtfsFetch()
    {
        return $this->belongsTo(GtfsFetch::class);
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function stopTimes()
    {
        return $this->hasMany(StopTime::class);
    }

    public function scopeForDate($query, $date)
    {
        if (! $service = Service::where('date', $date)->first()) {
            return null;
        }

        return $query->where('service_gtfs_id', $service->gtfs_id);
    }

    public function scopeWithArrivalTime($query)
    {
        $query->addSelect(['arrival_time' => StopTime::select('arrival_time')
            ->whereColumn('trip_id', 'trips.id')
            ->orderBy('stop_sequence', 'desc')
            ->limit(1)
        ]);
    }

    public function scopeWithDepartureTime($query)
    {
        $query->addSelect(['departure_time' => StopTime::select('departure_time')
            ->whereColumn('trip_id', 'trips.id')
            ->orderBy('stop_sequence', 'asc')
            ->limit(1)
        ]);
    }

    public function getArrivalStop()
    {
        return $this->getArrivalStopTime()
                    ->stop;
    }

    public function getArrivalStopTime()
    {
        return $this->stopTimes()
            ->orderBy('stop_sequence', 'desc')
            ->first();
    }

    public function getDepartureStopTime()
    {
        return $this->stopTimes()
            ->orderBy('stop_sequence', 'asc')
            ->first();
    }
}
