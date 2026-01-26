<?php

namespace App\Filament\Clinic\Resources\PersonResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ConsentsRelationManager extends RelationManager
{
    protected static string $relationship = 'consents';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('consent_type')
                    ->label('Consent Type')
                    ->options([
                        'TREATMENT_GENERAL' => 'General Treatment',
                        'EMERGENCY' => 'Emergency Care',
                        'DATA_PROCESSING' => 'Data Processing',
                        'REFERRAL' => 'Referral',
                        'IMMUNIZATION' => 'Immunization',
                        'OTHER' => 'Other',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('given_by')
                    ->label('Given By')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Guardian name or patient name'),
                Forms\Components\TextInput::make('relationship')
                    ->label('Relationship')
                    ->maxLength(255)
                    ->placeholder('e.g., Parent, Guardian'),
                Forms\Components\Select::make('channel')
                    ->label('Channel')
                    ->options([
                        'SIGNED_FORM' => 'Signed Form',
                        'SMS' => 'SMS',
                        'EMAIL' => 'Email',
                        'VERBAL' => 'Verbal',
                        'PORTAL' => 'Portal',
                    ])
                    ->required()
                    ->default('PORTAL'),
                Forms\Components\Textarea::make('consent_text_version')
                    ->label('Consent Text')
                    ->required()
                    ->rows(4)
                    ->placeholder('The exact text the person consented to'),
                Forms\Components\DateTimePicker::make('given_at')
                    ->label('Given At')
                    ->required()
                    ->default(now()),
                Forms\Components\DateTimePicker::make('expires_at')
                    ->label('Expires At')
                    ->helperText('Leave empty if consent does not expire'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('consent_type')
            ->columns([
                Tables\Columns\TextColumn::make('consent_type')
                    ->label('Type')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('given_by')
                    ->label('Given By')
                    ->searchable(),
                Tables\Columns\TextColumn::make('relationship')
                    ->label('Relationship'),
                Tables\Columns\TextColumn::make('channel')
                    ->label('Channel')
                    ->badge(),
                Tables\Columns\TextColumn::make('given_at')
                    ->label('Given At')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Expires')
                    ->dateTime()
                    ->sortable()
                    ->color(fn ($record) => $record->expires_at && $record->expires_at->isPast() ? 'danger' : null),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('consent_type')
                    ->options([
                        'TREATMENT_GENERAL' => 'General Treatment',
                        'EMERGENCY' => 'Emergency Care',
                        'DATA_PROCESSING' => 'Data Processing',
                        'REFERRAL' => 'Referral',
                        'IMMUNIZATION' => 'Immunization',
                        'OTHER' => 'Other',
                    ]),
                Tables\Filters\Filter::make('expired')
                    ->label('Expired Consents')
                    ->query(fn ($query) => $query->where('expires_at', '<', now())),
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
            ->defaultSort('given_at', 'desc');
    }
}
