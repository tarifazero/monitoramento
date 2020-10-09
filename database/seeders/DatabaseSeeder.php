<?php

namespace Database\Seeders;

use App\Models\Route;
use App\Models\TimeSeries\VehicleCount;
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

        foreach (range(0, 23) as $hour) {
            VehicleCount::factory()
                ->count(10)
                ->create([
                    'route_id' => $route->id,
                    'time' => today()->hour($hour),
                ]);
        }
    }
}
