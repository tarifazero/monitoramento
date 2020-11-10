<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class ParentStopNotFoundException extends Exception
{
    private $stopGtfsId;

    public function __construct($stopGtfsId)
    {
        $this->stopGtfsId = $stopGtfsId;
    }

    public function report()
    {
        Log::warning('Parent missing for stop with gtfs_id ' . $this->stopGtfsId);
    }
}
