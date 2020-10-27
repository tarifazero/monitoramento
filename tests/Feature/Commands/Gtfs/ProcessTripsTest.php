<?php

namespace Tests\Feature\Commands\Gtfs;

use App\Models\GtfsFetch;
use App\Models\Route;
use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\Sequence;
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
        Storage::fake(GtfsFetch::STORAGE_DISK);

        Route::factory()
            ->count(2)
            ->state(new Sequence(
                ['gtfs_id' => '4205  01'],
                ['gtfs_id' => 'SC01A 01'],
            ))
            ->create();

        GtfsFetch::factory()->create();

        $this->artisan('gtfs:process:trips')
            ->assertExitCode(0);

        $this->assertEquals(5, Trip::count());
    }

    /** @test */
    function ignores_missing_routes()
    {
        Storage::fake(GtfsFetch::STORAGE_DISK);

        GtfsFetch::factory()->create();

        $this->artisan('gtfs:process:trips')
            ->assertExitCode(0);

        $this->assertEquals(0, Trip::count());
    }

    /** @test */
    function exits_with_error_if_no_gtfs_exists()
    {
        Storage::fake(GtfsFetch::STORAGE_DISK);

        $this->artisan('gtfs:process:trips')
            ->assertExitCode(1);
    }
}
