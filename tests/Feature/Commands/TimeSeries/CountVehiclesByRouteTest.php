<?php

namespace Tests\Feature\Commands\TimeSeries;

use App\Models\Route;
use App\Models\TimeSeries\VehiclesByRouteCount;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CountVehiclesByRouteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function counts_active_route_vehicles_and_stores_to_database()
    {
        $route = Route::factory()
            ->create();

        $vehicle = Vehicle::factory()
            ->create();

        $route->vehicles()->attach($vehicle);

        $this->assertEquals(0, VehiclesByRouteCount::count());

        $this->artisan('time-series:count:vehicles-by-route')
            ->assertExitCode(0);

        $this->assertEquals(1, VehiclesByRouteCount::count());
        $this->assertEquals(1, VehiclesByRouteCount::first()->count);
    }

    /** @test */
    function ignores_inactive_route_vehicles()
    {
        $route = Route::factory()
            ->create();

        $vehicle = Vehicle::factory()
            ->create();

        $route->vehicles()->attach($vehicle);

        $this->assertEquals(0, VehiclesByRouteCount::count());

        $this->travel(2)->hours();

        $this->artisan('time-series:count:vehicles-by-route')
            ->assertExitCode(0);

        $this->assertEquals(1, VehiclesByRouteCount::count());
        $this->assertEquals(0, VehiclesByRouteCount::first()->count);
    }
}
