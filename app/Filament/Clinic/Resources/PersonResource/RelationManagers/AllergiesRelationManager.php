<?php

namespace App\Filament\Clinic\Resources\PersonResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AllergiesRelationManager extends RelationManager
{
    protected static string $relationship = 'allergies';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('allergen')
                    ->label('Allergen')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g., Penicillin, Peanuts, Latex'),
                Forms\Components\Textarea::make('reaction')
                    ->label('Reaction')
                    ->rows(2)
                    ->placeholder('Describe the reaction when exposed'),
                Forms\Components\Select::make('severity')
                    ->label('Severity')
                    ->options([
                        'MILD' => 'Mild',
                        'MODERATE' => 'Moderate',
                        'SEVERE' => 'Severe',
                    ])
                    ->required()
                    ->default('MILD'),
                Forms\Components\DateTimePicker::make('recorded_at')
                    ->label('Recorded At')
                    ->default(now())
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('allergen')
            ->columns([
                Tables\Columns\TextColumn::make('allergen')
                    ->label('Allergen')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reaction')
                    ->label('Reaction')
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->reaction),
                Tables\Columns\TextColumn::make('severity')
                    ->label('Severity')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'MILD' => 'success',
                        'MODERATE' => 'warning',
                        'SEVERE' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('recorded_at')
                    ->label('Recorded')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('severity')
                    ->options([
                        'MILD' => 'Mild',
                        'MODERATE' => 'Moderate',
                        'SEVERE' => 'Severe',
                    ]),
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
