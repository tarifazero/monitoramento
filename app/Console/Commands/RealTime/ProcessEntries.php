<?php

namespace App\Console\Commands\RealTime;

use App\Models\RealTimeEntry;
use App\Models\Route;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ProcessEntries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'real-time:process:entries {cutOffTime?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process the realtime entries';

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
        $cutOffTime = $this->argument('cutOffTime')
            ? Carbon::parse($this->argument('cutOffTime'))
            : now()->startOfHour();

        $entries = RealTimeEntry::where('created_at', '<', $cutOffTime)
            ->orderBy('created_at', 'ASC');

        foreach ($entries->cursor() as $entry) {
            $route = Route::updateOrCreate([
                'real_time_id' => $entry->route_real_time_id,
                'type' => Route::TYPE_BUS,
            ], [
                'updated_at' => now(),
            ]);

            $vehicle = Vehicle::updateOrCreate([
                'real_time_id' => $entry->vehicle_real_time_id,
            ], [
                'updated_at' => now(),
            ]);

            $route->vehicles()->syncWithoutDetaching([
                $vehicle->id => [
                    'updated_at' => now(),
                ],
            ]);
        }

        $entries->update(['processed' => true]);

        $this->info("Processed {$entries->count()} entries");
    }
}
