<?php

namespace App\Filament\Admin\Resources\InventoryResource\Pages;

use App\Filament\Admin\Resources\InventoryResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditInventory extends EditRecord
{
    protected static string $resource = InventoryResource::class;

    protected ?array $batchData = null;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Store batch info temporarily for adding new stock
        $this->batchData = [
            'batch_no'   => $data['batch_number'] ?? null,
            'expiry_date'=> $data['expiry_date'] ?? null,
            'qty_on_hand'=> (int) ($data['quantity_in'] ?? 0),
        ];

        unset($data['batch_number'], $data['expiry_date'], $data['quantity_in']);

        // Set updated_by
        if (Auth::check()) {
            $data['updated_by'] = Auth::id();
        }

        return $data;
    }

    protected function afterSave(): void
    {
        if ($this->record && $this->batchData && $this->batchData['qty_on_hand'] > 0) {
            // Create new batch for additional stock
            $batch = $this->record->batches()->create(array_filter([
                'batch_no'   => $this->batchData['batch_no'],
                'expiry_date'=> $this->batchData['expiry_date'],
                'qty_on_hand'=> $this->batchData['qty_on_hand'],
            ]));

            // Record stock-in transaction
            if ($batch) {
                $batch->transactions()->create([
                    'type'         => 'in',
                    'quantity'     => $this->batchData['qty_on_hand'],
                    'reason'       => 'Stock added via edit',
                    'performed_by' => Auth::id(),
                ]);
            }

            // Update inventory's available stock
            $this->record->recalculateStock();
        }
    }
}
