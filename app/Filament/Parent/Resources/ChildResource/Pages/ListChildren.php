<?php

namespace App\Filament\Parent\Resources\ChildResource\Pages;

use App\Filament\Parent\Resources\ChildResource;
use Filament\Resources\Pages\ListRecords;

class ListChildren extends ListRecords
{
    protected static string $resource = ChildResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No actions - parents cannot create children records
        ];
    }
}
