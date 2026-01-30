<?php

namespace App\Filament\Clinic\Resources;

use App\Filament\Clinic\Resources\ClinicInventoryResource\Pages;
use App\Models\Inventory;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ClinicInventoryResource extends Resource
{
    protected static ?string $model = Inventory::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationGroup = 'Pharmacy';
    protected static ?string $navigationLabel = 'Clinic Inventory';
    protected static ?int $navigationSort = 3;
    protected static ?string $pluralLabel = 'Clinic Inventory';

    /**
     * Clinic staff should NOT create/edit/delete inventory.
     */
    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    /**
     * Main inventory table for nurses/doctors.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                // Medicine Name
                Tables\Columns\TextColumn::make('medication.name')
                    ->label('Medicine')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('available_stock')
                    ->label('Available Stock')
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state == 0 => 'danger',
                        $state < 10 => 'warning',
                        default => 'success',
                    })
                    ->getStateUsing(fn ($record) => $record->batches()->sum('qty_on_hand')),

                // Nearest Expiry Batch Date
                Tables\Columns\TextColumn::make('nearest_expiry')
                    ->label('Nearest Expiry')
                    ->date()
                    ->getStateUsing(fn ($record) =>
                        $record->batches()
                            ->where('qty_on_hand', '>', 0)
                            ->orderBy('expiry_date')
                            ->value('expiry_date')
                    )
                    ->placeholder('N/A'),

                // Latest Batch No (Optional)
                Tables\Columns\TextColumn::make('latest_batch')
                    ->label('Latest Batch')
                    ->getStateUsing(fn ($record) =>
                        $record->batches()
                            ->latest('created_at')
                            ->value('batch_no')
                    )
                    ->placeholder('-'),

                // Low Stock Warning Icon
                Tables\Columns\IconColumn::make('low_stock')
                    ->label('Low Stock?')
                    ->boolean()
                    ->state(fn ($record) =>
                        $record->available_stock < 10
                    ),
                    

                // Expiring Soon Warning Icon
                Tables\Columns\IconColumn::make('expiring_soon')
                    ->label('Expiring Soon?')
                    ->boolean()
                    ->state(fn ($record) =>
                        $record->batches()
                            ->whereBetween('expiry_date', [now(), now()->addDays(30)])
                            ->where('qty_on_hand', '>', 0)
                            ->exists()
                    ),
            ])

            ->filters([

                // Low Stock Filter
                Tables\Filters\Filter::make('low_stock')
                    ->label('Low Stock (<10)')
                    ->query(fn ($query) =>
                        $query->where('available_stock', '<', 10)
                    ),

                // Expiring Soon Filter
                Tables\Filters\Filter::make('expiring_soon')
                    ->label('Expiring Soon (30 days)')
                    ->query(fn ($query) =>
                        $query->whereHas('batches', fn ($q) =>
                            $q->whereBetween('expiry_date', [now(), now()->addDays(30)])
                              ->where('qty_on_hand', '>', 0)
                        )
                    ),

                // Out of Stock Filter
                Tables\Filters\Filter::make('out_of_stock')
                    ->label('Out of Stock')
                    ->query(fn ($query) =>
                        $query->where('available_stock', '=', 0)
                    ),
            ])

            ->actions([
                // Clinic side = view only
            ])

            ->bulkActions([
                // none
            ])

            ->defaultSort('available_stock', 'asc');
    }

    /**
     * Pages for Clinic Inventory Resource
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClinicInventories::route('/'),
        ];
    }
}
