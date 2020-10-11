<?php

namespace Database\Seeders;

use App\Models\Route;
use App\Models\TimeSeries\ActiveRouteCount;
use App\Models\TimeSeries\ActiveVehicleCount;
use App\Models\TimeSeries\VehiclesByRouteCount;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Vehicle::factory()
            ->count(2300)
            ->create();

        $route = Route::factory()->state([
            'short_name' => '9502',
            'long_name' => 'SAO GERALDO/SAO FRANCISCO VIA ESPLANADA',
        ])->create();

        ActiveRouteCount::factory()
            ->create([
                'resolution' => 'day',
            ]);

        ActiveVehicleCount::factory()
            ->create([
                'resolution' => 'month',
                'count' => 3000,
            ]);

        foreach (range(0, 23) as $hour) {

            ActiveVehicleCount::factory()
                ->create([
                    'resolution' => 'hour',
                    'time' => today()->hour($hour),
                ]);

            VehiclesByRouteCount::factory()
                ->create([
                    'resolution' => 'hour',
                    'route_id' => $route->id,
                    'time' => today()->hour($hour),
                ]);
        }
    }
}
