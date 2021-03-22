<?php

namespace App\Console\Commands\Indicator;

use App\Models\Indicators\ActiveFleetMonthly;
use App\Models\RealTimeEntry;
use App\Models\Vehicle;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;

class CalculateActiveFleetMonthly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'indicator:calculate:active-fleet-monthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculates the monthly active fleet indicator';

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

        $elapsedMonths = $periodStart->monthsUntil(now()->startOfMonth())
                                     ->excludeEndDate();

        $points = collect($elapsedMonths)
            ->map(function ($month) {
                $value = Vehicle::whereHas('realTimeEntries', function ($query) use ($month) {
                    $query->whereBetween('timestamp', [$month, $month->copy()->endOfMonth()]);
                })->count();

                return [
                    'timestamp' => $month,
                    'value' => $value,
                ];
            })
            ->toArray();

        ActiveFleetMonthly::insert($points);

        return 0;
    }

    protected function getPeriodStart()
    {
        if (! RealTimeEntry::count()) {
            return;
        }

        $latestIndicator = ActiveFleetMonthly::latest()->first();

        if (! $latestIndicator) {
            return RealTimeEntry::oldest()
                ->first()
                ->timestamp
                ->startOfMonth();
        }

        return $latestIndicator->timestamp
                               ->startOfMonth()
                               ->addMonth();
    }
}
