<?php

namespace Tests\Feature\Commands\Indicator;

use App\Models\Indicators\ActiveFleetHourly;
use App\Models\RealTimeEntry;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CalculateActiveFleetHourlyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function does_nothing_if_there_is_no_data()
    {
        $this->artisan('indicator:calculate:active-fleet-hourly')
            ->assertExitCode(0);

        $this->assertCount(0, ActiveFleetHourly::all());
    }

    /** @test */
    function calculates_hourly_active_vehicles()
    {
        $vehicles = Vehicle::factory()
            ->count(2)
            ->create();

        RealTimeEntry::factory()
            ->for($vehicles[0])
            ->create([
                'timestamp' => now()->subHour(),
            ]);

        RealTimeEntry::factory()
            ->for($vehicles[1])
            ->create([
                'timestamp' => now()->subHour(),
            ]);

        $this->artisan('indicator:calculate:active-fleet-hourly')
            ->assertExitCode(0);

        $this->assertEquals(2, ActiveFleetHourly::latest('timestamp')->first()->value);
    }

    /** @test */
    function creates_one_database_entry_per_month()
    {
        RealTimeEntry::factory()
            ->create([
                'timestamp' => now()->subHour(),
            ]);

        RealTimeEntry::factory()
            ->create([
                'timestamp' => now()->subHours(2),
            ]);

        $this->artisan('indicator:calculate:active-fleet-hourly')
            ->assertExitCode(0);

        $this->assertCount(2, ActiveFleetHourly::all());
    }

    /** @test */
    function ignores_months_that_have_already_been_calculated()
    {
        ActiveFleetHourly::factory()
            ->create([
                'timestamp' => now()->subHour()->startOfHour(),
            ]);

        RealTimeEntry::factory()
            ->create([
                'timestamp' => now()->subHour(),
            ]);

        $this->artisan('indicator:calculate:active-fleet-hourly')
            ->assertExitCode(0);

        $this->assertCount(1, ActiveFleetHourly::all());
    }

    /** @test */
    function ignores_current_month()
    {
        RealTimeEntry::factory()
            ->create([
                'timestamp' => now(),
            ]);

        $this->artisan('indicator:calculate:active-fleet-hourly')
            ->assertExitCode(0);

        $this->assertCount(0, ActiveFleetHourly::all());
    }
}
