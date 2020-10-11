<?php

namespace Tests\Feature\Commands\RealTime;

use App\Models\RealTimeEntry;
use App\Models\Route;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProcessEntriesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function creates_missing_routes()
    {
        $entry = RealTimeEntry::factory()
            ->create();

        $this->assertEquals(0, Route::count());

        $this->artisan('real-time:process:entries', [now()->toDateTimeString()])
            ->assertExitCode(0);

        $this->assertEquals(1, Route::count());
        $this->assertEquals($entry->route_real_time_id, Route::first()->real_time_id);
    }

    /** @test */
    function updates_existing_routes()
    {
        $route = Route::factory()
            ->create();

        $entry = RealTimeEntry::factory()
            ->create([
                'route_real_time_id' => $route->real_time_id,
            ]);

        $this->assertEquals(1, Route::count());

        $this->travel(2)->seconds();

        $this->artisan('real-time:process:entries', [now()->toDateTimeString()])
            ->assertExitCode(0);

        $route->refresh();

        $this->assertEquals(1, Route::count());
        $this->assertTrue($route->updated_at->gt($route->created_at));
    }

    /** @test */
    function creates_missing_vehicles()
    {
        $entry = RealTimeEntry::factory()
            ->create();

        $this->assertEquals(0, Vehicle::count());

        $this->artisan('real-time:process:entries', [now()->toDateTimeString()])
            ->assertExitCode(0);

        $this->assertEquals(1, Vehicle::count());
        $this->assertEquals($entry->vehicle_real_time_id, Vehicle::first()->real_time_id);
    }

    /** @test */
    function updates_existing_vehicles()
    {
        $vehicle = Vehicle::factory()
            ->create();

        $entry = RealTimeEntry::factory()
            ->create([
                'vehicle_real_time_id' => $vehicle->real_time_id,
            ]);

        $this->assertEquals(1, Vehicle::count());

        $this->travel(2)->seconds();

        $this->artisan('real-time:process:entries', [now()->toDateTimeString()])
            ->assertExitCode(0);

        $vehicle->refresh();

        $this->assertEquals(1, Vehicle::count());
        $this->assertTrue($vehicle->updated_at->gt($vehicle->created_at));
    }

    /** @test */
    function creates_route_vehicle_relationship()
    {
        $route = Route::factory()
            ->create();

        $vehicle = Vehicle::factory()
            ->create();

        $entry = RealTimeEntry::factory()
            ->create([
                'route_real_time_id' => $route->real_time_id,
                'vehicle_real_time_id' => $vehicle->real_time_id,
            ]);

        $this->assertEquals(0, $route->vehicles()->count());

        $this->artisan('real-time:process:entries', [now()->toDateTimeString()])
            ->assertExitCode(0);

        $this->assertEquals(1, $route->vehicles()->count());
        $this->assertTrue($vehicle->is($route->vehicles->first()));
    }

    /** @test */
    function updates_existing_route_vehicle_relationship()
    {
        $route = Route::factory()
            ->create();

        $vehicle = Vehicle::factory()
            ->create();

        $route->vehicles()->attach($vehicle->id);

        $entry = RealTimeEntry::factory()
            ->create([
                'route_real_time_id' => $route->real_time_id,
                'vehicle_real_time_id' => $vehicle->real_time_id,
            ]);

        $this->assertEquals(1, $route->vehicles()->count());

        $this->travel(1)->seconds();

        $this->artisan('real-time:process:entries', [now()->toDateTimeString()])
            ->assertExitCode(0);

        $this->assertEquals(1, $route->vehicles()->count());

        $relationshipVehicle = $route->vehicles()->first();
        $this->assertTrue($vehicle->is($relationshipVehicle));
        $this->assertTrue($relationshipVehicle->updated_at->gt($relationshipVehicle->created_at));
    }

    /** @test */
    function keeps_other_existing_route_vehicle_relationships()
    {
        $route = Route::factory()
            ->create();

        $vehicles = Vehicle::factory()
            ->count(2)
            ->create();

        $route->vehicles()->attach($vehicles->first()->id);

        $entry = RealTimeEntry::factory()
            ->create([
                'route_real_time_id' => $route->real_time_id,
                'vehicle_real_time_id' => $vehicles->last()->real_time_id,
            ]);

        $this->assertEquals(1, $route->vehicles()->count());

        $this->artisan('real-time:process:entries', [now()->toDateTimeString()])
            ->assertExitCode(0);

        $this->assertEquals(2, $route->vehicles()->count());
    }

    /** @test */
    function marks_processed_entries_as_processed()
    {
        $entry = RealTimeEntry::factory()
            ->create();

        $this->assertEquals(false, $entry->processed);

        $this->artisan('real-time:process:entries', [now()->toDateTimeString()])
            ->assertExitCode(0);

        $entry->refresh();

        $this->assertEquals(true, $entry->processed);
    }
}
