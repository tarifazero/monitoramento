<?php

namespace App\Builders;

use Illuminate\Database\Eloquent\Builder;

class TimeSeriesBuilder extends Builder
{
    public function latest($column = 'timestamp')
    {
        $this->query->latest($column);

        return $this;
    }

    public function oldest($column = 'timestamp')
    {
        $this->query->oldest($column);

        return $this;
    }
}

