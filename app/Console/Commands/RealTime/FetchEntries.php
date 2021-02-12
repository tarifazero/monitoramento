<?php

namespace App\Console\Commands\RealTime;

use App\Models\RealTimeEntry;
use App\Models\Route;
use App\Models\Vehicle;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use League\Csv\Reader as CsvReader;

class FetchEntries extends Command
{
    const DATA_URL = 'https://temporeal.pbh.gov.br/?param=C';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'real-time:fetch:entries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch the real time public transit data';

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
        $data = $this->fetchData();

        $this->storeData($data);

        return 0;
    }

    protected function fetchData()
    {
        $response = Http::get(self::DATA_URL)
            ->throw();

        $csv = CsvReader::createFromString($response->body());
        $csv->setDelimiter(';')
            ->setHeaderOffset(0);

        $headers = array_map('trim', $csv->getHeader());

        $records = iterator_to_array($csv->getRecords($headers));

        return collect($records);
    }

    protected function storeData(Collection $data)
    {
        $data->each(function ($item) {
            if (! in_array($item['EV'], RealTimeEntry::VALID_EVENTS)) {
                // TODO: log this
                return;
            }

            if (! in_array($item['SV'], RealTimeEntry::VALID_TRAVEL_DIRECTIONS)) {
                // TODO: log this
                return;
            }
            $vehicle = Vehicle::firstOrCreate([
                'real_time_id' => $item['NV'],
            ]);

            // The realtime data timestamp comes in a YYYYMMDDHHmmSS format
            // and in local timezone
            $timestamp = Carbon::create(
                Str::substr($item['HR'], 0, 4), // Year
                Str::substr($item['HR'], 4, 2), // Month
                Str::substr($item['HR'], 6, 2), // Day
                Str::substr($item['HR'], 8, 2), // Hour
                Str::substr($item['HR'], 10, 2), // Minute
                Str::substr($item['HR'], 12, 2), // Second
                config('app.local_timezone')
            )->setTimezone(config('app.timezone'));

            // The realtime data uses commas as decimal separators
            $latitude = (float) str_replace(',', '.', $item['LT']);
            $longitude = (float) str_replace(',', '.', $item['LG']);


            if ($this->hasOverlappingEntry($vehicle, $item['SV'], $timestamp, $latitude, $longitude)) {
                return;
            }

            $route = Route::firstOrCreate([
                'real_time_id' => $item['NL'],
            ], [
                'type' => Route::TYPE_BUS,
            ]);

            RealTimeEntry::create([
                'vehicle_id' => $vehicle->id,
                'route_id' => $route->id,
                'timestamp' => $timestamp,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'speed' => $item['VL'],
                'travel_direction' => $item['SV'],
            ]);
        });
    }

    protected function hasOverlappingEntry($vehicle, $travel_direction, $timestamp, $latitude, $longitude)
    {
        $latestEntry = $vehicle->realTimeEntries()
                               ->orderByDesc('timestamp')
                               ->first();

        if (! $latestEntry) {
            return false;
        }

        if ($latestEntry->timestamp->diffInMinutes($timestamp) < 1) {
            return true;
        }

        if ($latestEntry->travel_direction == $travel_direction && $latestEntry->isNear($latitude, $longitude)) {
            return true;
        }

        return false;
    }
}
