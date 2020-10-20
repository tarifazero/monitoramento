<?php

namespace App\Console\Commands\Gtfs;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class Fetch extends Command
{
    const DATA_URL = 'https://ckan.pbh.gov.br/dataset/77764a7e-63fc-4111-ace3-fb7d3037953a'
        . '/resource/f0fa78dc-74c3-49fa-8971-c310a76a07fa/download/gtfsfiles.zip';

    const METADATA_URL = 'https://dados.pbh.gov.br/api/3/action'
        . '/resource_show?id=f0fa78dc-74c3-49fa-8971-c310a76a07fa';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gtfs:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch the GTFS files';

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
        if (! $this->fileHasChanged()) {
            $this->info('The latest file has already been fetched');

            return 0;
        }

        $dateSuffix = today()->toDateString();

        $response = Http::withOptions([
            'sink' => Storage::disk('gtfs')->path("gtfsfiles-{$dateSuffix}.zip"),
        ])->get(self::DATA_URL);

        if ($response->failed()) {
            $this->error('Could not fetch files');

            return 1;
        }

        return 0;
    }

    protected function fileHasChanged()
    {
        $response = Http::get(self::METADATA_URL);

        if ($response->failed()) {
            $this->error('Could not fetch metadata');

            return 1;
        }

        $metadata = $response->json();

        $latestFile = collect(Storage::disk('gtfs')->files())
            ->sort()
            ->last();

        return ! $latestFile
            || Carbon::create($metadata['last_modified'])
                ->greaterThan(Carbon::create(Storage::disk('gtfs')->lastModified($latestFile)));
    }
}
