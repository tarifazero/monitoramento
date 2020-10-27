<?php

namespace Tests\Feature\Commands\Gtfs;

use App\Models\GtfsFetch;
use App\Models\Route;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProcessRoutesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function creates_inexisting_routes()
    {
        Storage::fake(GtfsFetch::STORAGE_DISK);

        GtfsFetch::factory()->create();

        $this->artisan('gtfs:process:routes')
             ->assertExitCode(0);

        $this->assertEquals(5, Route::count());
    }

    /** @test */
    function updates_existing_routes()
    {
        Storage::fake(GtfsFetch::STORAGE_DISK);

        GtfsFetch::factory()->create();

        $route = Route::factory()
            ->realTimeOnly()
            ->create([
                'short_name' => '2102-02',
            ]);

        $this->artisan('gtfs:process:routes')
             ->assertExitCode(0);

        $route->refresh();

        $this->assertEquals('2102  02', $route->gtfs_id);
    }

    /** @test */
    function exits_with_error_if_no_gtfs_exists()
    {
        Storage::fake(GtfsFetch::STORAGE_DISK);

        $this->artisan('gtfs:process:routes')
            ->assertExitCode(1);
    }
}
