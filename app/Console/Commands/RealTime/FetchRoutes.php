<?php

namespace App\Console\Commands\RealTime;

use App\Models\Route;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use League\Csv\CharsetConverter as CsvCharsetConverter;
use League\Csv\Reader as CsvReader;

class FetchRoutes extends Command
{
    const DICTIONARY_URL = 'http://servicosbhtrans.pbh.gov.br/bhtrans/webserviceGPS/bhtrans_bdlinha.csv';

    const DICTIONARY_ENCODING = 'iso-8859-1';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'real-time:fetch-routes';

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

        $this->setParentRoutes();

        return 0;
    }

    protected function fetchRoutes()
    {
        $response = Http::get(self::DICTIONARY_URL)
            ->throw();

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
                ['real_time_id' => $route['NumeroLinha']],
                [
                    'short_name' => ltrim($route['Linha'], '0'),
                    'long_name' => $route['Nome'],
                    'type' => Route::TYPE_BUS,
                ],
            );
        });
    }

    protected function setParentRoutes()
    {
        Route::cursor()->each(function ($route) {
            $baseRoute = Str::before($route->short_name, '-');

            if ($baseRoute === $route->short_name) {
                return;
            }

            $parentRoute = Route::where('short_name', $baseRoute)->first();

            if (! $parentRoute) {
                return;
            }

            $route->update(['parent_id' => $parentRoute->id]);
        });
    }
}
