<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\InventoryResource\Pages;
use App\Models\Inventory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Resources\Components\Tab;



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
            Forms\Components\Select::make('medication_id')
                ->relationship('medication', 'name')
                ->required(),

            Forms\Components\TextInput::make('batch_number')
                ->label('Batch Number')
                ->required(),

            Forms\Components\DatePicker::make('expiry_date')
                ->label('Expiry Date'),

            Forms\Components\TextInput::make('quantity_in')
                ->label('Initial Stock')
                ->numeric()
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->defaultSort('created_at', 'desc')
            ->columns([
                // Medication Name
                Tables\Columns\TextColumn::make('medication.name')
                    ->searchable()
                    ->label('Medication'),

                // Total Available Stock (sum of all batches)
                Tables\Columns\TextColumn::make('quantity_available')
                    ->label('Available')
                    ->sortable()
                    ->getStateUsing(fn ($record) => $record->batches()->sum('qty_on_hand')),

                Tables\Columns\TextColumn::make('is_low_stock')
                    ->badge()
                    ->label('Low Stock')
                    ->sortable()
                    ->getStateUsing(function ($record) {
                        $threshold = $record->minimum_stock_level ?? 10;
                        $available = (int) $record->batches()->whereNull('deleted_at')->sum('qty_on_hand');
                        return $available <= $threshold;
                    })
                    ->colors([
                        'success' => fn ($state) => $state === false,
                        'danger' => fn ($state) => $state === true,
                    ])
                    ->formatStateUsing(fn ($state) => $state ? 'Low' : 'OK'),
    

                // Total Stock In (sum of all 'in' transactions)
                Tables\Columns\TextColumn::make('total_stock_in')
                    ->label('Stock In')
                    ->getStateUsing(fn ($record) => $record->batches()
                        ->with('transactions')
                        ->get()
                        ->sum(fn ($batch) => $batch->transactions
                            ->where('type', 'in')
                            ->sum('quantity')
                        )
                    ),

                // Total Stock Out (sum of all 'out' transactions)
                Tables\Columns\TextColumn::make('total_stock_out')
                    ->label('Stock Out')
                    ->getStateUsing(fn ($record) => $record->batches()
                        ->with('transactions')
                        ->get()
                        ->sum(fn ($batch) => $batch->transactions
                            ->where('type', 'out')
                            ->sum('quantity')
                        )
                    ),

                // Display the latest batch number (or combine multiple)
                Tables\Columns\TextColumn::make('latest_batch')
                    ->label('Batch Number')
                    ->getStateUsing(fn ($record) => $record->batches()->latest('created_at')->value('batch_no') ?? '-')
                    ->searchable(
                        query: fn (Builder $query, string $search) =>
                            $query->whereHas('batches', fn ($q) =>
                                $q->where('batch_no', 'like', "%{$search}%")
                            )
                    ),

                // Display nearest expiry date
                Tables\Columns\TextColumn::make('nearest_expiry')
                    ->label('Expiry Date')
                    ->date()
                    ->getStateUsing(fn ($record) => $record->batches()->orderBy('expiry_date')->value('expiry_date') ?? '-'),

                Tables\Columns\TextColumn::make('created_at')->date(),
            ])
            ->filters([
                SelectFilter::make('low_stock')
                    ->label('Stock Level')
                    ->options([
                        'low' => 'Low Stock',
                        'ok' => 'In Stock',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (! $data['value']) {
                            return $query;
                        }

                        return $query->whereHas('batches', function ($q) use ($data) {
                            $q->selectRaw('inventory_id, SUM(qty_on_hand) as total')
                            ->groupBy('inventory_id')
                            ->havingRaw(
                                $data['value'] === 'low'
                                    ? 'SUM(qty_on_hand) <= 10'
                                    : 'SUM(qty_on_hand) > 10'
                            );
                        });
                    }),

                // Expiry status filter
                SelectFilter::make('expiry')
                    ->label('Expiry Status')
                    ->options([
                        'expired' => 'Expired',
                        'expiring' => 'Expiring Soon (30 days)',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if ($data['value'] === 'expired') {
                            return $query->whereHas('batches', fn ($q) =>
                                $q->whereDate('expiry_date', '<', now())
                            );
                        }

                        if ($data['value'] === 'expiring') {
                            return $query->whereHas('batches', fn ($q) =>
                                $q->whereBetween('expiry_date', [now(), now()->addDays(30)])
                            );
                        }

                        return $query;
                    }),

                // Batch number filter
                Filter::make('batch_number')
                    ->form([
                        Forms\Components\TextInput::make('batch_no')
                            ->label('Batch Number'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (! filled($data['batch_no'])) {
                            return $query;
                        }

                        return $query->whereHas('batches', fn ($q) =>
                            $q->where('batch_no', 'like', '%' . $data['batch_no'] . '%')
                        );
                    }),
            ],
            layout: FiltersLayout::AboveContent)

            ->actions([
                
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
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
