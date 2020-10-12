<?php

namespace App\Models\Concerns;

trait HasActivityStatus
{
    public function scopeActiveInPastHour($query)
    {
        return $query->whereColumn('updated_at', '>', 'created_at')
            ->where('updated_at', '>=', now()->subHour()->startOfHour());
    }

    public function scopeActiveInPastDay($query)
    {
        return $query->whereColumn('updated_at', '>', 'created_at')
            ->where('updated_at', '>=', now()->subDay()->startOfDay());
    }

    public function scopeActiveInPastMonth($query)
    {
        return $query->whereColumn('updated_at', '>', 'created_at')
            ->where('updated_at', '>=', now()->subMonth()->startOfMonth());
    }

    public function scopeInactiveInPastHour($query)
    {
        return $query->whereColumn('updated_at', 'created_at')
            ->orWhere('updated_at', '<', now()->subHour()->startOfHour());
    }

    public function scopeInactiveInPastDay($query)
    {
        return $query->whereColumn('updated_at', 'created_at')
            ->orWhere('updated_at', '<', now()->subDay()->startOfDay());
    }

    public function scopeInactiveInPastMonth($query)
    {
        return $query->whereColumn('updated_at', 'created_at')
            ->orWhere('updated_at', '<', now()->subMonth()->startOfMonth());
    }
}
