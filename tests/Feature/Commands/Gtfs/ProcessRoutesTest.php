<?php

namespace Tests\Feature\Commands\Gtfs;

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
    function skips_inexisting_routes()
    {
        $this->artisan('gtfs:process:routes')
            ->assertExitCode(0);

        $this->assertEquals(0, Route::count());
    }

    /** @test */
    function updates_existing_routes()
    {
        Storage::fake('gtfs');

        Storage::disk('gtfs')
            ->put('latest/routes.txt', file_get_contents(base_path('tests/resources/routes.txt')));

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
    function exits_with_error_if_routes_file_does_not_exist()
    {
        Storage::fake('gtfs');

        $this->artisan('gtfs:process:routes')
            ->assertExitCode(1);
    }
}
