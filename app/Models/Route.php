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

    public function vehicles()
    {
        return $this->belongsToMany(Vehicle::class);
    }
}
