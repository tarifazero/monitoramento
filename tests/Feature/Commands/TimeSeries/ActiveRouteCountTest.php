<?php

namespace Tests\Feature\Commands\TimeSeries;

use App\Models\Route;
use App\Models\TimeSeries\ActiveRouteCount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ActiveRouteCountTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function counts_active_routes_and_stores_to_database()
    {
        Route::factory()
            ->active()
            ->create();

        $this->assertEquals(0, ActiveRouteCount::count());

        $this->artisan('time-series:count:active-routes')
            ->assertExitCode(0);

        $this->assertEquals(1, ActiveRouteCount::count());
        $this->assertEquals(1, ActiveRouteCount::first()->count);
    }

    /** @test */
    function ignores_inactive_routes()
    {
        Route::factory()
            ->inactive()
            ->create();

        $this->assertEquals(0, ActiveRouteCount::count());

        $this->artisan('time-series:count:active-routes')
            ->assertExitCode(0);

        $this->assertEquals(1, ActiveRouteCount::count());
        $this->assertEquals(0, ActiveRouteCount::first()->count);
    }
}
