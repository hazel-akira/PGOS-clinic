<?php

namespace App\Filament\Admin\Resources\InventoryResource\Pages;

use App\Filament\Admin\Resources\InventoryResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateInventory extends CreateRecord
{
    protected static string $resource = InventoryResource::class;
    
    protected function getRedirectUrl(): string
    {
        return static::$resource::getUrl('index');
    }

    protected ?array $batchData = null;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Store batch info temporarily to create after Inventory
        $this->batchData = [
            'batch_no'   => $data['batch_number'] ?? null,
            'expiry_date'=> $data['expiry_date'] ?? null,
            'qty_on_hand'=> (int) ($data['quantity_in'] ?? 0),
        ];

        // Remove batch fields from Inventory
        unset($data['batch_number'], $data['expiry_date'], $data['quantity_in']);

        // Set created_by
        if (Auth::check()) {
            $data['created_by'] = Auth::id();
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        if ($this->record && $this->batchData) {
            // Create initial stock batch
            $batch = $this->record->batches()->create(array_filter([
                'batch_no'   => $this->batchData['batch_no'],
                'expiry_date'=> $this->batchData['expiry_date'],
                'qty_on_hand'=> $this->batchData['qty_on_hand'],
            ]));

            // Record initial stock transaction
            if ($batch && $this->batchData['qty_on_hand'] > 0) {
                $batch->transactions()->create([
                    'type'         => 'in',
                    'quantity'     => $this->batchData['qty_on_hand'],
                    'reason'       => 'Initial stock',
                    'performed_by' => Auth::id(),
                ]);
            }

            // Update inventory's available stock
            $this->record->recalculateStock();
        }
    }
}
