<?php

namespace App\Filament\Parent\Resources\ChildResource\Pages;

use App\Filament\Parent\Resources\ChildResource;
use App\Models\Person;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Grid;

class ViewChild extends ViewRecord
{
    protected static string $resource = ChildResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Personal Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('full_name')
                                    ->label('Full Name')
                                    ->icon('heroicon-m-user')
                                    ->weight('bold')
                                    ->size('lg'),
                                    
                                TextEntry::make('student_id')
                                    ->label('Student ID')
                                    ->icon('heroicon-m-identification')
                                    ->copyable(),
                            ]),
                            
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('date_of_birth')
                                    ->label('Date of Birth')
                                    ->date('M d, Y')
                                    ->icon('heroicon-m-cake'),
                                    
                                TextEntry::make('date_of_birth')
                                    ->label('Age')
                                    ->formatStateUsing(fn ($state) => $state ? $state->age . ' years old' : 'N/A')
                                    ->icon('heroicon-m-calendar'),
                                    
                                TextEntry::make('gender')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'male' => 'info',
                                        'female' => 'success',
                                        default => 'gray',
                                    }),
                            ]),
                            
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('class')
                                    ->label('Class')
                                    ->badge()
                                    ->color('primary')
                                    ->icon('heroicon-m-academic-cap'),
                                    
                                TextEntry::make('is_active')
                                    ->label('Status')
                                    ->badge()
                                    ->formatStateUsing(fn (bool $state): string => $state ? 'Active' : 'Inactive')
                                    ->color(fn (bool $state): string => $state ? 'success' : 'danger'),
                            ]),
                    ])
                    ->icon('heroicon-o-user')
                    ->collapsible(),

                Section::make('Contact Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('phone')
                                    ->label('Phone')
                                    ->icon('heroicon-m-phone')
                                    ->placeholder('Not provided'),
                                    
                                TextEntry::make('email')
                                    ->label('Email')
                                    ->icon('heroicon-m-envelope')
                                    ->placeholder('Not provided')
                                    ->copyable(),
                            ]),
                    ])
                    ->icon('heroicon-o-phone')
                    ->collapsible(),

                Section::make('Guardian Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('guardian_name')
                                    ->label('Guardian Name')
                                    ->icon('heroicon-m-user-circle'),
                                    
                                TextEntry::make('guardian_relationship')
                                    ->label('Relationship')
                                    ->badge()
                                    ->color('primary'),
                            ]),
                            
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('guardian_phone')
                                    ->label('Guardian Phone')
                                    ->icon('heroicon-m-phone')
                                    ->copyable(),
                                    
                                TextEntry::make('guardian_email')
                                    ->label('Guardian Email')
                                    ->icon('heroicon-m-envelope')
                                    ->copyable(),
                            ]),
                    ])
                    ->icon('heroicon-o-user-circle')
                    ->collapsible(),

                Section::make('Medical Information')
                    ->schema([
                        TextEntry::make('allergies')
                            ->label('Known Allergies')
                            ->columnSpanFull()
                            ->placeholder('No known allergies')
                            ->icon('heroicon-m-exclamation-triangle')
                            ->color('danger'),
                            
                        TextEntry::make('chronic_conditions')
                            ->label('Chronic Conditions')
                            ->columnSpanFull()
                            ->placeholder('No chronic conditions')
                            ->icon('heroicon-m-heart'),
                            
                        TextEntry::make('current_medications')
                            ->label('Current Medications')
                            ->columnSpanFull()
                            ->placeholder('No current medications')
                            ->icon('heroicon-m-beaker'),
                            
                        TextEntry::make('medical_history')
                            ->label('Medical History')
                            ->columnSpanFull()
                            ->placeholder('No medical history recorded')
                            ->icon('heroicon-m-clipboard-document-list'),
                    ])
                    ->icon('heroicon-o-heart')
                    ->collapsible(),

                Section::make('Consent & Emergency Care')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('consent_first_aid')
                                    ->label('First Aid Consent')
                                    ->badge()
                                    ->formatStateUsing(fn (bool $state): string => $state ? 'Given' : 'Not Given')
                                    ->color(fn (bool $state): string => $state ? 'success' : 'danger')
                                    ->icon(fn (bool $state): string => $state ? 'heroicon-m-check-circle' : 'heroicon-m-x-circle'),
                                    
                                TextEntry::make('consent_emergency_care')
                                    ->label('Emergency Care Consent')
                                    ->badge()
                                    ->formatStateUsing(fn (bool $state): string => $state ? 'Given' : 'Not Given')
                                    ->color(fn (bool $state): string => $state ? 'success' : 'danger')
                                    ->icon(fn (bool $state): string => $state ? 'heroicon-m-check-circle' : 'heroicon-m-x-circle'),
                            ]),
                            
                        TextEntry::make('consent_date')
                            ->label('Consent Date')
                            ->date('M d, Y')
                            ->icon('heroicon-m-calendar')
                            ->placeholder('Not recorded'),
                    ])
                    ->icon('heroicon-o-shield-check')
                    ->collapsible(),

                Section::make('Visit History')
                    ->schema([
                        TextEntry::make('visit_summary')
                            ->label('')
                            ->formatStateUsing(function ($record) {
                                $person = Person::where('adm_or_staff_no', $record->student_id)->first();
                                if (!$person) {
                                    return 'No clinic visits recorded.';
                                }
                                
                                $totalVisits = $person->visits()->count();
                                $recentVisits = $person->visits()
                                    ->where('arrival_at', '>=', now()->subDays(365))
                                    ->count();
                                $lastVisit = $person->visits()->latest('arrival_at')->first();
                                
                                $summary = "Total clinic visits: {$totalVisits}\n";
                                $summary .= "Visits in past year: {$recentVisits}\n";
                                if ($lastVisit) {
                                    $summary .= "Last visit: {$lastVisit->arrival_at->format('M d, Y')} - {$lastVisit->chief_complaint}";
                                }
                                
                                return $summary;
                            })
                            ->columnSpanFull(),
                    ])
                    ->icon('heroicon-o-clipboard-document-list')
                    ->collapsible(),
            ]);
    }
}
