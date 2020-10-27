<?php

namespace Tests\Feature\Commands\Gtfs;

use App\Models\GtfsFetch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FetchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function creates_gtfs_fetch()
    {
        $testFile = file_get_contents(base_path('tests/resources/gtfsfiles.zip'));

        Http::fake([
            'dados.pbh.gov.br/api/*' => Http::response([
                'result' => [
                    'last_modified' => now(),
                ],
            ], 200),
            'ckan.pbh.gov.br/*' => Http::response($testFile, 200),
        ]);

        Storage::fake(GtfsFetch::STORAGE_DISK);

        $this->assertEquals(0, GtfsFetch::count());

        $this->artisan('gtfs:fetch')
             ->assertExitCode(0);

        $this->assertEquals(1, GtfsFetch::count());
    }

    /** @test */
    function ignores_repeated_gtfs_file()
    {
        $testFile = file_get_contents(base_path('tests/resources/gtfsfiles.zip'));

        Http::fake([
            'dados.pbh.gov.br/api/*' => Http::response([
                'result' => [
                    'last_modified' => today()->subDays(2),
                ],
            ], 200),
            'ckan.pbh.gov.br/*' => Http::response($testFile, 200),
        ]);

        Storage::fake(GtfsFetch::STORAGE_DISK);

        GtfsFetch::factory()->create([
            'created_at' => today(),
        ]);

        $this->artisan('gtfs:fetch')
             ->assertExitCode(0);

        $this->assertEquals(1, GtfsFetch::count());
    }

    /** @test */
    function downloads_and_stores_gtfs_file()
    {
        $testFile = file_get_contents(base_path('tests/resources/gtfsfiles.zip'));

        Http::fake([
            'dados.pbh.gov.br/api/*' => Http::response([
                'result' => [
                    'last_modified' => now()->subDays(2),
                ],
            ], 200),
            'ckan.pbh.gov.br/*' => Http::response($testFile, 200),
        ]);

        Storage::fake(GtfsFetch::STORAGE_DISK);

        $this->assertNull(GtfsFetch::latest());

        $this->artisan('gtfs:fetch')
             ->assertExitCode(0);

        $this->assertNotNull($gtfs = GtfsFetch::latest());
        $this->assertCount(1, Storage::disk(GtfsFetch::STORAGE_DISK)->files());

        Storage::disk(GtfsFetch::STORAGE_DISK)
            ->assertExists(GtfsFetch::latest()->path . '.zip');
    }
}
