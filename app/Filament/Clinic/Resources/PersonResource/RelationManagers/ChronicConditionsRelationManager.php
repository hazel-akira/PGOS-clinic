<?php

namespace App\Filament\Clinic\Resources\PersonResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ChronicConditionsRelationManager extends RelationManager
{
    protected static string $relationship = 'chronicConditions';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('condition')
                    ->label('Condition')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g., Asthma, Diabetes, Hypertension'),
                Forms\Components\Textarea::make('notes')
                    ->label('Notes')
                    ->rows(2)
                    ->placeholder('Additional details about the condition'),
                Forms\Components\Toggle::make('active')
                    ->label('Active')
                    ->default(true)
                    ->helperText('Mark as inactive if condition is resolved'),
                Forms\Components\DateTimePicker::make('recorded_at')
                    ->label('Recorded At')
                    ->default(now())
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('condition')
            ->columns([
                Tables\Columns\TextColumn::make('condition')
                    ->label('Condition')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('notes')
                    ->label('Notes')
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->notes),
                Tables\Columns\IconColumn::make('active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('recorded_at')
                    ->label('Recorded')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('active')
                    ->label('Active Status')
                    ->placeholder('All')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only'),
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
