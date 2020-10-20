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
                'last_modified' => now()->subDays(2),
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

        $testFile = new File(base_path('tests/resources/gtfsfiles.zip'));

        Http::fake([
            'ckan.pbh.gov.br/*' => Http::response($testFile, 200),
        ]);

        $this->artisan('gtfs:fetch')
             ->assertExitCode(0);

        $dateSuffix = today()->toDateString();

        Storage::disk('gtfs')
            ->assertExists("gtfsfiles-{$dateSuffix}.zip");
    }
}
