<?php

namespace App\Console\Commands\Gtfs;

use App\Exceptions\ParentStopNotFoundException;
use App\Models\GtfsFetch;
use App\Models\Route;
use App\Models\Stop;
use Illuminate\Console\Command;
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
            try {
                Stop::create([
                    'gtfs_fetch_id' => $gtfs->id,
                    'gtfs_id' => $line[0],
                    'name' => $line[1],
                    'longitude' => $line[2],
                    'latitude' => $line[3],
                    'location_type' => $line[4] ? $line[4] : 0,
                    'parent_station' => $this->getParentStationId($line[5]),
                ]);
            } catch (ParentStopNotFoundException $exception) {
                report($exception);
            }
        });

        return 0;
    }

    protected function getParentStationId($stopGtfsId)
    {
        if (! $stopGtfsId) {
            return null;
        }

        $parentStation = Stop::where('gtfs_id', $stopGtfsId)->first();

        if (! $parentStation) {
            throw new ParentStopNotFoundException($stopGtfsId);

            return null;
        }

        return $parentStation->id;
    }
}
