<?php

namespace App\Console\Commands\Gtfs;

use App\Models\GtfsFetch;
use App\Models\Route;
use App\Models\Stop;
use App\Models\StopTime;
use App\Models\Trip;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ProcessStopTimes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gtfs:process:stop-times';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process the GTFS stop_times file';

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

        File::lines($gtfs->getStopTimesFilePath())
            ->except(0) // skip header
            ->map(fn ($line) => str_getcsv($line))
            ->each(function ($line) use ($gtfs) {
                $trip = Trip::where('gtfs_id', $line[0])->first();

                if (! $trip) {
                    Log::warning('Trip missing for stop_time', $line);

                    return;
                }

                $stop = Stop::where('gtfs_id', $line[3])->first();

                if (! $stop) {
                    Log::warning('Stop missing for stop_time', $line);

                    return;
                }

                StopTime::create([
                    'gtfs_fetch_id' => $gtfs->id,
                    'gtfs_id' => $line[0],
                    'trip_id' => $trip->id,
                    'stop_id' => $stop->id,
                    'arrival_time' => $line[1] ? $line[1] : null,
                    'departure_time' => $line[2] ? $line[2] : null,
                    'stop_sequence' => $line[4],
                    'timepoint' => $line[5] ? $line[5] : 1,
                ]);
            });

        return 0;
    }
}
