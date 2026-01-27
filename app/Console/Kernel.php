<?php

namespace App\Console;

use App\Models\StockBatch;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
   protected function schedule(Schedule $schedule): void
    {
       $schedule->call(function () {
            StockBatch::whereNotNull('expiry_date')
                ->whereDate('expiry_date', '<=', now())
                ->where('qty_on_hand', '>', 0)
                ->each(function ($batch) {
                    $batch->update(['qty_on_hand' => 0]);

                    $batch->transactions()->create([
                        'type' => 'out',
                        'quantity' => $batch->qty_on_hand,
                        'reason' => 'Expired stock',
                        'performed_by' => null,
                    ]);

                    $batch->inventory->recalculateStock();
                });
        })->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
