<?php

namespace App\Console\Commands\Gtfs;

use App\Models\GtfsFetch;
use App\Models\Route;
use App\Models\Trip;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\LazyCollection;

class ProcessTrips extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gtfs:process:trips';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process the GTFS calendar_dates file';

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

        LazyCollection::make(function () use ($gtfs) {
            $handle = fopen(
                $gtfs->getTripsFilePath(),
                'r'
            );

            while (($line = fgetcsv($handle)) !== false) {
                yield $line;
            }
        })
        ->except(0) // skip header
        ->each(function ($line) use ($gtfs) {
            $route = Route::where('gtfs_id', $line[0])->first();

            if (! $route) {
                Log::warning('Route missing for trip ', $line);

                return;
            }

            Trip::create([
                    'gtfs_fetch_id' => $gtfs->id,
                    'gtfs_id' => $line[2],
                    'route_id' => $route->id,
                    'service_gtfs_id' => $line[1],
                    'headsign' => $line[3],
                    'direction_id' => $line[4],
                ]);
        });

        return 0;
    }
}
