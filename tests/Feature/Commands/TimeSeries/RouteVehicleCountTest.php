<?php

namespace Tests\Feature\Commands\TimeSeries;

use App\Models\Route;
use App\Models\TimeSeries\RouteVehicleCount;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RouteVehicleCountTest extends TestCase
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

        $this->assertEquals(0, RouteVehicleCount::count());

        $this->artisan('time-series:count:route-vehicles')
            ->assertExitCode(0);

        $this->assertEquals(1, RouteVehicleCount::count());
        $this->assertEquals(1, RouteVehicleCount::first()->count);
    }

    /** @test */
    function ignores_inactive_route_vehicles()
    {
        $route = Route::factory()
            ->create();

        $vehicle = Vehicle::factory()
            ->create();

        $route->vehicles()->attach($vehicle);

        $this->assertEquals(0, RouteVehicleCount::count());

        $this->travel(2)->hours();

        $this->artisan('time-series:count:route-vehicles')
            ->assertExitCode(0);

        $this->assertEquals(1, RouteVehicleCount::count());
        $this->assertEquals(0, RouteVehicleCount::first()->count);
    }
}
