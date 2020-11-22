<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealTimeFetch extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public static function latest()
    {
        return self::orderBy('created_at', 'DESC')->first();
    }

    public function entries()
    {
        return $this->hasMany(RealTimeEntry::class);
    }
}
