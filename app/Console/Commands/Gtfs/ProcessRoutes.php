<?php

namespace App\Console\Commands\Gtfs;

use App\Models\Route;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\LazyCollection;

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
        if (! Storage::disk('gtfs')->exists('latest/routes.txt')) {
            $this->error('The routes file was not found');

            return 1;
        }

        LazyCollection::make(function () {
            $handle = fopen(
                Storage::disk('gtfs')->path('latest/routes.txt'),
                'r'
            );

            while (($line = fgetcsv($handle)) !== false) {
                yield $line;
            }
        })
        ->except(0) // skip header
        ->each(function ($line) {
            $shortName = preg_replace('!\s+!', '-', $line[0]);

            $route = Route::where('short_name', $shortName)->first();

            if (! $route) {
                // TODO: log this
                return;
            }

            if ($route->gtfs_id && $route->gtfs_id !== $line[0]) {
                // TODO: log this
                return;
            }

            $route->update(['gtfs_id' => $line[0]]);
        });

        return 0;
    }
}