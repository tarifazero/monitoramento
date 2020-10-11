<?php

namespace App\Console\Commands\TimeSeries;

use App\Models\TimeSeries\ActiveVehicleCount;
use App\Models\Vehicle;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CountActiveVehicles extends Command
{
    const VALID_RESOLUTIONS = ['hour', 'day', 'month'];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'time-series:count:active-vehicles {resolution=hour}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Count the currently active vehicles and store in database';

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

        ActiveVehicleCount::updateOrCreate([
            'time' => today()->startOfDay(),
            'resolution' => $resolution,
        ], [
            'count' => Vehicle::{$scope}()->count(),
        ]);

        return 0;
    }
}
