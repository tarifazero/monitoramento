<?php

namespace App\Console\Commands\TimeSeries;

use App\Models\TimeSeries\ActiveVehicleCount;
use App\Models\Vehicle;
use Illuminate\Console\Command;

class CountActiveVehicles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'time-series:count:active-vehicles';

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
        ActiveVehicleCount::updateOrCreate([
            'time' => today()->startOfDay(),
        ], [
            'count' => Vehicle::active()->count(),
        ]);
    }
}
