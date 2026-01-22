<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ClinicStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Students Visited Today', 42)
                ->description('Daily student visits')
                ->icon('heroicon-o-academic-cap')
                ->color('success'),

            Stat::make('Staff Visited Today', 11)
                ->description('Daily staff visits')
                ->icon('heroicon-o-user-group')
                ->color('info'),

            Stat::make('Current Patients', 6)
                ->description('Still in clinic')
                ->icon('heroicon-o-heart')
                ->color('warning'),

            Stat::make('Low Stock Items', 3)
                ->description('Needs attention')
                ->icon('heroicon-o-exclamation-triangle')
                ->color('danger'),
        ];
    }
}
