<?php

namespace App\Models\Concerns;

use App\Builders\TimeSeriesBuilder;

trait IsTimeSeries
{
    protected static function bootIsTimeSeries()
    {
        static::creating(function ($model) {
            $model->timestamp = $model->timestamp ?? now();
        });
    }

    public function getKeyName()
    {
        return null;
    }

    public function getIncrementing()
    {
        return false;
    }

    public function newEloquentBuilder($query)
    {
        return new TimeSeriesBuilder($query);
    }
}
