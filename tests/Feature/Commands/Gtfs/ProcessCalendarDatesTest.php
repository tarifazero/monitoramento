<?php

namespace Tests\Feature\Commands\Gtfs;

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
        Storage::fake('gtfs');

        Storage::disk('gtfs')->put(
            'latest/calendar_dates.txt',
            file_get_contents(base_path('tests/resources/calendar_dates.txt'))
        );

        $this->artisan('gtfs:process:calendar-dates')
            ->assertExitCode(0);

        $this->assertEquals(5, Service::count());
    }

    /** @test */
    function exits_with_error_if_calendar_dates_file_does_not_exist()
    {
        Storage::fake('gtfs');

        $this->artisan('gtfs:process:calendar-dates')
            ->assertExitCode(1);
    }
}
