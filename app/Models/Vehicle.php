<?php

namespace App\Models;

use App\Models\Concerns\HasActivityStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, HasActivityStatus, SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function routes()
    {
        return $this->belongsToMany(Route::class)
            ->withTimestamps();
    }
}
