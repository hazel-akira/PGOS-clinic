<?php

namespace App\Filament\Admin\Widgets;

use App\Models\User;
use App\Models\Medication;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count()),
            Stat::make('Admins', User::where('role', 'admin')->count()),
            Stat::make('Medications', Medication::count()),
            Stat::make('Deleted Medications', Medication::onlyTrashed()->count()),
        ];
    }
}
