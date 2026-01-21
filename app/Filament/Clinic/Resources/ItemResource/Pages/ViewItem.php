<?php

namespace App\Filament\Clinic\Resources\ItemResource\Pages;

use App\Filament\Clinic\Resources\ItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewItem extends ViewRecord
{
    protected static string $resource = ItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
