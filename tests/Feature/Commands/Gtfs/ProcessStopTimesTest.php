<?php

namespace Tests\Feature\Commands\Gtfs;

use App\Models\GtfsFetch;
use App\Models\Stop;
use App\Models\StopTime;
use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProcessStopTimesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function processes_stop_times()
    {
        Storage::fake(GtfsFetch::STORAGE_DISK);

        $gtfsFetch = GtfsFetch::factory()->create();

        Trip::factory()
            ->create([
                'gtfs_fetch_id' => $gtfsFetch->id,
                'gtfs_id' => 'SC01A 011320090520',
            ]);

        Stop::factory()
            ->count(5)
            ->state(['gtfs_fetch_id' => $gtfsFetch->id])
            ->state(new Sequence(
                ['gtfs_id' => '00101722809716'],
                ['gtfs_id' => '00101722810080'],
                ['gtfs_id' => '00101722810424'],
                ['gtfs_id' => '00101722810500'],
                ['gtfs_id' => '00101722810706'],
            ))
            ->create();


        $this->artisan('gtfs:process:stop-times')
            ->assertExitCode(0);

        $this->assertEquals(5, StopTime::count());
    }

    /** @test */
    function ignores_stop_times_for_missing_trips()
    {
        Storage::fake(GtfsFetch::STORAGE_DISK);

        Stop::factory()
            ->count(5)
            ->state(new Sequence(
                ['gtfs_id' => '00101722809716'],
                ['gtfs_id' => '00101722810080'],
                ['gtfs_id' => '00101722810424'],
                ['gtfs_id' => '00101722810500'],
                ['gtfs_id' => '00101722810706'],
            ))
            ->create();

        GtfsFetch::factory()->create();

        $this->artisan('gtfs:process:stop-times')
            ->assertExitCode(0);

        $this->assertEquals(0, StopTime::count());
    }

    /** @test */
    function ignores_stop_times_for_missing_stops()
    {
        Storage::fake(GtfsFetch::STORAGE_DISK);

        Trip::factory()
            ->create([
                'gtfs_id' => 'SC01A 011320090520',
            ]);

        GtfsFetch::factory()->create();

        $this->artisan('gtfs:process:stop-times')
            ->assertExitCode(0);

        $this->assertEquals(0, StopTime::count());
    }

    /** @test */
    function exits_with_error_if_no_gtfs_exists()
    {
        Storage::fake(GtfsFetch::STORAGE_DISK);

        $this->artisan('gtfs:process:stop-times')
            ->assertExitCode(1);
    }
}
