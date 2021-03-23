<?php

namespace Tests\Feature\Commands\Indicator;

use App\Models\Indicators\ActiveFleetMonthly;
use App\Models\RealTimeEntry;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CalculateActiveFleetMonthlyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function does_nothing_if_there_is_no_data()
    {
        $this->artisan('indicator:calculate:active-fleet-monthly')
            ->assertExitCode(0);

        $this->assertCount(0, ActiveFleetMonthly::all());
    }

    /** @test */
    function calculates_monthly_active_vehicles()
    {
        $vehicles = Vehicle::factory()
            ->count(2)
            ->create();

        RealTimeEntry::factory()
            ->for($vehicles[0])
            ->create([
                'timestamp' => now()->subMonth(),
            ]);

        RealTimeEntry::factory()
            ->for($vehicles[1])
            ->create([
                'timestamp' => now()->subMonth(),
            ]);

        $this->artisan('indicator:calculate:active-fleet-monthly')
            ->assertExitCode(0);

        $this->assertEquals(2, ActiveFleetMonthly::latest('timestamp')->first()->value);
    }

    /** @test */
    function creates_one_database_entry_per_month()
    {
        RealTimeEntry::factory()
            ->create([
                'timestamp' => now()->subMonth(),
            ]);

        RealTimeEntry::factory()
            ->create([
                'timestamp' => now()->subMonths(2),
            ]);

        $this->artisan('indicator:calculate:active-fleet-monthly')
            ->assertExitCode(0);

        $this->assertCount(2, ActiveFleetMonthly::all());
    }

    /** @test */
    function ignores_months_that_have_already_been_calculated()
    {
        ActiveFleetMonthly::factory()
            ->create([
                'timestamp' => now()->subMonth()->startOfMonth(),
            ]);

        RealTimeEntry::factory()
            ->create([
                'timestamp' => now()->subMonth(),
            ]);

        $this->artisan('indicator:calculate:active-fleet-monthly')
            ->assertExitCode(0);

        $this->assertCount(1, ActiveFleetMonthly::all());
    }

    /** @test */
    function ignores_current_month()
    {
        RealTimeEntry::factory()
            ->create([
                'timestamp' => now(),
            ]);

        $this->artisan('indicator:calculate:active-fleet-monthly')
            ->assertExitCode(0);

        $this->assertCount(0, ActiveFleetMonthly::all());
    }
}
