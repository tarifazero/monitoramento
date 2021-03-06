<?php

namespace App\Models;

use App\Scopes\LatestGtfsFetchScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StopTime extends Model
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

    public function stop()
    {
        return $this->belongsTo(Stop::class);
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
}
