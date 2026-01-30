<?php

namespace App\Filament\Clinic\Resources;

use App\Filament\Clinic\Resources\MedicationResource\Pages;
use App\Models\Medication;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MedicationResource extends Resource
{
    protected static ?string $model = Medication::class;

    protected static ?string $navigationGroup = 'Pharmacy';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Medication Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('generic_name')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('manufacturer')
                            ->maxLength(255),

                        Forms\Components\Textarea::make('description')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Dosage Information')
                    ->schema([
                        Forms\Components\TextInput::make('dosage_form')
                            ->maxLength(100)
                            ->required(),

                        Forms\Components\TextInput::make('strength')
                            ->maxLength(100),

                        Forms\Components\Textarea::make('dosage_instructions')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Classification & Status')
                    ->schema([
                        Forms\Components\TextInput::make('category')
                            ->maxLength(100),

                        Forms\Components\Toggle::make('requires_prescription'),

                        Forms\Components\Toggle::make('is_controlled_substance'),

                        Forms\Components\Toggle::make('is_active')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('dosage_form')
                    ->sortable(),

                Tables\Columns\TextColumn::make('strength'),

                Tables\Columns\TextColumn::make('category')
                    ->sortable(),

                Tables\Columns\IconColumn::make('requires_prescription')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_controlled_substance')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\Filter::make('active')
                    ->query(fn ($query) => $query->where('is_active', true)),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

            ])
            ->bulkActions([

            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMedications::route('/'),
            'create' => Pages\CreateMedication::route('/create'),
            'edit' => Pages\EditMedication::route('/{record}/edit'),
        ];
    }
}
