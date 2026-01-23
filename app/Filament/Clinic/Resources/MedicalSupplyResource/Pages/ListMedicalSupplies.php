<?php

namespace App\Filament\Clinic\Resources\MedicalSupplyResource\Pages;

use App\Filament\Clinic\Resources\MedicalSupplyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMedicalSupplies extends ListRecords
{
    protected static string $resource = MedicalSupplyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
