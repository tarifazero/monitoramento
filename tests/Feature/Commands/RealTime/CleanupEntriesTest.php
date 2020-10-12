<?php

namespace Tests\Feature\Commands\RealTime;

use App\Models\RealTimeEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CleanupEntriesTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    function deletes_invalid_entries()
    {
        $deletedEntry = RealTimeEntry::factory()
            ->invalid()
            ->create();

        $keptEntry = RealTimeEntry::factory()
            ->create();

        $this->assertEquals(2, RealTimeEntry::withoutGlobalScopes()->count());

        $this->artisan('real-time:cleanup:entries')
            ->assertExitCode(0);

        $this->assertEquals(1, RealTimeEntry::withoutGlobalScopes()->count());
        $this->assertNull(RealTimeEntry::find($deletedEntry->id));
        $this->assertNotNull(RealTimeEntry::find($keptEntry->id));
    }
}
