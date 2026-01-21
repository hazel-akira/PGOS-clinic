<?php

namespace App\Filament\Clinic\Resources\VisitResource\Pages;

use App\Filament\Clinic\Resources\VisitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVisits extends ListRecords
{
    protected static string $resource = VisitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Visit'),
        ];
    }
}
