<?php

namespace App\Models\Indicators;

use App\Models\Concerns\IsTimeSeries;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActiveFleetHourly extends Model
{
    use HasFactory, IsTimeSeries;

    protected $table = 'indicator_active_fleet_hourly';

    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'timestamp' => 'datetime',
    ];
}
