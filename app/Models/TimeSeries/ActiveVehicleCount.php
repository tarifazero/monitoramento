<?php

namespace App\Models\TimeSeries;

use App\Models\Concerns\HasResolution;
use App\Models\Concerns\HasUuid;
use App\Models\Concerns\IsTimeSeries;
use App\Models\Route;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActiveVehicleCount extends Model
{
    use HasFactory, HasResolution, HasUuid, IsTimeSeries;

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
}
