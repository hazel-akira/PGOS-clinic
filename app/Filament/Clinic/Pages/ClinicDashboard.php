<?php

namespace App\Filament\Clinic\Pages;

use Filament\Pages\Dashboard;

class ClinicDashboard extends Dashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-heart';
    protected static ?string $title = 'Clinic Dashboard';

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\ClinicStats::class,
            \App\Filament\Widgets\EmergencyAlerts::class,
            \App\Filament\Widgets\QuickClinicActions::class,
        ];
    }
}
