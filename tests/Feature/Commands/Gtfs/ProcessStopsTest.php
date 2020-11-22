<?php

namespace Tests\Feature\Commands\Gtfs;

use App\Models\GtfsFetch;
use App\Models\Stop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProcessStopsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function processes_stops()
    {
        Storage::fake(GtfsFetch::STORAGE_DISK);

        $gtfsFetch = GtfsFetch::factory()->create();

        Stop::factory()
            ->create([
                'gtfs_fetch_id' => $gtfsFetch->id,
                'gtfs_id' => '10101153700105',
            ]);

        $this->artisan('gtfs:process:stops')
            ->assertExitCode(0);

        $this->assertEquals(6, Stop::count());
    }

    /** @test */
    function ignores_stops_with_missing_parents()
    {
        Storage::fake(GtfsFetch::STORAGE_DISK);

        GtfsFetch::factory()->create();

        $this->artisan('gtfs:process:stops')
            ->assertExitCode(0);

        $this->assertEquals(4, Stop::count());
    }

    /** @test */
    function exits_with_error_if_no_gtfs_exists()
    {
        Storage::fake(GtfsFetch::STORAGE_DISK);

        $this->artisan('gtfs:process:stops')
            ->assertExitCode(1);
    }
}
