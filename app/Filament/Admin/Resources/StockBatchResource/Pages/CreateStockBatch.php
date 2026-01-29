<?php

namespace App\Filament\Admin\Resources\StockBatchResource\Pages;

use App\Filament\Admin\Resources\StockBatchResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateStockBatch extends CreateRecord
{
    protected static string $resource = StockBatchResource::class;

    protected function getRedirectUrl(): string
    {
        return static::$resource::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $batch = $this->record;

        // Only log if qty_on_hand was provided
        if ($batch->qty_on_hand > 0) {
            $batch->transactions()->create([
                'type' => 'in',
                'quantity' => $batch->qty_on_hand,
                'notes' => 'Initial stock added when batch was created',
            ]);
        }
    }
}
