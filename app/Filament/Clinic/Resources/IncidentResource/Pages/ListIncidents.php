<?php

namespace App\Filament\Clinic\Resources\IncidentResource\Pages;

use App\Filament\Clinic\Resources\IncidentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIncidents extends ListRecords
{
    protected static string $resource = IncidentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
