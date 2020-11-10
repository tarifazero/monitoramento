<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StopTime extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function gtfsFetch()
    {
        return $this->belongsTo(GtfsFetch::class);
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
