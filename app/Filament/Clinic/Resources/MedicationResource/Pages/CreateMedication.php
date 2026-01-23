<?php

namespace App\Filament\Clinic\Resources\MedicationResource\Pages;

use App\Filament\Clinic\Resources\MedicationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMedication extends CreateRecord
{
    protected static string $resource = MedicationResource::class;

    protected function getRedirectUrl(): string
    {
        return static::$resource::getUrl('index');
    }
}
