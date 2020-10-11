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
    function counts_active_routes_with_hour_resolution_and_stores_to_database()
    {
        Route::factory()
            ->activeInPastHour()
            ->create();

        $this->assertEquals(0, ActiveRouteCount::count());

        $this->artisan('time-series:count:active-routes hour')
            ->assertExitCode(0);

        $this->assertEquals(1, ActiveRouteCount::count());
        $this->assertEquals(1, ActiveRouteCount::first()->count);
        $this->assertEquals('hour', ActiveRouteCount::first()->resolution);
    }

    /** @test */
    function counts_active_routes_with_day_resolution_and_stores_to_database()
    {
        Route::factory()
            ->activeInPastDay()
            ->create();

        $this->assertEquals(0, ActiveRouteCount::count());

        $this->artisan('time-series:count:active-routes day')
            ->assertExitCode(0);

        $this->assertEquals(1, ActiveRouteCount::count());
        $this->assertEquals(1, ActiveRouteCount::first()->count);
        $this->assertEquals('day', ActiveRouteCount::first()->resolution);
    }

    /** @test */
    function counts_active_routes_with_month_resolution_and_stores_to_database()
    {
        Route::factory()
            ->activeInPastMonth()
            ->create();

        $this->assertEquals(0, ActiveRouteCount::count());

        $this->artisan('time-series:count:active-routes month')
            ->assertExitCode(0);

        $this->assertEquals(1, ActiveRouteCount::count());
        $this->assertEquals(1, ActiveRouteCount::first()->count);
        $this->assertEquals('month', ActiveRouteCount::first()->resolution);
    }

    /** @test */
    function ignores_inactive_routes_with_hour_resolution()
    {
        Route::factory()
            ->inactiveInPastHour()
            ->create();

        $this->assertEquals(0, ActiveRouteCount::count());

        $this->artisan('time-series:count:active-routes hour')
            ->assertExitCode(0);

        $this->assertEquals(1, ActiveRouteCount::count());
        $this->assertEquals(0, ActiveRouteCount::first()->count);
        $this->assertEquals('hour', ActiveRouteCount::first()->resolution);
    }

    /** @test */
    function ignores_inactive_routes_with_day_resolution()
    {
        Route::factory()
            ->inactiveInPastDay()
            ->create();

        $this->assertEquals(0, ActiveRouteCount::count());

        $this->artisan('time-series:count:active-routes day')
            ->assertExitCode(0);

        $this->assertEquals(1, ActiveRouteCount::count());
        $this->assertEquals(0, ActiveRouteCount::first()->count);
        $this->assertEquals('day', ActiveRouteCount::first()->resolution);
    }

    /** @test */
    function ignores_inactive_routes_with_month_resolution()
    {
        Route::factory()
            ->inactiveInPastMonth()
            ->create();

        $this->assertEquals(0, ActiveRouteCount::count());

        $this->artisan('time-series:count:active-routes month')
            ->assertExitCode(0);

        $this->assertEquals(1, ActiveRouteCount::count());
        $this->assertEquals(0, ActiveRouteCount::first()->count);
        $this->assertEquals('month', ActiveRouteCount::first()->resolution);
    }

    /** @test */
    function defaults_to_day_resolution()
    {
        Route::factory()
            ->activeInPastDay()
            ->create();

        $this->artisan('time-series:count:active-routes')
            ->assertExitCode(0);

        $this->assertEquals('day', ActiveRouteCount::first()->resolution);
    }

    /** @test */
    function returns_error_on_invalid_resolution()
    {
        $this->artisan('time-series:count:active-routes foo')
            ->assertExitCode(1);
    }
}
