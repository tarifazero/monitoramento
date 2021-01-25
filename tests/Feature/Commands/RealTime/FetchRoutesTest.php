<?php

namespace Tests\Feature\Commands\RealTime;

use App\Models\Route;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FetchRoutesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function stores_new_routes_in_database()
    {
        $header = 'NumeroLinha;Linha;Nome';
        $row1 = '1;100;BARREIRO';
        $row2 = '2;0100-01;BARREIRO';

        $csv = implode("\r\n", [$header, $row1, $row2]);

        Http::fake([
            'servicosbhtrans.pbh.gov.br/*' => Http::response($csv, 200),
        ]);

        $this->artisan('real-time:fetch:routes')
            ->assertExitCode(0);

        $this->assertCount(2, Route::all());
    }

    /** @test */
    function trims_leading_zeroes_from_route_short_names()
    {
        $header = 'NumeroLinha;Linha;Nome';
        $row1 = '2;0100-01;BARREIRO';

        $csv = implode("\r\n", [$header, $row1]);

        Http::fake([
            'servicosbhtrans.pbh.gov.br/*' => Http::response($csv, 200),
        ]);

        $this->artisan('real-time:fetch:routes');

        $this->assertEquals('100-01', Route::first()->short_name);
    }

    /** @test */
    function updates_existing_routes()
    {
        $existingRoute = Route::factory()->create([
            'real_time_id' => '1',
            'short_name' => '101',
            'long_name' => 'NOVO BARREIRO',
        ]);

        $header = 'NumeroLinha;Linha;Nome';
        $row1 = '1;100;BARREIRO';

        $csv = implode("\r\n", [$header, $row1]);

        Http::fake([
            'servicosbhtrans.pbh.gov.br/*' => Http::response($csv, 200),
        ]);

        $this->artisan('real-time:fetch:routes')
            ->assertExitCode(0);

        $existingRoute->refresh();

        $this->assertEquals('100', $existingRoute->short_name);
        $this->assertEquals('BARREIRO', $existingRoute->long_name);
    }

    /** @test */
    function keeps_timestamp_for_unchanged_routes()
    {
        $existingRoute = Route::factory()->create([
            'real_time_id' => '1',
            'short_name' => '100',
            'long_name' => 'BARREIRO',
        ]);

        $updatedAt = $existingRoute->updated_at;

        $header = 'NumeroLinha;Linha;Nome';
        $row1 = '1;100;BARREIRO';

        $csv = implode("\r\n", [$header, $row1]);

        Http::fake([
            'servicosbhtrans.pbh.gov.br/*' => Http::response($csv, 200),
        ]);

        $this->travel(2)->seconds();

        $this->artisan('real-time:fetch:routes')
            ->assertExitCode(0);

        $existingRoute->refresh();

        $this->assertEquals($updatedAt, $existingRoute->updated_at);
    }

    /** @test */
    function sets_route_parent_id_for_child_routes()
    {
        $header = 'NumeroLinha;Linha;Nome';
        $row1 = '1;100;BARREIRO';
        $row2 = '2;0100-01;BARREIRO';

        $csv = implode("\r\n", [$header, $row1, $row2]);

        Http::fake([
            'servicosbhtrans.pbh.gov.br/*' => Http::response($csv, 200),
        ]);

        $this->artisan('real-time:fetch:routes')
            ->assertExitCode(0);

        $parentRoute = Route::where('short_name', '100')->first();
        $childRoute = Route::where('short_name', '100-01')->first();

        $this->assertNull($parentRoute->parent_id);
        $this->assertEquals($parentRoute->id, $childRoute->parent_id);
    }

    /** @test */
    function throws_exceptions_on_http_errors()
    {
        Http::fake([
            'servicosbhtrans.pbh.gov.br/*' => Http::response('Bad request', 400),
        ]);

        $this->expectException(RequestException::class);

        $this->artisan('real-time:fetch:routes');
    }

    /** @test */
    function fixes_route_names_with_dates()
    {
        $header = 'NumeroLinha;Linha;Nome';
        $row1 = '1;31/mar;JATOBA / HOSPITAIS';

        $csv = implode("\r\n", [$header, $row1]);

        Http::fake([
            'servicosbhtrans.pbh.gov.br/*' => Http::response($csv, 200),
        ]);

        $this->artisan('real-time:fetch:routes')
            ->assertExitCode(0);

        $this->assertTrue(Route::where('short_name', '31-03')->exists());
    }
}
