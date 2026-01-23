<?php

namespace App\Filament\Parent\Pages;

use Filament\Pages\Dashboard;
use App\Filament\Parent\Widgets\ChildrenOverview;
use App\Filament\Parent\Widgets\RecentVisits;
use App\Filament\Parent\Widgets\HealthSummary;

class ParentDashboard extends Dashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $title = 'Parent Dashboard';
    protected static ?string $slug = 'dashboard';

    public function getWidgets(): array
    {
        return [
            ChildrenOverview::class,
            RecentVisits::class,
            HealthSummary::class,
        ];
    }

    public function getColumns(): int | string | array
    {
        return [
            'sm' => 1,
            'md' => 2,
            'xl' => 3,
        ];
    }
}
