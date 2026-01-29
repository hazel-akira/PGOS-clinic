<?php

namespace App\Filament\Admin\Resources\StockBatchResource\Pages;

use App\Filament\Admin\Resources\StockBatchResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStockBatches extends ListRecords
{
    protected static string $resource = StockBatchResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
