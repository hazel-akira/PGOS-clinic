<?php

namespace App\Filament\Clinic\Resources\ClinicInventoryResource\Pages;

use App\Filament\Clinic\Resources\ClinicInventoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClinicInventories extends ListRecords
{
    protected static string $resource = ClinicInventoryResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
