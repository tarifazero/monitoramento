<?php

namespace App\Console\Commands\Gtfs;

use App\Models\Route;
use App\Models\Trip;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
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
        if (! Storage::disk('gtfs')->exists('latest/trips.txt')) {
            $this->error('The trips file was not found');

            return 1;
        }

        Trip::query()->delete();

        LazyCollection::make(function () {
            $handle = fopen(
                Storage::disk('gtfs')->path('latest/trips.txt'),
                'r'
            );

            while (($line = fgetcsv($handle)) !== false) {
                yield $line;
            }
        })
        ->except(0) // skip header
        ->each(function ($line) {
            $route = Route::where('gtfs_id', $line[0])->first();

            if (! $route) {
                // TODO: log this
                return;
            }

            Trip::withTrashed()
                ->updateOrCreate([
                    'gtfs_id' => $line[2],
                ], [
                    'route_id' => $route->id,
                    'service_gtfs_id' => $line[1],
                    'headsign' => $line[3],
                    'direction_id' => $line[4],
                    'deleted_at' => null,
                ]);
        });

        return 0;
    }
}
