<?php

namespace App\Models;

use App\Scopes\LatestGtfsFetchScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    protected static function booted()
    {
        static::addGlobalScope(new LatestGtfsFetchScope);
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
        if (! $calendarDate = CalendarDate::where('date', $date)->first()) {
            return null;
        }

        return $query->where('calendar_date_id', $calendarDate->id);
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
