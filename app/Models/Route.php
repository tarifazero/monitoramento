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
        return $this->hasMany(RealTimeEntry::class, 'route_real_time_id', 'real_time_id');
    }

    public function vehicles()
    {
        return $this->belongsToMany(Vehicle::class)
            ->withTimestamps();
    }

    public function scopeMain($query)
    {
        return $query->whereNull('parent_id');
    }

    public function toFlatTree()
    {
        return collect([$this, ...$this->children]);
    }

    public function scopeActive($query)
    {
        return $query->where('updated_at', '>=', today()->subWeek()->startOfDay());
    }

    public function scopeInactive($query)
    {
        return $query->where('updated_at', '<', today()->subWeek()->startOfDay());
    }
}
