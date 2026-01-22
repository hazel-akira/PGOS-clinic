<?php

namespace App\Filament\Clinic\Resources\VisitResource\Pages;

use App\Filament\Clinic\Resources\VisitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVisit extends EditRecord
{
    protected static string $resource = VisitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->visible(fn () => auth()->user()->hasRole('admin')),
        ];
    }
}
