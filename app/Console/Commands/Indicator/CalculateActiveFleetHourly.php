<?php

namespace App\Console\Commands\Indicator;

use App\Models\Indicators\ActiveFleetHourly;
use App\Models\RealTimeEntry;
use App\Models\Vehicle;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;

class CalculateActiveFleetHourly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'indicator:calculate:active-fleet-hourly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculates the hourly active fleet indicator';

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
        $periodStart = $this->getPeriodStart();

        if (! $periodStart) {
            return 0;
        }

        $elapsedHours = $periodStart->hoursUntil(now()->startOfHour())
                                     ->excludeEndDate();

        $points = collect($elapsedHours)
            ->map(function ($hour) {
                $value = Vehicle::whereHas('realTimeEntries', function ($query) use ($hour) {
                    $query->whereBetween('timestamp', [$hour, $hour->copy()->endOfHour()]);
                })->count();

                return [
                    'timestamp' => $hour,
                    'value' => $value,
                ];
            })
            ->toArray();

        ActiveFleetHourly::insert($points);

        return 0;
    }

    protected function getPeriodStart()
    {
        if (! RealTimeEntry::count()) {
            return;
        }

        $latestIndicator = ActiveFleetHourly::latest()->first();

        if (! $latestIndicator) {
            return RealTimeEntry::oldest()
                ->first()
                ->timestamp
                ->startOfHour();
        }

        return $latestIndicator->timestamp
                               ->startOfHour()
                               ->addHour();
    }
}
