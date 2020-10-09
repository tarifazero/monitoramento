<?php

namespace Tests\Feature\Commands;

use App\Models\RealTimeEntry;
use App\Models\Route;
use App\Models\RouteVehicle;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class AggregateRealTimeDataTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    function aggregates_real_time_data()
    {
        $route = Route::factory()->create();
        $vehicle = Vehicle::factory()->create();

        RealTimeEntry::factory()
            ->count(10)
            ->create([
                'route_realtime_id' => $route->realtime_id,
                'vehicle_realtime_id' => $vehicle->realtime_id,
                'created_at' => $this->faker->dateTimeBetween(
                    now()->startOfHour()->subHours(2),
                    now()->startOfHour()->subHour()
                ),
            ]);

        RealTimeEntry::factory()
            ->count(10)
            ->create([
                'route_realtime_id' => $route->realtime_id,
                'vehicle_realtime_id' => $vehicle->realtime_id,
                'created_at' => $this->faker->dateTimeBetween(
                    now()->startOfHour()->subHours(3),
                    now()->startOfHour()->subHours(2)
                ),
            ]);

        $this->artisan('aggregate:realtime:data')
            ->assertExitCode(0);

        $this->assertEquals(2, RouteVehicle::count());
        $this->assertEquals(0, RealTimeEntry::count());
    }

    /** @test */
    function creates_missing_vehicles()
    {
        $route = Route::factory()->create();

        RealTimeEntry::factory()
            ->create([
                'route_realtime_id' => $route->realtime_id,
                'created_at' => $this->faker->dateTimeBetween(
                    now()->startOfHour()->subHours(2),
                    now()->startOfHour()->subHour()
                ),
            ]);

        $this->artisan('aggregate:realtime:data')
            ->assertExitCode(0);

        $this->assertEquals(1, RouteVehicle::count());
        $this->assertEquals(1, Vehicle::count());
        $this->assertEquals(0, RealTimeEntry::count());

    }

    /** @test */
    function logs_and_deletes_real_time_data_if_unsuccessful_aggregation()
    {
        /**
         * No Routes exist, so this entry is invalid
         * and sould be logged
         */
        $entry = RealTimeEntry::factory()
            ->create([
                'created_at' => $this->faker->dateTimeBetween(
                    now()->startOfHour()->subHours(2),
                    now()->startOfHour()->subHour()
                ),
            ]);

        Log::shouldReceive('warning')
            ->with('Cannot aggregate missing route.', ['realtime_id' => $entry->route_realtime_id]);

        $this->assertEquals(1, RealTimeEntry::count());

        $this->artisan('aggregate:realtime:data')
            ->assertExitCode(0);

        $this->assertEquals(0, RealTimeEntry::count());
    }

    /** @test */
    function deletes_invalid_events()
    {
        RealTimeEntry::factory()
            ->create([
                'event' => 'foo',
                'created_at' => $this->faker->dateTimeBetween(
                    now()->startOfHour()->subHours(2),
                    now()->startOfHour()->subHour()
                ),
            ]);

        $this->assertEquals(1, RealTimeEntry::withoutGlobalScopes()->count());

        $this->artisan('aggregate:realtime:data')
            ->assertExitCode(0);

        $this->assertEquals(0, RealTimeEntry::withoutGlobalScopes()->count());
    }
}
