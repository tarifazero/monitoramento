<?php

namespace Tests\Feature\Commands\Gtfs;

use App\Models\GtfsFetch;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProcessCalendarDatesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function processes_calendar_dates()
    {
        Storage::fake(GtfsFetch::STORAGE_DISK);

        GtfsFetch::factory()->create();

        $this->artisan('gtfs:process:calendar-dates')
            ->assertExitCode(0);

        $this->assertEquals(5, Service::count());
    }

    /** @test */
    function exits_with_error_if_no_gtfs_exists()
    {
        Storage::fake(GtfsFetch::STORAGE_DISK);

        $this->artisan('gtfs:process:calendar-dates')
            ->assertExitCode(1);
    }
}
