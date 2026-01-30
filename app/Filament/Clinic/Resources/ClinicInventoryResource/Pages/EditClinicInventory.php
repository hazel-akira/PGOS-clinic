<?php

namespace App\Filament\Clinic\Resources\ClinicInventoryResource\Pages;

use App\Filament\Clinic\Resources\ClinicInventoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClinicInventory extends EditRecord
{
    protected static string $resource = ClinicInventoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
