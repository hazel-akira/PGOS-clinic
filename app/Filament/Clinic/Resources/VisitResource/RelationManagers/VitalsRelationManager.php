<?php

namespace App\Filament\Clinic\Resources\VisitResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VitalsRelationManager extends RelationManager
{
    protected static string $relationship = 'vitals';

    protected static ?string $title = 'Vital Signs';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\TextInput::make('temperature')
                            ->label('Temperature (°C)')
                            ->numeric()
                            ->step(0.1)
                            ->suffix('°C')
                            ->minValue(30)
                            ->maxValue(45),
                        Forms\Components\TextInput::make('blood_pressure_systolic')
                            ->label('BP Systolic')
                            ->numeric()
                            ->minValue(50)
                            ->maxValue(250),
                        Forms\Components\TextInput::make('blood_pressure_diastolic')
                            ->label('BP Diastolic')
                            ->numeric()
                            ->minValue(30)
                            ->maxValue(150),
                        Forms\Components\TextInput::make('pulse_rate')
                            ->label('Pulse Rate (bpm)')
                            ->numeric()
                            ->suffix('bpm')
                            ->minValue(30)
                            ->maxValue(200),
                        Forms\Components\TextInput::make('respiratory_rate')
                            ->label('Respiratory Rate')
                            ->numeric()
                            ->suffix('per min')
                            ->minValue(10)
                            ->maxValue(50),
                        Forms\Components\TextInput::make('weight')
                            ->label('Weight (kg)')
                            ->numeric()
                            ->step(0.1)
                            ->suffix('kg')
                            ->minValue(0)
                            ->maxValue(300),
                        Forms\Components\TextInput::make('oxygen_saturation')
                            ->label('O2 Saturation (%)')
                            ->numeric()
                            ->suffix('%')
                            ->minValue(0)
                            ->maxValue(100),
                    ]),
                Forms\Components\DateTimePicker::make('recorded_at')
                    ->label('Recorded At')
                    ->default(now())
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('recorded_at')
            ->columns([
                Tables\Columns\TextColumn::make('temperature')
                    ->label('Temp')
                    ->formatStateUsing(fn ($state) => $state ? "{$state}°C" : '-')
                    ->sortable(),
                Tables\Columns\TextColumn::make('blood_pressure')
                    ->label('Blood Pressure')
                    ->formatStateUsing(fn ($record) => 
                        $record->blood_pressure_systolic && $record->blood_pressure_diastolic
                            ? "{$record->blood_pressure_systolic}/{$record->blood_pressure_diastolic}"
                            : '-'
                    ),
                Tables\Columns\TextColumn::make('pulse_rate')
                    ->label('Pulse')
                    ->formatStateUsing(fn ($state) => $state ? "{$state} bpm" : '-')
                    ->sortable(),
                Tables\Columns\TextColumn::make('respiratory_rate')
                    ->label('Resp. Rate')
                    ->formatStateUsing(fn ($state) => $state ? "{$state}/min" : '-'),
                Tables\Columns\TextColumn::make('weight')
                    ->label('Weight')
                    ->formatStateUsing(fn ($state) => $state ? "{$state} kg" : '-')
                    ->sortable(),
                Tables\Columns\TextColumn::make('oxygen_saturation')
                    ->label('O2 Sat')
                    ->formatStateUsing(fn ($state) => $state ? "{$state}%" : '-'),
                Tables\Columns\TextColumn::make('recorded_at')
                    ->label('Recorded')
                    ->dateTime('g:i A')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('recorded_at', 'desc');
    }
}
