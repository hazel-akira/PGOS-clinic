<?php

namespace App\Filament\Clinic\Resources\VisitResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DiagnosesRelationManager extends RelationManager
{
    protected static string $relationship = 'diagnoses';

    protected static ?string $title = 'Diagnoses';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('diagnosis_name')
                    ->label('Diagnosis Name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g., Common Cold, Sprained Ankle'),
                Forms\Components\TextInput::make('diagnosis_code')
                    ->label('ICD-10 Code (Optional)')
                    ->maxLength(50)
                    ->placeholder('e.g., J00'),
                Forms\Components\Select::make('diagnosis_type')
                    ->label('Type')
                    ->options([
                        'PRIMARY' => 'Primary',
                        'SECONDARY' => 'Secondary',
                        'DIFFERENTIAL' => 'Differential',
                        'RULED_OUT' => 'Ruled Out',
                    ])
                    ->default('PRIMARY')
                    ->required(),
                Forms\Components\Textarea::make('notes')
                    ->label('Notes')
                    ->rows(3)
                    ->placeholder('Additional notes about the diagnosis'),
                Forms\Components\DateTimePicker::make('diagnosed_at')
                    ->label('Diagnosed At')
                    ->default(now())
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('diagnosis_name')
            ->columns([
                Tables\Columns\TextColumn::make('diagnosis_name')
                    ->label('Diagnosis')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('diagnosis_code')
                    ->label('ICD-10 Code')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('diagnosis_type')
                    ->label('Type')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('notes')
                    ->label('Notes')
                    ->limit(30)
                    ->toggleable()
                    ->tooltip(fn ($record) => $record->notes),
                Tables\Columns\TextColumn::make('diagnosed_at')
                    ->label('Diagnosed At')
                    ->dateTime('g:i A')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('diagnosis_type')
                    ->options([
                        'PRIMARY' => 'Primary',
                        'SECONDARY' => 'Secondary',
                        'DIFFERENTIAL' => 'Differential',
                        'RULED_OUT' => 'Ruled Out',
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
            ->defaultSort('diagnosed_at', 'desc');
    }
}
