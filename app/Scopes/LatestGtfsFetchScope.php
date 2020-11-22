<?php

namespace App\Scopes;

use App\Models\GtfsFetch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class LatestGtfsFetchScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $latestGtfsFetch = GtfsFetch::latest();

        if ($latestGtfsFetch) {
            $builder->where('gtfs_fetch_id', $latestGtfsFetch->id);
        }
    }
}
