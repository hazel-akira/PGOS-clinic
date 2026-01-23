<?php

namespace App\Filament\Parent\StudentResource\Pages;

use App\Filament\Parent\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Parents cannot create students - they can only view their linked children
        ];
    }
}
