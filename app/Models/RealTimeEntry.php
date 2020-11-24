<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealTimeEntry extends Model
{
    use HasFactory, HasUuid;

    const VALID_EVENTS = [105];

    const VALID_TRAVEL_DIRECTIONS = [1, 2];

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

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('validEvents', function ($builder) {
            $builder->whereIn('event', self::VALID_EVENTS);
        });

        static::addGlobalScope('validTravelDirections', function ($builder) {
            $builder->whereIn('travel_direction', self::VALID_TRAVEL_DIRECTIONS);
        });
    }

    public function realTimeFetch()
    {
        return $this->belongsTo(RealTimeFetch::class);
    }

    public function route()
    {
        return $this->belongsTo(Route::class, 'route_real_time_id', 'real_time_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_real_time_id', 'real_time_id');
    }

    public function scopeFromLatestFetch($query)
    {
        return $query->where('real_time_fetch_id', function ($query) {
            $query->select('id')
                  ->from('real_time_fetches')
                  ->orderByDesc('created_at')
                  ->limit(1);
        });
    }

    public function scopeFetchedBetween($query, $startTime, $endTime)
    {
        return $query->whereIn('real_time_fetch_id', function ($query) use ($startTime, $endTime) {
            $query->select('id')
                  ->from('real_time_fetches')
                  ->whereBetween('created_at', [$startTime, $endTime]);
        });
    }

    public function scopeInvalid($query)
    {
        return $query->whereNotIn('event', self::VALID_EVENTS)
            ->orWhereNotIn('travel_direction', self::VALID_TRAVEL_DIRECTIONS);
    }

    public function scopeProcessed($query)
    {
        return $query->whereNotNull('processed_at');
    }

    public function scopeUnprocessed($query)
    {
        return $query->whereNull('processed_at');
    }

    public function scopeWhereNear($query, $latitude, $longitude, $distance = 20)
    {
        return $query->whereRaw(
            'ST_DWithin(geography(ST_Point(longitude, latitude)), geography(ST_Point(?, ?)), ?)',
            [$longitude, $latitude, $distance]
        );
    }

    public function scopeWhereRoute($query, $route)
    {
        return $query->whereIn(
            'route_real_time_id',
            $route->toFlatTree()->pluck('real_time_id')
        );
    }
}
