<?php

namespace App\Console\Commands\Gtfs;

use App\Models\GtfsFetch;
use App\Models\Route;
use App\Models\Stop;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\LazyCollection;

class ProcessStops extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gtfs:process:stops';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process the GTFS stops file';

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
                $gtfs->getStopsFilePath(),
                'r'
            );

            while (($line = fgetcsv($handle)) !== false) {
                yield $line;
            }
        })
        ->except(0) // skip header
        ->each(function ($line) use ($gtfs) {
            Stop::create([
                'gtfs_fetch_id' => $gtfs->id,
                'gtfs_id' => $line[0],
                'name' => $line[1],
                'longitude' => $line[2],
                'latitude' => $line[3],
                'location_type' => $line[4] ? $line[4] : null,
                'parent_station' => $line[5] ? $line[5] : null,
            ]);
        });

        return 0;
    }
}
