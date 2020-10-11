<?php

namespace App\Models\Concerns;

trait HasActivityStatus
{
    public function scopeActiveInPastHour($query)
    {
        return $query->where('updated_at', '>=', now()->subHour()->startOfHour());
    }

    public function scopeActiveInPastDay($query)
    {
        return $query->where('updated_at', '>=', now()->subDay()->startOfDay());
    }

    public function scopeActiveInPastMonth($query)
    {
        return $query->where('updated_at', '>=', today()->subMonth()->startOfMonth());
    }

    public function scopeInactiveInPastHour($query)
    {
        return $query->where('updated_at', '<', now()->subHour()->startOfHour());
    }

    public function scopeInactiveInPastDay($query)
    {
        return $query->where('updated_at', '<', now()->subDay()->startOfDay());
    }

    public function scopeInactiveInPastMonth($query)
    {
        return $query->where('updated_at', '<', today()->subMonth()->startOfMonth());
    }
}
