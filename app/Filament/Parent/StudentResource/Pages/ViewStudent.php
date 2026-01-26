<?php

namespace App\Filament\Parent\StudentResource\Pages;

use App\Filament\Parent\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewStudent extends ViewRecord
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Parents have read-only access - cannot edit student information
        ];
    }
}
