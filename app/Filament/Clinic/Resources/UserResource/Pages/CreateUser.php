<?php

namespace App\Filament\Clinic\Resources\UserResource\Pages;

use App\Filament\Clinic\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Email verification is handled by the form field default
        return $data;
    }
}
