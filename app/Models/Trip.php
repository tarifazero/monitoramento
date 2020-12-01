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
        return $query->where('service_id', function ($query) use ($date) {
            $query->select('service_id')
                  ->from('calendar_dates')
                  ->where('date', $date)
                  ->limit(1);
        });
    }

    public function scopeWhereRoute($query, $route)
    {
        return $query->whereIn(
            'route_id',
            $route->toFlatTree()->pluck('id')
        );
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

    public function getRealTimeDirectionAttribute()
    {
        $directionMap = collect([
            collect([
                'gtfs' => 0,
                'real_time' => 2,
            ]),
            collect([
                'gtfs' => 1,
                'real_time' => 2,
            ]),
        ]);

        return optional($directionMap->where('gtfs', $this->direction_id)->first())
            ->get('real_time');
    }

    public function getDepartureStopTime()
    {
        return $this->stopTimes
                    ->sortBy('stop_sequence')
                    ->first();
    }

    public function getArrivalStopTime()
    {
        return $this->stopTimes
                    ->sortByDesc('stop_sequence')
                    ->first();
    }
}
