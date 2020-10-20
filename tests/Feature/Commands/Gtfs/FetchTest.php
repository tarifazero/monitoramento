<?php

namespace Tests\Feature\Commands\Gtfs;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FetchTest extends TestCase
{
    /** @test */
    function ignores_repeated_gtfs_file()
    {
        Storage::fake('gtfs');

        $dateSuffix = today()->subDay()->toDateString();

        Storage::disk('gtfs')->put("gtfsfiles-{$dateSuffix}.zip", 'teste');

        $this->assertCount(1, Storage::disk('gtfs')->files());

        Http::fake([
            'dados.pbh.gov.br/api/*' => Http::response([
                'result' => [
                    'last_modified' => now()->subDays(2),
                ],
            ], 200),
        ]);

        $this->artisan('gtfs:fetch')
             ->assertExitCode(0);

        $this->assertCount(1, Storage::disk('gtfs')->files());
    }

    /** @test */
    function downloads_and_stores_gtfs_file()
    {
        Storage::fake('gtfs');

        $testFile = file_get_contents(base_path('tests/resources/gtfsfiles.zip'));

        Http::fake([
            'dados.pbh.gov.br/api/*' => Http::response([
                'result' => [
                    'last_modified' => now()->subDays(2),
                ],
            ], 200),
            'ckan.pbh.gov.br/*' => Http::response($testFile, 200),
        ]);

        $dateSuffix = today()->toDateString();

        Storage::disk('gtfs')
            ->assertMissing("gtfsfiles-{$dateSuffix}.zip");

        $this->artisan('gtfs:fetch')
             ->assertExitCode(0);

        Storage::disk('gtfs')
            ->assertExists("gtfsfiles-{$dateSuffix}.zip");
    }

    /** @test */
    public function unzips_gtfs_file_after_retrieval()
    {
        Storage::fake('gtfs');

        $testFile = file_get_contents(base_path('tests/resources/gtfsfiles.zip'));

        Http::fake([
            'dados.pbh.gov.br/api/*' => Http::response([
                'result' => [
                    'last_modified' => now()->subDays(2),
                ],
            ], 200),
            'ckan.pbh.gov.br/*' => Http::response($testFile, 200),
        ]);

        Storage::disk('gtfs')
            ->assertMissing('latest');

        $this->artisan('gtfs:fetch')
             ->assertExitCode(0);

        Storage::disk('gtfs')
            ->assertExists('latest/agency.txt')
            ->assertExists('latest/calendar_dates.txt')
            ->assertExists('latest/fare_attributes.txt')
            ->assertExists('latest/fare_rules.txt')
            ->assertExists('latest/routes.txt')
            ->assertExists('latest/stop_times.txt')
            ->assertExists('latest/stops.txt')
            ->assertExists('latest/trips.txt');
    }
}
