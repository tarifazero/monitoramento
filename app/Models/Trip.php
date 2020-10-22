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

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function scopeForDate($query, $date)
    {
        if (! $service = Service::where('date', $date)->first()) {
            return null;
        }

        return $query->where('service_gtfs_id', $service->gtfs_id);
    }
}
