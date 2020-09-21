<?php

namespace App\Console\Commands;

use App\Models\Route;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use League\Csv\CharsetConverter as CsvCharsetConverter;
use League\Csv\Reader as CsvReader;

class FetchRealTimeRoutes extends Command
{
    const DICTIONARY_URL = 'http://servicosbhtrans.pbh.gov.br/bhtrans/webserviceGPS/bhtrans_bdlinha.csv';

    const DICTIONARY_ENCODING = 'iso-8859-1';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:realtime:routes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch the real time data routes dictionary in CSV format';

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
        $routes = $this->fetchRoutes();

        $this->storeRoutes($routes);

        return 0;
    }

    protected function fetchRoutes()
    {
        $response = Http::get(self::DICTIONARY_URL);

        $csv = CsvReader::createFromString($response->body());
        $csv->setDelimiter(';')
                  ->setHeaderOffset(0);

        $encoder = (new CsvCharsetConverter())->inputEncoding(self::DICTIONARY_ENCODING);

        $records = $encoder->convert($csv);

        return collect($records);
    }

    protected function storeRoutes(Collection $routes)
    {
        $routes->each(function ($route) {
            Route::updateOrCreate(
                ['json_id' => $route['NumeroLinha']],
                [
                    'short_name' => $route['Linha'],
                    'long_name' => $route['Nome'],
                    'type' => Route::TYPE_BUS,
                ],
            );
        });
    }
}
