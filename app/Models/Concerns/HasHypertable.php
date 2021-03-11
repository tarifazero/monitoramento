<?php

namespace App\Models\Concerns;

trait HasHypertable
{
    protected static function bootHasUuid()
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
}
