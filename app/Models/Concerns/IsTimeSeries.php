<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait IsTimeSeries
{
    protected static function bootIsTimeSeries()
    {
        static::addGlobalScope('orderByTimeDesc', function (Builder $builder) {
            $builder->orderBy('time', 'DESC');
        });
    }

    public function usesTimestamps()
    {
        return false;
    }
}
