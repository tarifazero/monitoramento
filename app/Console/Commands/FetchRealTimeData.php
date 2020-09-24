<?php

namespace App\Console\Commands;

use App\Models\Vehicle;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use League\Csv\Reader as CsvReader;

class FetchRealTimeData extends Command
{
    const DATA_URL = 'https://temporeal.pbh.gov.br/?param=C';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:realtime:data';

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
        dd($data->count());

        $this->storeData($data);

        return 0;
    }

    protected function fetchData()
    {
        $response = Http::get(self::DATA_URL);

        $csv = CsvReader::createFromString($response->body());
        $csv->setDelimiter(';')
            ->setHeaderOffset(0);

        $headers = array_map('trim', $csv->getHeader());

        $records = iterator_to_array($csv->getRecords($headers));

        return collect($records)
            ->where('EV', 105)
            ->whereIn('SV', [1, 2]);
    }

    protected function storeData(Collection $data)
    {
        $data->each(function ($item) {
            Vehicle::updateOrCreate(
                ['json_id' => $item['NV']]
            );
        });
    }
}
