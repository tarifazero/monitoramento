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
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Get the name of the "updated at" column.
     *
     * @return string
     */
    public function getUpdatedAtColumn()
    {
        return null;
    }

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

    public function route()
    {
        return $this->belongsTo(Route::class, 'route_real_time_id', 'real_time_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_real_time_id', 'real_time_id');
    }

    public function scopeInvalid($query)
    {
        $query->whereNotIn('event', self::VALID_EVENTS)
            ->orWhereNotIn('travel_direction', self::VALID_TRAVEL_DIRECTIONS);
    }

    public function scopeProcessed($query)
    {
        return $query->where('processed', true);
    }

    public function scopeUnprocessed($query)
    {
        return $query->where('processed', false);
    }
}
