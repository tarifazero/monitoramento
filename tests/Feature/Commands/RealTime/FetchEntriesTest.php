<?php

namespace Tests\Feature\Commands\RealTime;

use App\Models\RealTimeEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FetchEntriesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function stores_entries_in_database()
    {
        $header = 'EV; HR; LT; LG; NV; VL; NL; DG; SV; DT';
        $row1 = '105;20200924151103;-19,976116;-44,003806;40861;32;7419;38;1;4138';
        $row2 = '105;20200924151108;-19,930916;-43,964478;40435;0;655;200;1;11425';

        $csv = implode("\r\n", [$header, $row1, $row2]);

        Http::fake([
            'temporeal.pbh.gov.br/*' => Http::response($csv, 200),
        ]);

        $this->artisan('real-time:fetch:entries')
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

        $this->artisan('real-time:fetch:entries');
    }

    /** @test */
    function skips_time_overlapping_entries()
    {
        $header = 'EV; HR; LT; LG; NV; VL; NL; DG; SV; DT';
        $row1 = '105;20200924151103;-19,976116;-44,003806;40861;32;7419;38;1;4138';
        $row2 = '105;20200924151108;-19,930916;-43,964478;40861;0;655;200;1;11425';

        $csv = implode("\r\n", [$header, $row1, $row2]);

        Http::fake([
            'temporeal.pbh.gov.br/*' => Http::response($csv, 200),
        ]);

        $this->artisan('real-time:fetch:entries')
            ->assertExitCode(0);

        $this->assertCount(1, RealTimeEntry::all());
    }

    /** @test */
    function skips_location_overlapping_entries()
    {
        $header = 'EV; HR; LT; LG; NV; VL; NL; DG; SV; DT';
        $row1 = '105;20200924151103;-19,976116;-44,003806;40861;32;7419;38;1;4138';
        $row2 = '105;20200924151608;-19,976115;-44,003805;40861;0;655;200;1;11425';

        $csv = implode("\r\n", [$header, $row1, $row2]);

        Http::fake([
            'temporeal.pbh.gov.br/*' => Http::response($csv, 200),
        ]);

        $this->artisan('real-time:fetch:entries')
            ->assertExitCode(0);

        $this->assertCount(1, RealTimeEntry::all());
    }

    /** @test */
    function skipts_invalid_events()
    {
        $header = 'EV; HR; LT; LG; NV; VL; NL; DG; SV; DT';
        $row1 = '104;20200924151103;-19,976116;-44,003806;40861;32;7419;38;1;4138';
        $row2 = '105;20200924151608;-19,976115;-44,003805;40861;0;655;200;1;11425';

        $csv = implode("\r\n", [$header, $row1, $row2]);

        Http::fake([
            'temporeal.pbh.gov.br/*' => Http::response($csv, 200),
        ]);

        $this->artisan('real-time:fetch:entries')
            ->assertExitCode(0);

        $this->assertCount(1, RealTimeEntry::all());
    }

    /** @test */
    function skipts_invalid_directions()
    {
        $header = 'EV; HR; LT; LG; NV; VL; NL; DG; SV; DT';
        $row1 = '105;20200924151103;-19,976116;-44,003806;40861;32;7419;38;3;4138';
        $row2 = '105;20200924151608;-19,976115;-44,003805;40861;0;655;200;1;11425';

        $csv = implode("\r\n", [$header, $row1, $row2]);

        Http::fake([
            'temporeal.pbh.gov.br/*' => Http::response($csv, 200),
        ]);

        $this->artisan('real-time:fetch:entries')
            ->assertExitCode(0);

        $this->assertCount(1, RealTimeEntry::all());
    }
}
