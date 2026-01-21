<?php

namespace App\Filament\Clinic\Resources;

use App\Filament\Clinic\Resources\ItemResource\Pages;
use App\Models\Item;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-beaker';
    
    protected static ?string $navigationLabel = 'Medications & Items';
    
    protected static ?string $modelLabel = 'Item';
    
    protected static ?string $pluralModelLabel = 'Items';
    
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Item Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('form')
                            ->label('Form (e.g., tablet, syrup)')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('strength')
                            ->label('Strength (e.g., 500mg)')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('unit')
                            ->label('Unit (e.g., tabs, ml)')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('reorder_level')
                            ->label('Reorder Level')
                            ->numeric()
                            ->default(10)
                            ->required(),
                        Forms\Components\Toggle::make('is_medicine')
                            ->label('Is Medicine')
                            ->default(true),
                        Forms\Components\Toggle::make('active')
                            ->label('Active')
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
                Tables\Columns\TextColumn::make('form')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('strength')
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit')
                    ->sortable(),
                Tables\Columns\TextColumn::make('reorder_level')
                    ->label('Reorder Level')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_medicine')
                    ->label('Medicine')
                    ->boolean(),
                Tables\Columns\IconColumn::make('active')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('is_medicine')
                    ->label('Type')
                    ->options([
                        1 => 'Medicine',
                        0 => 'Other Item',
                    ]),
                Tables\Filters\TernaryFilter::make('active')
                    ->label('Active Only'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => auth()->user()->hasRole('admin')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()->hasRole('admin')),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'view' => Pages\ViewItem::route('/{record}'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }
}
