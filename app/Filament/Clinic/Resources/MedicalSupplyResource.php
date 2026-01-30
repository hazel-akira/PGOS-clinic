<?php

namespace App\Filament\Clinic\Resources;

use App\Filament\Clinic\Resources\MedicalSupplyResource\Pages;
use App\Models\MedicalSupply;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MedicalSupplyResource extends Resource
{
    protected static ?string $model = MedicalSupply::class;

    protected static ?string $navigationGroup = 'Pharmacy';

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Supply Details')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('unit_of_measure')
                        ->label('Unit of Measure')
                        ->maxLength(50)
                        ->helperText('e.g. pcs, pkts, bottles'),

                    Forms\Components\TextInput::make('category')
                        ->maxLength(100),

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

                Tables\Columns\TextColumn::make('unit_of_measure')
                    ->label('Unit'),

                Tables\Columns\TextColumn::make('category')
                    ->sortable(),

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
            ->bulkActions([]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMedicalSupplies::route('/'),
            'create' => Pages\CreateMedicalSupply::route('/create'),
            'edit' => Pages\EditMedicalSupply::route('/{record}/edit'),
        ];
    }
}
