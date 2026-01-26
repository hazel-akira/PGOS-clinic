<?php

namespace App\Filament\Clinic\Widgets;

use App\Models\Incident;
use Filament\Widgets\Widget;

class EmergencyAlerts extends Widget
{
    protected static string $view = 'filament.widgets.emergency-alerts';

    public function getViewData(): array
    {
        $emergencyIncidents = Incident::where('is_emergency', true)

            ->with(['person', 'school', 'reportedBy'])
            ->orderBy('occurred_at', 'desc')
            ->limit(5)
            ->get();

        return [
            'emergencyIncidents' => $emergencyIncidents,
            'count' => $emergencyIncidents->count(),
        ];
    }
}
