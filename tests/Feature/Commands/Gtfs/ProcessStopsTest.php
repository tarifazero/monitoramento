<?php

namespace Tests\Feature\Commands\Gtfs;

use App\Models\GtfsFetch;
use App\Models\Stop;
use Illuminate\Database\Eloquent\Factories\Sequence;
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

        GtfsFetch::factory()->create();

        $this->artisan('gtfs:process:stops')
            ->assertExitCode(0);

        $this->assertEquals(5, Stop::count());
    }

    /** @test */
    function exits_with_error_if_no_gtfs_exists()
    {
        Storage::fake(GtfsFetch::STORAGE_DISK);

        $this->artisan('gtfs:process:stops')
            ->assertExitCode(1);
    }
}
