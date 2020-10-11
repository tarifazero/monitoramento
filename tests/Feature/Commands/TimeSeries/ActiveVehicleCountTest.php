<?php

namespace Tests\Feature\Commands\TimeSeries;

use App\Models\Vehicle;
use App\Models\TimeSeries\ActiveVehicleCount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ActiveVehicleCountTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function counts_active_vehicles_and_stores_to_database()
    {
        Vehicle::factory()
            ->active()
            ->create();

        $this->assertEquals(0, ActiveVehicleCount::count());

        $this->artisan('time-series:count:active-vehicles')
            ->assertExitCode(0);

        $this->assertEquals(1, ActiveVehicleCount::count());
        $this->assertEquals(1, ActiveVehicleCount::first()->count);
    }

    /** @test */
    function ignores_inactive_vehicles()
    {
        Vehicle::factory()
            ->inactive()
            ->create();

        $this->assertEquals(0, ActiveVehicleCount::count());

        $this->artisan('time-series:count:active-vehicles')
            ->assertExitCode(0);

        $this->assertEquals(1, ActiveVehicleCount::count());
        $this->assertEquals(0, ActiveVehicleCount::first()->count);
    }
}
