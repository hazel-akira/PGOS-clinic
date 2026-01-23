<?php

namespace App\Filament\Parent\Resources\VisitResource\Pages;

use App\Filament\Parent\Resources\VisitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVisits extends ListRecords
{
    protected static string $resource = VisitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No actions - parents cannot create visits
        ];
    }
}
