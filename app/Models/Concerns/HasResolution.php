<?php

namespace App\Models\Concerns;

trait HasResolution
{
    public function scopeResolution($query, $resolution)
    {
        return $query->where('resolution', $resolution);
    }
}
