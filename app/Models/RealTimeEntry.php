<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealTimeEntry extends Model
{
    use HasFactory, HasUuid;

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
            $builder->where('event', 105);
        });

        static::addGlobalScope('validTravelDirections', function ($builder) {
            $builder->whereIn('travel_direction', [1, 2]);
        });
    }

    public function scopeWhereRouteWithChildren($query, Route $route)
    {
        $children = Route::where('parent_id', $route->id)->pluck('json_id');

        return $query->whereIn('route_json_id', [$route->json_id, ...$children]);
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
