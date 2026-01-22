<?php

namespace App\Filament\Clinic\Resources\PatientResource\Pages;

use App\Filament\Clinic\Resources\PatientResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ViewPatient extends ViewRecord
{
    protected static string $resource = PatientResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Patient Summary')
                ->schema([
                    Infolists\Components\TextEntry::make('full_name')
                        ->label('Name')
                        ->size(Infolists\Components\TextEntry\TextEntrySize::Large),

                    Infolists\Components\TextEntry::make('patient_type')
                        ->badge(),

                    Infolists\Components\TextEntry::make('status')
                        ->badge(),

                    Infolists\Components\TextEntry::make('external_id')
                        ->label('Admission / Staff No.')
                        ->visible(fn ($record) => in_array($record->patient_type, ['STUDENT', 'STAFF'])),

                ])
                ->columns(2),

            Infolists\Components\Section::make('Visit History')
                ->schema([
                    Infolists\Components\RepeatableEntry::make('visits')
                        ->schema([
                            Infolists\Components\TextEntry::make('visited_at')
                                ->dateTime()
                                ->label('Date'),

                            Infolists\Components\TextEntry::make('reason'),

                            Infolists\Components\TextEntry::make('outcome')
                                ->badge(),

                            Infolists\Components\TextEntry::make('notes')
                                ->columnSpanFull(),
                        ])
                        
                ])
                ->columns(3),

            Infolists\Components\Section::make('Contact Information')
                ->schema([
                    Infolists\Components\TextEntry::make('phone'),
                    Infolists\Components\TextEntry::make('email'),
                ])
                ->columns(2),

            Infolists\Components\Section::make('Clinic Activity')
                ->schema([
                    Infolists\Components\TextEntry::make('created_at')
                        ->label('First Seen')
                        ->dateTime(),

                    Infolists\Components\TextEntry::make('updated_at')
                        ->label('Last Updated')
                        ->dateTime(),
                ])
                ->columns(2),
        ]);
    }
}
