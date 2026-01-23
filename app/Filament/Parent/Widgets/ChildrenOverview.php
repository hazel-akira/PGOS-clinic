<?php

namespace App\Filament\Parent\Widgets;

use App\Models\Person;
use App\Models\Patient;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class ChildrenOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $user = Auth::user();
        
        // Find children linked to this parent via guardian email
        $children = $this->getParentChildren();
        $childrenCount = $children->count();
        
        // Count recent visits (last 30 days)
        $recentVisitsCount = 0;
        $activeVisitsCount = 0;
        
        foreach ($children as $child) {
            // Find corresponding person record
            $person = Person::where('adm_or_staff_no', $child->student_id)->first();
            if ($person) {
                $recentVisitsCount += $person->visits()
                    ->where('arrival_at', '>=', now()->subDays(30))
                    ->count();
                    
                $activeVisitsCount += $person->visits()
                    ->whereNull('departure_at')
                    ->count();
            }
        }

        return [
            Stat::make('My Children', $childrenCount)
                ->description('Registered students')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, 5]),
                
            Stat::make('Clinic Visits (30 days)', $recentVisitsCount)
                ->description('Medical visits this month')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('primary')
                ->chart([3, 5, 2, 7, 4, 6, 3]),
                
            Stat::make('Active Visits', $activeVisitsCount)
                ->description($activeVisitsCount > 0 ? 'Currently at clinic' : 'No active visits')
                ->descriptionIcon('heroicon-m-heart')
                ->color($activeVisitsCount > 0 ? 'warning' : 'gray')
                ->chart([0, 0, 1, 0, 0, 0, $activeVisitsCount]),
        ];
    }

    protected function getParentChildren()
    {
        $user = Auth::user();
        
        // Get children where guardian email matches user email
        return Patient::where('type', 'student')
            ->where('guardian_email', $user->email)
            ->where('is_active', true)
            ->get();
    }
}
