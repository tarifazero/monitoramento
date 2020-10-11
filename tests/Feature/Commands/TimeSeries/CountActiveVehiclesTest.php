<?php

namespace Tests\Feature\Commands\TimeSeries;

use App\Models\Vehicle;
use App\Models\TimeSeries\ActiveVehicleCount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CountActiveVehiclesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function counts_active_vehicles_with_hour_resolution_and_stores_to_database()
    {
        Vehicle::factory()
            ->activeInPastHour()
            ->create();

        $this->assertEquals(0, ActiveVehicleCount::count());

        $this->artisan('time-series:count:active-vehicles hour')
            ->assertExitCode(0);

        $this->assertEquals(1, ActiveVehicleCount::count());
        $this->assertEquals(1, ActiveVehicleCount::first()->count);
        $this->assertEquals('hour', ActiveVehicleCount::first()->resolution);
        $this->assertEquals(now()->subHour()->startOfHour(), ActiveVehicleCount::first()->time);
    }

    /** @test */
    function counts_active_vehicles_with_day_resolution_and_stores_to_database()
    {
        Vehicle::factory()
            ->activeInPastDay()
            ->create();

        $this->assertEquals(0, ActiveVehicleCount::count());

        $this->artisan('time-series:count:active-vehicles day')
            ->assertExitCode(0);

        $this->assertEquals(1, ActiveVehicleCount::count());
        $this->assertEquals(1, ActiveVehicleCount::first()->count);
        $this->assertEquals('day', ActiveVehicleCount::first()->resolution);
        $this->assertEquals(now()->subDay()->startOfDay(), ActiveVehicleCount::first()->time);
    }

    /** @test */
    function counts_active_vehicles_with_month_resolution_and_stores_to_database()
    {
        Vehicle::factory()
            ->activeInPastMonth()
            ->create();

        $this->assertEquals(0, ActiveVehicleCount::count());

        $this->artisan('time-series:count:active-vehicles month')
            ->assertExitCode(0);

        $this->assertEquals(1, ActiveVehicleCount::count());
        $this->assertEquals(1, ActiveVehicleCount::first()->count);
        $this->assertEquals('month', ActiveVehicleCount::first()->resolution);
        $this->assertEquals(now()->subMonth()->startOfMonth(), ActiveVehicleCount::first()->time);
    }

    /** @test */
    function ignores_inactive_vehicles_with_hour_resolution()
    {
        Vehicle::factory()
            ->inactiveInPastHour()
            ->create();

        $this->assertEquals(0, ActiveVehicleCount::count());

        $this->artisan('time-series:count:active-vehicles hour')
            ->assertExitCode(0);

        $this->assertEquals(1, ActiveVehicleCount::count());
        $this->assertEquals(0, ActiveVehicleCount::first()->count);
        $this->assertEquals(now()->subHour()->startOfHour(), ActiveVehicleCount::first()->time);
    }

    /** @test */
    function ignores_inactive_vehicles_with_day_resolution()
    {
        Vehicle::factory()
            ->inactiveInPastDay()
            ->create();

        $this->assertEquals(0, ActiveVehicleCount::count());

        $this->artisan('time-series:count:active-vehicles day')
            ->assertExitCode(0);

        $this->assertEquals(1, ActiveVehicleCount::count());
        $this->assertEquals(0, ActiveVehicleCount::first()->count);
        $this->assertEquals(now()->subDay()->startOfDay(), ActiveVehicleCount::first()->time);
    }

    /** @test */
    function ignores_inactive_vehicles_with_month_resolution()
    {
        Vehicle::factory()
            ->inactiveInPastMonth()
            ->create();

        $this->assertEquals(0, ActiveVehicleCount::count());

        $this->artisan('time-series:count:active-vehicles month')
            ->assertExitCode(0);

        $this->assertEquals(1, ActiveVehicleCount::count());
        $this->assertEquals(0, ActiveVehicleCount::first()->count);
        $this->assertEquals(now()->subMonth()->startOfMonth(), ActiveVehicleCount::first()->time);
    }

    /** @test */
    function defaults_to_hour_resolution()
    {
        Vehicle::factory()
            ->activeInPastHour()
            ->create();

        $this->artisan('time-series:count:active-vehicles')
            ->assertExitCode(0);

        $this->assertEquals('hour', ActiveVehicleCount::first()->resolution);
    }

    /** @test */
    function returns_error_on_invalid_resolution()
    {
        $this->artisan('time-series:count:active-vehicles foo')
            ->assertExitCode(1);
    }
}
