<?php

namespace App\Filament\Clinic\Resources\MedicalSupplyResource\Pages;

use App\Filament\Clinic\Resources\MedicalSupplyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMedicalSupply extends EditRecord
{
    protected static string $resource = MedicalSupplyResource::class;

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
                ->visible(function () {
                    /** @var User|null $user */
                    $user = filament()->auth()->user();

                    return $user?->hasRole('admin');
                }),

            Actions\ForceDeleteAction::make()
                ->color('danger')
                ->requiresConfirmation()
                ->visible(function () {
                    /** @var User|null $user */
                    $user = filament()->auth()->user();

                    return $user?->hasRole('admin');
                }),
        ];
    }
}
