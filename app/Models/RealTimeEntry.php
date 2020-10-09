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

    public function scopeWhereRouteWithChildren($query, Route $route)
    {
        $children = Route::where('parent_id', $route->id)->pluck('real_time_id');

        return $query->whereIn('route_real_time_id', [$route->real_time_id, ...$children]);
    }

    /**
     * Get the name of the "updated at" column.
     *
     * @return string
     */
    public function getUpdatedAtColumn()
    {
        return null;
    }
}
