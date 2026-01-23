<?php

namespace App\Filament\Clinic\Resources\MedicationResource\Pages;

use App\Filament\Clinic\Resources\MedicationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMedication extends EditRecord
{
    protected static string $resource = MedicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\RestoreAction::make()
             ->color('success')
             ->icon('heroicon-o-arrow-path'),

            Actions\ForceDeleteAction::make()
            ->visible(fn ($record) => $record->trashed())
            ->requiresConfirmation()
            ->modalHeading('Permanently delete medication')
            ->modalDescription('This action cannot be undone. This medication will be permanently removed from the system.')
            ->modalSubmitActionLabel('Yes, delete permanently'),
        ];
    }
}
