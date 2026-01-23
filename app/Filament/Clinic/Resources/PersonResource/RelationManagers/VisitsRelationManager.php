<?php

namespace App\Filament\Clinic\Resources\PersonResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VisitsRelationManager extends RelationManager
{
    protected static string $relationship = 'visits';

    protected static ?string $title = 'Medical History Timeline';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('visit_type')
                    ->options([
                        'ILLNESS' => 'Illness',
                        'INJURY' => 'Injury',
                        'FOLLOW_UP' => 'Follow-up',
                        'SCREENING' => 'Screening',
                        'OTHER' => 'Other',
                    ])
                    ->required(),
                Forms\Components\DateTimePicker::make('arrival_at')
                    ->label('Arrival Time')
                    ->required()
                    ->default(now()),
                Forms\Components\DateTimePicker::make('departure_at')
                    ->label('Departure Time'),
                Forms\Components\Select::make('triage_level')
                    ->options([
                        'LOW' => 'Low',
                        'MEDIUM' => 'Medium',
                        'HIGH' => 'High',
                        'EMERGENCY' => 'Emergency',
                    ])
                    ->default('LOW'),
                Forms\Components\Textarea::make('chief_complaint')
                    ->label('Chief Complaint')
                    ->required()
                    ->rows(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('arrival_at')
            ->columns([
                Tables\Columns\TextColumn::make('arrival_at')
                    ->label('Date & Time')
                    ->dateTime('M j, Y g:i A')
                    ->sortable(),
                Tables\Columns\TextColumn::make('visit_type')
                    ->label('Type')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('triage_level')
                    ->label('Triage')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'LOW' => 'success',
                        'MEDIUM' => 'warning',
                        'HIGH' => 'danger',
                        'EMERGENCY' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('chief_complaint')
                    ->label('Chief Complaint')
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->chief_complaint),
                Tables\Columns\TextColumn::make('disposition')
                    ->label('Disposition')
                    ->badge()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('departure_at')
                    ->label('Departure')
                    ->dateTime('g:i A')
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('visit_type')
                    ->options([
                        'ILLNESS' => 'Illness',
                        'INJURY' => 'Injury',
                        'FOLLOW_UP' => 'Follow-up',
                        'SCREENING' => 'Screening',
                        'OTHER' => 'Other',
                    ]),
                Tables\Filters\Filter::make('active_visits')
                    ->label('Active Visits Only')
                    ->query(fn ($query) => $query->whereNull('departure_at')),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->defaultSort('arrival_at', 'desc');
    }
}
