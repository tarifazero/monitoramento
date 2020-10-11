<?php

namespace App\Console\Commands\RealTime;

use App\Models\RealTimeEntry;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CleanupEntries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'real-time:cleanup:entries {cutOffTime?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup processed realtime entries';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $deletedCount = RealTimeEntry::withoutGlobalScopes()
            ->invalid()
            ->orWhere
            ->processed()
            ->delete();

        $this->info("Deleted {$deletedCount} entries");
    }
}
