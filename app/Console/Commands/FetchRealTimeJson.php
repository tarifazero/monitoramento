<?php

namespace App\Console\Commands;

use App\Models\Vehicle;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class FetchRealTimeJson extends Command
{
    const JSON_URL = 'https://temporeal.pbh.gov.br/?param=D';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:realtime:json';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch the real time public transit data in JSON format';

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
        $response = Http::get(self::JSON_URL);

        return collect($response->json())
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
