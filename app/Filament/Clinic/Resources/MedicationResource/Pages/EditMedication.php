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
            Actions\DeleteAction::make()
                ->visible(function () {
                    /** @var User|null $user */
                    $user = filament()->auth()->user();

                    return $user?->hasRole('admin');
                }),
            Actions\RestoreAction::make()
                ->color('success')
                ->icon('heroicon-o-arrow-path')
                ->visible(function () {
                    /** @var User|null $user */
                    $user = filament()->auth()->user();

                    return $user?->hasRole('admin');
                }),

            Actions\ForceDeleteAction::make()
                ->visible(fn ($record) => $record->trashed())
                ->visible(function () {
                    /** @var User|null $user */
                    $user = filament()->auth()->user();

                    return $user?->hasRole('admin');
                })
                ->requiresConfirmation()
                ->modalHeading('Permanently delete medication')
                ->modalDescription('This action cannot be undone. This medication will be permanently removed from the system.')
                ->modalSubmitActionLabel('Yes, delete permanently'),
        ];
    }
}
