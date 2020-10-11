<?php

namespace App\Console\Commands\TimeSeries;

use App\Models\TimeSeries\VehicleCount;
use App\Models\Vehicle;
use Illuminate\Console\Command;

class CountVehicles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'time-series:count:vehicles';

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
        $cutOffTime = today()->subHour()->startOfHour();

        $vehicleCount = Vehicle::where('updated_at', '>=', $cutOffTime)
            ->count();

        VehicleCount::updateOrCreate([
            'time' => $cutOffTime,
        ], [
            'count' => $vehicleCount,
        ]);
    }
}
