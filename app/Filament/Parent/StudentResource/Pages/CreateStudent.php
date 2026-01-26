<?php

namespace App\Filament\Parent\StudentResource\Pages;

use App\Filament\Parent\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;
}
