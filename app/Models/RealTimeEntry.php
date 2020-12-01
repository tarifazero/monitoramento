<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use League\Geotools\Geotools;
use League\Geotools\Coordinate\Coordinate;

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
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'timestamp' => 'datetime',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
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
            'route_id',
            $route->toFlatTree()->pluck('id')
        );
    }

    public function isNear($latitude, $longitude, $distance = 20)
    {
        $geotools = new Geotools();
        $from = new Coordinate([$this->latitude, $this->longitude]);
        $to = new Coordinate([$latitude, $longitude]);

        return $geotools->distance()
                        ->setFrom($from)
                        ->setTo($to)
                        ->in('km')
                        ->flat() < ($distance / 1000);
    }
}
