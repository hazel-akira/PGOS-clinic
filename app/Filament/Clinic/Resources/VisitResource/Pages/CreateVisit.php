<?php

namespace App\Filament\Clinic\Resources\VisitResource\Pages;

use App\Filament\Clinic\Resources\VisitResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVisit extends CreateRecord
{
    protected static string $resource = VisitResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by_user_id'] = auth()->id();
        
        return $data;
    }
}
