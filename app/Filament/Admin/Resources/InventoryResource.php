<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\InventoryResource\Pages;
use App\Filament\Admin\Resources\StockBatchResource;
use App\Models\Inventory;
use App\Models\StockBatch;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Enums\FiltersLayout;
use Carbon\Carbon;

class InventoryResource extends Resource
{
    protected static ?string $model = Inventory::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationGroup = 'Inventory Control';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Inventory Item')
                ->schema([
                    Forms\Components\Select::make('medication_id')
                        ->relationship('medication', 'name')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->label('Medication'),

                    Forms\Components\TextInput::make('minimum_stock_level')
                        ->numeric()
                        ->default(10)
                        ->required()
                        ->label('Minimum Stock Level'),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->columns([
                Tables\Columns\TextColumn::make('medication.name')
                    ->label('Medication')
                    ->searchable(),

                Tables\Columns\TextColumn::make('quantity_available')
                    ->label('Available')
                    ->sortable()
                    ->getStateUsing(fn ($record) => $record->batches()->sum('qty_on_hand')),

                Tables\Columns\TextColumn::make('is_low_stock')
                    ->badge()
                    ->label('Low Stock')
                    ->sortable()
                    ->getStateUsing(fn ($record) => (int)$record->batches()->whereNull('deleted_at')->sum('qty_on_hand') <= ($record->minimum_stock_level ?? 10))
                    ->colors([
                        'success' => fn ($state) => $state === false,
                        'danger' => fn ($state) => $state === true,
                    ])
                    ->formatStateUsing(fn ($state) => $state ? 'Low' : 'OK'),

                Tables\Columns\TextColumn::make('total_stock_in')
                    ->label('Stock In')
                    ->getStateUsing(fn ($record) =>
                        $record->batches()
                            ->whereHas('transactions', fn ($q) =>
                                $q->where('type', 'in')
                            )
                            ->withSum([
                                'transactions as stock_in_sum' => fn ($q) =>
                                    $q->where('type', 'in')
                            ], 'quantity')
                            ->get()
                            ->sum('stock_in_sum')
                    ),

                Tables\Columns\TextColumn::make('total_stock_out')
                    ->label('Stock Out')
                    ->getStateUsing(fn ($record) =>
                        $record->batches()
                            ->whereHas('transactions', fn ($q) =>
                                $q->where('type', 'out')
                            )
                            ->withSum(['transactions as stock_out_sum' => fn ($q) =>
                                $q->where('type', 'out')
                            ], 'quantity')
                            ->get()
                            ->sum('stock_out_sum')
                    ),

                Tables\Columns\TextColumn::make('latest_batch')
                    ->label('Latest Batch')
                    ->getStateUsing(fn ($record) => $record->batches()->latest('created_at')->value('batch_no') ?? '-'),

                Tables\Columns\TextColumn::make('nearest_expiry')
                    ->label('Expiry')
                    ->getStateUsing(fn ($record) => 
                        $record->batches()->orderBy('expiry_date')->value('expiry_date')?->format('Y-m-d') ?? '-'
                    ),
                Tables\Columns\TextColumn::make('created_at')->date(),
            ])
            ->filters([
                SelectFilter::make('low_stock')
                    ->label('Stock Level')
                    ->options([
                        'low' => 'Low Stock',
                        'ok' => 'In Stock',
                    ])
                    ->query(fn (Builder $query, array $data) => $data['value'] === 'low'
                        ? $query->whereHas('batches', fn($q) => $q->selectRaw('inventory_id, SUM(qty_on_hand) as total')->groupBy('inventory_id')->havingRaw('SUM(qty_on_hand) <= 10'))
                        : ($data['value'] === 'ok'
                            ? $query->whereHas('batches', fn($q) => $q->selectRaw('inventory_id, SUM(qty_on_hand) as total')->groupBy('inventory_id')->havingRaw('SUM(qty_on_hand) > 10'))
                            : $query)
                    ),

                SelectFilter::make('expiry')
                    ->label('Expiry Status')
                    ->options([
                        'expired' => 'Expired',
                        'expiring' => 'Expiring Soon (30 days)',
                    ])
                    ->query(fn (Builder $query, array $data) => match($data['value'] ?? '') {
                        'expired' => $query->whereHas('batches', fn($q) => $q->whereDate('expiry_date', '<', now())),
                        'expiring' => $query->whereHas('batches', fn($q) => $q->whereBetween('expiry_date', [now(), now()->addDays(30)])),
                        default => $query,
                    }),

                Filter::make('batch_number')
                    ->form([Forms\Components\TextInput::make('batch_no')->label('Batch Number')])
                    ->query(fn (Builder $query, array $data) => filled($data['batch_no'])
                        ? $query->whereHas('batches', fn ($q) => $q->where('batch_no', 'like', '%' . $data['batch_no'] . '%'))
                        : $query
                    ),
            ], layout: FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),

                // NEW ACTION: Add Batch
                Tables\Actions\Action::make('add_batch')
                    ->label('Add Batch')
                    ->icon('heroicon-o-plus')
                    ->button()
                    ->action(fn ($record) => redirect(
                        StockBatchResource::getUrl('create', ['inventory_id' => $record->id])
                    )),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInventories::route('/'),
            'create' => Pages\CreateInventory::route('/create'),
            'edit' => Pages\EditInventory::route('/{record}/edit'),
        ];
    }
}
