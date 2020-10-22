<?php

namespace Tests\Feature\Commands\Gtfs;

use App\Models\Route;
use App\Models\Trip;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProcessTripsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function processes_trips()
    {
        Route::factory()->create([
            'gtfs_id' => '4205  01',
        ]);

        Route::factory()->create([
            'gtfs_id' => 'SC01A 01',
        ]);

        Storage::fake('gtfs');

        Storage::disk('gtfs')->put(
            'latest/trips.txt',
            file_get_contents(base_path('tests/resources/trips.txt'))
        );

        $this->artisan('gtfs:process:trips')
            ->assertExitCode(0);

        $this->assertEquals(5, Trip::count());
    }

    /** @test */
    function ignores_missing_routes()
    {
        Storage::fake('gtfs');

        Storage::disk('gtfs')->put(
            'latest/trips.txt',
            file_get_contents(base_path('tests/resources/trips.txt'))
        );

        $this->artisan('gtfs:process:trips')
            ->assertExitCode(0);

        $this->assertEquals(0, Trip::count());
    }

    /** @test */
    function deletes_trips_missing_from_gtfs()
    {
        Route::factory()->create([
            'gtfs_id' => '4205  01',
        ]);

        $trip = Trip::factory()
            ->forRoute([
                'gtfs_id' => 'SC01A 01',
            ])
            ->create();

        Storage::fake('gtfs');

        Storage::disk('gtfs')->put(
            'latest/trips.txt',
            file_get_contents(base_path('tests/resources/trips.txt'))
        );

        $this->artisan('gtfs:process:trips')
            ->assertExitCode(0);

        $this->assertEquals(5, Trip::count());
        $this->assertSoftDeleted($trip);
    }

    /** @test */
    function exits_with_error_if_trips_file_does_not_exist()
    {
        Storage::fake('gtfs');

        $this->artisan('gtfs:process:trips')
            ->assertExitCode(1);
    }
}
