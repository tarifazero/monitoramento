<?php

namespace App\Console\Commands\Gtfs;

use App\Models\GtfsFetch;
use App\Models\Route;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ProcessRoutes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gtfs:process:routes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process the GTFS routes file';

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
        if (! $gtfs = GtfsFetch::latest()) {
            $this->error('No GTFS found');

            return 1;
        }

        $gtfs->unzip();

        File::lines($gtfs->getFilePath('routes'))
            ->except(0) // skip header
            ->filter()
            ->map(fn ($line) => str_getcsv($line))
            ->each(function ($line) {
                $shortName = preg_replace('!\s+!', '-', $line[0]);

                Route::updateOrCreate([
                    'short_name' => $shortName,
                    'type' => Route::TYPE_BUS,
                ], [
                    'gtfs_id' => $line[0],
                ]);
            });

        Route::rebuildTree();

        return 0;
    }
}
