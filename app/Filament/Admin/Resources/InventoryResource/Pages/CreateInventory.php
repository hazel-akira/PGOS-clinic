<?php

namespace App\Filament\Admin\Resources\InventoryResource\Pages;

use App\Filament\Admin\Resources\InventoryResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateInventory extends CreateRecord
{
    protected static string $resource = InventoryResource::class;

    /**
     * Redirect to inventory list after creation.
     */
    protected function getRedirectUrl(): string
    {
        return static::$resource::getUrl('index');
    }

    /**
     * Mutate form data before creating the inventory.
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Remove any old batch fields if present
        unset($data['batch_number'], $data['expiry_date'], $data['quantity_in']);

        // Set the user who created the inventory
        if (Auth::check()) {
            $data['created_by'] = Auth::id();
        }

        return $data;
    }
}
