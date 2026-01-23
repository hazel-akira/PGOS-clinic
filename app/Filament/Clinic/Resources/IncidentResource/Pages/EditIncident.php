<?php

namespace App\Filament\Clinic\Resources\IncidentResource\Pages;

use App\Filament\Clinic\Resources\IncidentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditIncident extends EditRecord
{
    protected static string $resource = IncidentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->visible(fn () => Auth::check() && Auth::user() && Auth::user()->hasRole('admin')),
        ];
    }
}
