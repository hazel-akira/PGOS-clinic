<?php

namespace App\Filament\Parent\Resources\VisitResource\Pages;

use App\Filament\Parent\Resources\VisitResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\RepeatableEntry;

class ViewVisit extends ViewRecord
{
    protected static string $resource = VisitResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Visit Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('person.full_name')
                                    ->label('Child Name')
                                    ->icon('heroicon-m-user')
                                    ->weight('bold')
                                    ->size('lg'),
                                    
                                TextEntry::make('visit_type')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'ILLNESS' => 'danger',
                                        'INJURY' => 'warning',
                                        'SCREENING' => 'info',
                                        'FOLLOW_UP' => 'success',
                                        default => 'gray',
                                    }),
                            ]),
                            
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('arrival_at')
                                    ->label('Arrival Time')
                                    ->dateTime('M d, Y h:i A')
                                    ->icon('heroicon-m-clock'),
                                    
                                TextEntry::make('departure_at')
                                    ->label('Departure Time')
                                    ->dateTime('M d, Y h:i A')
                                    ->placeholder('Still at clinic')
                                    ->icon('heroicon-m-clock'),
                            ]),
                            
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('triage_level')
                                    ->label('Priority Level')
                                    ->badge()
                                    ->color(fn (?string $state): string => match ($state) {
                                        'EMERGENCY' => 'danger',
                                        'HIGH' => 'warning',
                                        'MEDIUM' => 'primary',
                                        'LOW' => 'success',
                                        default => 'gray',
                                    }),
                                    
                                TextEntry::make('disposition')
                                    ->label('Outcome')
                                    ->badge()
                                    ->color('success')
                                    ->formatStateUsing(fn (?string $state): string => $state ? str_replace('_', ' ', $state) : 'In Progress'),
                            ]),
                    ])
                    ->icon('heroicon-o-information-circle')
                    ->collapsible(),

                Section::make('Medical Details')
                    ->schema([
                        TextEntry::make('chief_complaint')
                            ->label('Chief Complaint')
                            ->columnSpanFull(),
                            
                        TextEntry::make('history_notes')
                            ->label('History & Symptoms')
                            ->columnSpanFull()
                            ->placeholder('No additional history recorded'),
                            
                        TextEntry::make('assessment_notes')
                            ->label('Medical Assessment')
                            ->columnSpanFull()
                            ->placeholder('Assessment pending'),
                    ])
                    ->icon('heroicon-o-clipboard-document-list')
                    ->collapsible(),

                Section::make('Vital Signs')
                    ->schema([
                        RepeatableEntry::make('vitals')
                            ->label('')
                            ->schema([
                                Grid::make(4)
                                    ->schema([
                                        TextEntry::make('temperature')
                                            ->label('Temperature (°C)')
                                            ->icon('heroicon-m-fire')
                                            ->suffix('°C'),
                                            
                                        TextEntry::make('pulse_rate')
                                            ->label('Pulse Rate (bpm)')
                                            ->icon('heroicon-m-heart')
                                            ->suffix(' bpm'),
                                            
                                        TextEntry::make('blood_pressure_systolic')
                                            ->label('Blood Pressure')
                                            ->icon('heroicon-m-heart')
                                            ->formatStateUsing(fn ($record): string => 
                                                ($record->blood_pressure_systolic && $record->blood_pressure_diastolic) 
                                                    ? "{$record->blood_pressure_systolic}/{$record->blood_pressure_diastolic} mmHg" 
                                                    : 'N/A'
                                            ),
                                            
                                        TextEntry::make('oxygen_saturation')
                                            ->label('O₂ Saturation')
                                            ->icon('heroicon-m-beaker')
                                            ->suffix('%'),
                                    ]),
                                    
                                Grid::make(3)
                                    ->schema([
                                        TextEntry::make('respiratory_rate')
                                            ->label('Respiratory Rate')
                                            ->suffix(' /min'),
                                            
                                        TextEntry::make('weight')
                                            ->label('Weight (kg)')
                                            ->suffix(' kg'),
                                            
                                        TextEntry::make('recorded_at')
                                            ->label('Recorded At')
                                            ->dateTime('M d, Y h:i A'),
                                    ]),
                            ])
                            ->columnSpanFull(),
                    ])
                    ->icon('heroicon-o-heart')
                    ->collapsible()
                    ->visible(fn ($record) => $record->vitals->count() > 0),

                Section::make('Diagnoses')
                    ->schema([
                        RepeatableEntry::make('diagnoses')
                            ->label('')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextEntry::make('diagnosis_name')
                                            ->label('Diagnosis')
                                            ->weight('bold'),
                                            
                                        TextEntry::make('diagnosis_type')
                                            ->label('Type')
                                            ->badge(),
                                    ]),
                                    
                                TextEntry::make('notes')
                                    ->label('Notes')
                                    ->columnSpanFull()
                                    ->placeholder('No additional notes'),
                                    
                                TextEntry::make('diagnosed_at')
                                    ->label('Diagnosed At')
                                    ->dateTime('M d, Y h:i A'),
                            ])
                            ->columnSpanFull(),
                    ])
                    ->icon('heroicon-o-document-text')
                    ->collapsible()
                    ->visible(fn ($record) => $record->diagnoses->count() > 0),

                Section::make('Treatments')
                    ->schema([
                        RepeatableEntry::make('treatments')
                            ->label('')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextEntry::make('treatment_type')
                                            ->label('Treatment Type')
                                            ->badge()
                                            ->color('primary'),
                                            
                                        TextEntry::make('performed_at')
                                            ->label('Performed At')
                                            ->dateTime('M d, Y h:i A'),
                                    ]),
                                    
                                TextEntry::make('description')
                                    ->label('Description')
                                    ->columnSpanFull(),
                                    
                                TextEntry::make('instructions')
                                    ->label('Instructions')
                                    ->columnSpanFull()
                                    ->placeholder('No additional instructions'),
                            ])
                            ->columnSpanFull(),
                    ])
                    ->icon('heroicon-o-beaker')
                    ->collapsible()
                    ->visible(fn ($record) => $record->treatments->count() > 0),

                Section::make('Prescriptions')
                    ->schema([
                        RepeatableEntry::make('prescriptions')
                            ->label('')
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        TextEntry::make('item.name')
                                            ->label('Medication')
                                            ->weight('bold')
                                            ->icon('heroicon-m-beaker'),
                                            
                                        TextEntry::make('dose')
                                            ->label('Dose'),
                                            
                                        TextEntry::make('frequency')
                                            ->label('Frequency'),
                                    ]),
                                    
                                Grid::make(2)
                                    ->schema([
                                        TextEntry::make('duration')
                                            ->label('Duration'),
                                            
                                        TextEntry::make('instructions')
                                            ->label('Instructions')
                                            ->columnSpanFull()
                                            ->placeholder('No additional instructions'),
                                    ]),
                            ])
                            ->columnSpanFull(),
                    ])
                    ->icon('heroicon-o-beaker')
                    ->collapsible()
                    ->visible(fn ($record) => $record->prescriptions->count() > 0),
            ]);
    }
}
