<?php

namespace App\Console\Commands\Gtfs;

use App\Models\CalendarDate;
use App\Models\GtfsFetch;
use App\Models\Service;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

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
        if (! $gtfs = GtfsFetch::latest()) {
            $this->error('No GTFS found');

            return 1;
        }

        $gtfs->unzip();

        File::lines($gtfs->getFilePath('calendar_dates'))
            ->except(0) // skip header
            ->filter()
            ->map(fn ($line) => str_getcsv($line))
            ->each(function ($line) use ($gtfs) {
                $service = Service::firstOrCreate([
                    'gtfs_id' => $line[0],
                ]);

                CalendarDate::updateOrCreate([
                    'gtfs_fetch_id' => $gtfs->id,
                    'date' => $line[1],
                ], [
                    'service_id' => $service->id,
                    'exception_type' => $line[2],
                ]);
            });

        return 0;
    }
}
