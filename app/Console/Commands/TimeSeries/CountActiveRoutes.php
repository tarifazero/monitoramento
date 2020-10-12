<?php

namespace App\Console\Commands\TimeSeries;

use App\Models\Route;
use App\Models\TimeSeries\ActiveRouteCount;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CountActiveRoutes extends Command
{
    const VALID_RESOLUTIONS = ['hour', 'day', 'month'];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'time-series:count:active-routes {resolution=day}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Count the currently active routes and store in database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $resolution = $this->argument('resolution');

        if (! in_array($resolution, self::VALID_RESOLUTIONS)) {
            $this->error('Invalid resolution parameter');

            return 1;
        }

        $scope = 'activeInPast' . Str::title($resolution);
        $timeOperation = 'sub' . Str::title($resolution);
        $timeTransform = 'startOf' . Str::title($resolution);

        ActiveRouteCount::updateOrCreate([
            'time' => now()->{$timeOperation}()->{$timeTransform}(),
            'resolution' => $resolution,
        ], [
            'count' => Route::{$scope}()->whereColumn('updated_at', '>', 'created_at')->count(),
        ]);

        return 0;
    }
}
