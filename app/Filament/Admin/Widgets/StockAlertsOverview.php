<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Inventory;
use App\Models\StockBatch;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StockAlertsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
     
        $lowStockCount = Inventory::query()
            ->whereHas('batches', function ($q) {
                $q->selectRaw('inventory_id, SUM(qty_on_hand) as total')
                    ->groupBy('inventory_id')
                    ->havingRaw('SUM(qty_on_hand) <= 10');
            })
            ->count();

       
        $expiringSoonCount = StockBatch::query()
            ->whereBetween('expiry_date', [
                now(),
                now()->addDays(30),
            ])
            ->count();

        
        $expiredCount = StockBatch::query()
            ->whereDate('expiry_date', '<', now())
            ->count();

        return [
            Stat::make('Low Stock Medicines', $lowStockCount)
                ->url(route('filament.admin.resources.inventories.index'))
                ->description('Stock below threshold')
                ->descriptionIcon('heroicon-o-exclamation-triangle')
                ->color('danger'),

            Stat::make('Expiring Soon', $expiringSoonCount)
                ->url(route('filament.admin.resources.inventories.index'))
                ->description('Batches expiring in 30 days')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Expired Stock', $expiredCount)
                ->url(route('filament.admin.resources.inventories.index'))
                ->description('Needs removal/disposal')
                ->descriptionIcon('heroicon-o-x-circle')
                ->color('gray'),
        ];
    }
}
