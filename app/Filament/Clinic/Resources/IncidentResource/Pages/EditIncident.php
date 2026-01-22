<?php

namespace App\Filament\Clinic\Resources\IncidentResource\Pages;

use App\Filament\Clinic\Resources\IncidentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIncident extends EditRecord
{
    protected static string $resource = IncidentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->visible(fn () => auth()->user()->hasRole('admin')),
        ];
    }
}
