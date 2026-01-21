<?php

namespace App\Filament\Clinic\Resources\IncidentResource\Pages;

use App\Filament\Clinic\Resources\IncidentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewIncident extends ViewRecord
{
    protected static string $resource = IncidentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
