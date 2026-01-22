<?php

namespace App\Filament\Clinic\Pages;

use Filament\Pages\Dashboard;
use App\Filament\Clinic\Widgets\DashboardStats;

class ClinicDashboard extends Dashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-heart';
    protected static ?string $title = 'Clinic Dashboard';
    protected static ?string $slug = 'dashboard';

    public function getWidgets(): array
    {
        return [
            DashboardStats::class,
            \App\Filament\Widgets\EmergencyAlerts::class,
            \App\Filament\Widgets\QuickClinicActions::class,
        ];
    }
}
