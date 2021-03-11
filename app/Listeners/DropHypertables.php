<?php

namespace App\Listeners;

use Illuminate\Console\Events\CommandStarting;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class DropHypertables
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CommandStarting  $event
     * @return void
     */
    public function handle(CommandStarting $event)
    {
        if (! in_array($event->command, ['db:wipe', 'migrate:fresh'])) {
            return;
        }

        DB::statement('drop table if exists real_time_entries');
    }
}
