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
    function downloads_and_stores_gtfs_zip_file()
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
