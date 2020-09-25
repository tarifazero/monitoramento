<?php

namespace Tests\Feature\Commands;

use App\Models\Route;
use App\Models\RouteVehicle;
use App\Models\RouteVehicleInstant;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AggregateRealTimeDataTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    function aggregates_real_time_data()
    {
        $route = Route::factory()->create();
        $vehicle = Vehicle::factory()->create();

        RouteVehicleInstant::factory()
            ->count(10)
            ->create([
                'route_json_id' => $route->json_id,
                'vehicle_json_id' => $vehicle->json_id,
                'created_at' => $this->faker->dateTimeBetween(
                    now()->startOfHour()->subHours(2),
                    now()->startOfHour()->subHour()
                ),
            ]);

        RouteVehicleInstant::factory()
            ->count(10)
            ->create([
                'route_json_id' => $route->json_id,
                'vehicle_json_id' => $vehicle->json_id,
                'created_at' => $this->faker->dateTimeBetween(
                    now()->startOfHour()->subHours(3),
                    now()->startOfHour()->subHours(2)
                ),
            ]);

        $this->artisan('aggregate:realtime:data')
            ->assertExitCode(0);

        $this->assertEquals(2, RouteVehicle::count());
        $this->assertEquals(0, RouteVehicleInstant::count());
    }

    /** @test */
    function keeps_real_time_data_if_unsuccessful_aggregation()
    {
        /**
         * No Routes or Vehicles exist, so this entry is invalid
         * and sould be kept
         */
        RouteVehicleInstant::factory()
            ->create();

        $this->artisan('aggregate:realtime:data')
            ->assertExitCode(0);

        $this->assertEquals(1, RouteVehicleInstant::count());
    }
}
