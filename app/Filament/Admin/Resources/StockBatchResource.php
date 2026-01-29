<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\StockBatchResource\Pages;
use App\Models\StockBatch;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\TrashedFilter;

class StockBatchResource extends Resource
{
    protected static ?string $model = StockBatch::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Inventory Control';
    protected static ?string $navigationLabel = 'Stock Batches';

   

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Batch Information')
                ->schema([
                    Forms\Components\Hidden::make('inventory_id')
                        ->default(request()->get('inventory_id'))
                        ->required(),
                    Forms\Components\Select::make('inventory_id')
                        ->label('Medication')
                        ->relationship('inventory', 'id')
                        ->getOptionLabelFromRecordUsing(
                            fn ($record) => $record->medication?->name ?? 'Unknown Medication'
                        )
                        ->searchable()
                        ->preload()
                        ->visible(fn () => ! request()->has('inventory_id'))
                        ->required(),

                    Forms\Components\TextInput::make('batch_no')->required()->label('Batch Number'),
                    Forms\Components\DatePicker::make('expiry_date')->label('Expiry Date'),
                    Forms\Components\TextInput::make('qty_on_hand')->numeric()->required()->label('Quantity On Hand'),
                    Forms\Components\TextInput::make('unit_cost')->numeric()->label('Unit Cost (KES)'),
                    Forms\Components\Select::make('supplier_id')
                        ->relationship('supplier', 'name')
                        ->searchable()
                        ->preload()
                        ->label('Supplier')
                        ->nullable(),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('inventory.medication.name')->label('Medication')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('batch_no')->label('Batch #')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('qty_on_hand')->label('Qty Available')->sortable(),
                Tables\Columns\TextColumn::make('expiry_date')->label('Expiry')->date()->sortable(),
                Tables\Columns\TextColumn::make('supplier.name')->label('Supplier')->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('unit_cost')->money('KES')->label('Unit Cost')->toggleable(),
                Tables\Columns\IconColumn::make('is_expired')->label('Expired?')->boolean()->state(fn ($record) => $record->isExpired()),
                Tables\Columns\TextColumn::make('created_at')->date()->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
                Tables\Filters\Filter::make('expired')->label('Expired')->query(fn ($query) => $query->whereDate('expiry_date', '<', now())),
                Tables\Filters\Filter::make('expiring_soon')->label('Expiring Soon (30 days)')->query(fn ($query) => $query->whereBetween('expiry_date', [now(), now()->addDays(30)])),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            
            ->defaultSort('created_at', 'desc');
    }
    public static function canCreate(): bool
    {
        return true;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStockBatches::route('/'),
            'edit' => Pages\EditStockBatch::route('/{record}/edit'),
            'create' => Pages\CreateStockBatch::route('/create'), // hidden from nav
        ];
    }
}
