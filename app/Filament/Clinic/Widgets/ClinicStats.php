<?php

namespace App\Filament\Clinic\Widgets;

use App\Models\Person;
use App\Models\Visit;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ClinicStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $today = now()->startOfDay();

        return [
            Stat::make('Today\'s Visits', Visit::whereDate('arrival_at', today())->count())
                ->description('Visits today')
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color('success'),

            Stat::make('Active Patients', Person::where('status', 'ACTIVE')->count())
                ->description('Total active students & staff')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),

            Stat::make('Active Visits', Visit::whereNull('departure_at')->count())
                ->description('Currently in clinic')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Emergency Cases', Visit::where('triage_level', 'EMERGENCY')
                ->whereNull('departure_at')
                ->count())
                ->description('Requiring immediate attention')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),
        ];
    }
}
