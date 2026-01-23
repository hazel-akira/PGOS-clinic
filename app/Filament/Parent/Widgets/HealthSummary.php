<?php

namespace App\Filament\Parent\Widgets;

use App\Models\Person;
use App\Models\Patient;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class HealthSummary extends Widget
{
    protected static ?int $sort = 3;
    protected static string $view = 'filament.parent.widgets.health-summary';
    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        $user = Auth::user();
        
        // Get children linked to this parent
        $children = Patient::where('type', 'student')
            ->where('guardian_email', $user->email)
            ->where('is_active', true)
            ->get();
            
        $childrenWithHealth = [];
        
        foreach ($children as $child) {
            // Find corresponding person record
            $person = Person::where('adm_or_staff_no', $child->student_id)->first();
            
            $childData = [
                'patient' => $child,
                'person' => $person,
                'recent_visit' => null,
                'visit_count' => 0,
            ];
            
            if ($person) {
                $childData['recent_visit'] = $person->visits()
                    ->latest('arrival_at')
                    ->first();
                    
                $childData['visit_count'] = $person->visits()
                    ->where('arrival_at', '>=', now()->subDays(365))
                    ->count();
            }
            
            $childrenWithHealth[] = $childData;
        }

        return [
            'children' => $childrenWithHealth,
        ];
    }
}
