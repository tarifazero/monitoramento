<?php

namespace App\Models\TimeSeries;

use App\Models\Concerns\HasUuid;
use App\Models\Concerns\IsTimeSeries;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActiveRouteCount extends Model
{
    use HasFactory, HasUuid, IsTimeSeries;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
}
