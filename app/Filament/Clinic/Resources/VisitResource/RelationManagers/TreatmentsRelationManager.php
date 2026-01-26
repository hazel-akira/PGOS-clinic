<?php

namespace App\Filament\Clinic\Resources\VisitResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TreatmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'treatments';

    protected static ?string $title = 'Treatments Given';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('treatment_type')
                    ->label('Treatment Type')
                    ->options([
                        'MEDICATION' => 'Medication',
                        'PROCEDURE' => 'Procedure',
                        'BANDAGE' => 'Bandage/Dressing',
                        'ICE_PACK' => 'Ice Pack',
                        'REST' => 'Rest',
                        'REFERRAL' => 'Referral',
                        'OTHER' => 'Other',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->required()
                    ->rows(3)
                    ->placeholder('Describe the treatment given'),
                Forms\Components\Textarea::make('instructions')
                    ->label('Instructions')
                    ->rows(2)
                    ->placeholder('Any follow-up instructions for the patient'),
                Forms\Components\DateTimePicker::make('performed_at')
                    ->label('Performed At')
                    ->default(now())
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('treatment_type')
            ->columns([
                Tables\Columns\TextColumn::make('treatment_type')
                    ->label('Type')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->description),
                Tables\Columns\TextColumn::make('instructions')
                    ->label('Instructions')
                    ->limit(30)
                    ->toggleable()
                    ->tooltip(fn ($record) => $record->instructions),
                Tables\Columns\TextColumn::make('performed_at')
                    ->label('Performed At')
                    ->dateTime('g:i A')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('treatment_type')
                    ->options([
                        'MEDICATION' => 'Medication',
                        'PROCEDURE' => 'Procedure',
                        'BANDAGE' => 'Bandage/Dressing',
                        'ICE_PACK' => 'Ice Pack',
                        'REST' => 'Rest',
                        'REFERRAL' => 'Referral',
                        'OTHER' => 'Other',
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
            ->defaultSort('performed_at', 'desc');
    }
}
