<?php

namespace App\Filament\Parent\Pages;

use App\Models\Person;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class ParentDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    
    protected static ?string $navigationLabel = 'Dashboard';
    
    protected static ?string $title = 'My Children';
    
    protected static ?string $slug = 'dashboard';
    
    protected static string $view = 'filament.parent.pages.parent-dashboard';
    
    protected static ?int $navigationSort = 1;

    public function getStudents()
    {
        $user = Auth::user();
        if (!$user->guardian) {
            return collect([]);
        }

        return Person::whereHas('guardianLinks', function ($query) use ($user) {
            $query->where('guardian_id', $user->guardian->id);
        })
        ->where('person_type', 'STUDENT')
        ->where('status', 'ACTIVE')
        ->with([
            'school', 
            'enrolment',
            'visits' => function ($query) {
                $query->latest()->limit(10)->with(['vitals', 'diagnoses', 'treatments', 'prescriptions.item']);
            },
            'allergies' => function ($query) {
                $query->orderBy('severity', 'desc')->orderBy('recorded_at', 'desc');
            },
            'chronicConditions' => function ($query) {
                $query->orderBy('active', 'desc')->orderBy('recorded_at', 'desc');
            },
            'consents' => function ($query) {
                $query->where(function ($q) {
                    $q->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
                })->orderBy('given_at', 'desc');
            },
            'medicalProfile'
        ])
        ->get();
    }
}
