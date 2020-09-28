<?php

namespace Tests\Feature\Commands;

use App\Models\RealTimeEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FetchRealTimeDataTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function stores_data_in_database()
    {
        $header = 'EV; HR; LT; LG; NV; VL; NL; DG; SV; DT';
        $row1 = '105;20200924151103;-19,976116;-44,003806;40861;32;7419;38;1;4138';
        $row2 = '105;20200924151108;-19,930916;-43,964478;40435;0;655;200;1;11425';

        $csv = implode("\r\n", [$header, $row1, $row2]);

        Http::fake([
            'temporeal.pbh.gov.br/*' => Http::response($csv, 200),
        ]);

        $this->artisan('fetch:realtime:data')
            ->assertExitCode(0);

        $this->assertCount(2, RealTimeEntry::all());
    }

    /** @test */
    function throws_exceptions_on_http_errors()
    {
        Http::fake([
            'temporeal.pbh.gov.br/*' => Http::response('Bad request', 400),
        ]);

        $this->expectException(RequestException::class);

        $this->artisan('fetch:realtime:data');
    }
}
