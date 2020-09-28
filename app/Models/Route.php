<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Route extends Model
{
    use HasFactory, SoftDeletes;

    const TYPE_BUS = 3;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function children()
    {
        return $this->hasMany(Route::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Route::class, 'parent_id');
    }

    public function realTimeEntries()
    {
        return $this->hasMany(RealTimeEntry::class, 'route_json_id', 'json_id');
    }

    public function vehicles()
    {
        return $this->belongsToMany(Vehicle::class, 'route_vehicles');
    }

    public function scopeMain($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeWithData($query)
    {
        return $query->whereHas('vehicles')
                     ->orWhereHas('realTimeEntries')
                     ->orWhereHas('children', function ($query) {
                         $query->whereHas('realTimeEntries');
                     });
    }
}
