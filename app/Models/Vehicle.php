<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

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

    public function scopeActive($query)
    {
        return $query->where('updated_at', '>=', today()->subMonth()->startOfDay());
    }

    public function scopeInactive($query)
    {
        return $query->where('updated_at', '<', today()->subMonth()->startOfDay());
    }
}
