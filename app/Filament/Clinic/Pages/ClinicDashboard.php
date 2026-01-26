<?php

namespace App\Filament\Clinic\Pages;

use App\Filament\Clinic\Widgets\DashboardStats;
use App\Filament\Clinic\Widgets\QuickClinicActions;
use \App\Filament\Clinic\Widgets\EmergencyAlerts;
use \App\Filament\Clinic\Widgets\ClinicStats;
use Filament\Pages\Dashboard;

class ClinicDashboard extends Dashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-heart';

    protected static ?string $title = 'Clinic Dashboard';

    protected static ?string $slug = 'dashboard';

    public function getWidgets(): array
    {
        return [
            ClinicStats::class,
            EmergencyAlerts::class,
            QuickClinicActions::class,
        ];
    }
}
