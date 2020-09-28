<?php

namespace Database\Seeders;

use App\Models\Route;
use App\Models\RouteVehicle;
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
        RouteVehicle::factory()
            ->for(Route::factory()->state([
                'short_name' => '9502',
                'long_name' => 'SAO GERALDO/SAO FRANCISCO VIA ESPLANADA',
            ]))
            ->for(
            )
            ->count(10)
            ->create();
    }
}
