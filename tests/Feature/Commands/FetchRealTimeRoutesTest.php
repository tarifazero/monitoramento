<?php

namespace Tests\Feature\Commands;

use App\Models\Route;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FetchRealTimeRoutesTest extends TestCase
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

        $this->artisan('fetch:realtime:routes')
            ->assertExitCode(0);

        $this->assertCount(2, Route::all());
    }

    /** @test */
    function updates_existing_routes()
    {
        $existingRoute = Route::factory()->create([
            'json_id' => '1',
            'short_name' => '101',
            'long_name' => 'NOVO BARREIRO',
        ]);

        $header = 'NumeroLinha;Linha;Nome';
        $row1 = '1;100;BARREIRO';

        $csv = implode("\r\n", [$header, $row1]);

        Http::fake([
            'servicosbhtrans.pbh.gov.br/*' => Http::response($csv, 200),
        ]);

        $this->artisan('fetch:realtime:routes')
            ->assertExitCode(0);

        $existingRoute->refresh();

        $this->assertEquals('100', $existingRoute->short_name);
        $this->assertEquals('BARREIRO', $existingRoute->long_name);
    }

    /** @test */
    function throws_exceptions_on_http_errors()
    {
        Http::fake([
            'servicosbhtrans.pbh.gov.br/*' => Http::response('Bad request', 400),
        ]);

        $this->expectException(RequestException::class);

        $this->artisan('fetch:realtime:routes');
    }
}
