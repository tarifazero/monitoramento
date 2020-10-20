<?php

namespace App\Console\Commands\Gtfs;

use App\Models\Service;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\LazyCollection;

class ProcessCalendarDates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gtfs:process:calendar-dates';

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
        if (! Storage::disk('gtfs')->exists('latest/calendar_dates.txt')) {
            $this->error('The calendar_dates file was not found');

            return 1;
        }

        LazyCollection::make(function () {
            $handle = fopen(
                Storage::disk('gtfs')->path('latest/calendar_dates.txt'),
                'r'
            );

            while (($line = fgetcsv($handle)) !== false) {
                yield $line;
            }
        })
        ->except(0) // skip header
        ->each(function ($line) {
            Service::updateOrCreate([
                'gtfs_id' => $line[0],
                'date' => $line[1],
            ], [
                'exception_type' => $line[2],
            ]);
        });

        return 0;
    }
}
