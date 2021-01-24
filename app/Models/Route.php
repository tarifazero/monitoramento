<?php

namespace App\Models;

use App\Models\Concerns\HasActivityStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Route extends Model
{
    use HasFactory, HasActivityStatus, SoftDeletes;

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

    public function realTimeEntries()
    {
        return $this->hasMany(RealTimeEntry::class);
    }

    public function scopeMain($query)
    {
        return $query->whereNull('parent_id');
    }

    public function toFlatTree()
    {
        return collect([$this, ...$this->children]);
    }

    public function scopeActive($query)
    {
        return $query->where('updated_at', '>=', today()->subWeek()->startOfDay());
    }

    public function scopeInactive($query)
    {
        return $query->where('updated_at', '<', today()->subWeek()->startOfDay());
    }

    public static function rebuildTree()
    {
        self::cursor()->each(function ($route) {
            $base = Str::before($route->short_name, '-');

            if ($base === $route->short_name) {
                return;
            }

            $parent = self::where('short_name', $base)
                ->first();

            if (! $parent) {
                return;
            }

            $route->update(['parent_id' => $parent->id]);
        });
    }

    public function hasRealTimeId()
    {
        return $this->toFlatTree()
                    ->pluck('real_time_id')
                    ->filter()
                    ->count() > 0;
    }
}
