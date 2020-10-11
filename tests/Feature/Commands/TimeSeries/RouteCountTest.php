<?php

namespace Tests\Feature\Commands\TimeSeries;

use App\Models\Route;
use App\Models\TimeSeries\RouteCount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RouteCountTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function counts_active_routes_and_stores_to_database()
    {
        Route::factory()
            ->active()
            ->create();

        $this->assertEquals(0, RouteCount::count());

        $this->artisan('time-series:count:routes')
            ->assertExitCode(0);

        $this->assertEquals(1, RouteCount::count());
        $this->assertEquals(1, RouteCount::first()->count);
    }

    /** @test */
    function ignores_inactive_routes()
    {
        Route::factory()
            ->inactive()
            ->create();

        $this->assertEquals(0, RouteCount::count());

        $this->artisan('time-series:count:routes')
            ->assertExitCode(0);

        $this->assertEquals(1, RouteCount::count());
        $this->assertEquals(0, RouteCount::first()->count);
    }
}
