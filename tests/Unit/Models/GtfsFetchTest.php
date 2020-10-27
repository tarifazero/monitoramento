<?php

namespace Tests\Unit\Models;

use App\Models\GtfsFetch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GtfsFetchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function unzips_gtfs()
    {
        Storage::fake(GtfsFetch::STORAGE_DISK);

        $gtfs = GtfsFetch::factory()->create();

        Storage::disk(GtfsFetch::STORAGE_DISK)
            ->assertMissing($gtfs->path);

        $gtfs->unzip();

        Storage::disk(GtfsFetch::STORAGE_DISK)
            ->assertExists($gtfs->path . '/agency.txt')
            ->assertExists($gtfs->path . '/calendar_dates.txt')
            ->assertExists($gtfs->path . '/fare_attributes.txt')
            ->assertExists($gtfs->path . '/fare_rules.txt')
            ->assertExists($gtfs->path . '/routes.txt')
            ->assertExists($gtfs->path . '/stop_times.txt')
            ->assertExists($gtfs->path . '/stops.txt')
            ->assertExists($gtfs->path . '/trips.txt');
    }
}
