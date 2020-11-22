<?php

namespace Database\Seeders;

use App\Models\RealTimeEntry;
use App\Models\RealTimeFetch;
use App\Models\Route;
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
            $realTimeFetch = RealTimeFetch::factory()
                ->create([
                    'created_at' => today()->hour($hour)
                ]);

            RealTimeEntry::factory()
                ->count(10)
                ->create([
                    'real_time_fetch_id' => $realTimeFetch->id,
                    'route_real_time_id' => $route->real_time_id,
                ]);
        }
    }
}
