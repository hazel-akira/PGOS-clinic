<?php

namespace App\Filament\Clinic\Resources\VisitResource\RelationManagers;

use App\Models\Medication;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class VisitMedicationRelationManager extends RelationManager
{
    protected static string $relationship = 'visitMedications';

    protected static ?string $title = 'Dispensed Medicines';

    /**
     * FORM (Dispensing Medicine)
     */
    public function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Select::make('medication_id')
                ->label('Medicine')
                ->relationship('medication', 'name')
                ->searchable()
                ->preload()
                ->required()
                ->reactive(),

            Forms\Components\TextInput::make('quantity_issued')
                ->label('Quantity Issued')
                ->numeric()
                ->minValue(1)
                ->required(),

            Forms\Components\TextInput::make('instructions')
                ->label('Instructions')
                ->placeholder('e.g. Take 1 tablet twice daily after meals')
                ->maxLength(255),

            Forms\Components\Textarea::make('notes')
                ->label('Notes')
                ->rows(2)
                ->placeholder('Optional notes about dispensing'),

            Forms\Components\DateTimePicker::make('issued_at')
                ->label('Issued At')
                ->default(now())
                ->required(),
        ]);
    }

    /**
     * TABLE (Display Dispensed Medicines)
     */
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('medication.name')
            ->columns([

                Tables\Columns\TextColumn::make('medication.name')
                    ->label('Medicine')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('quantity_issued')
                    ->label('Qty')
                    ->badge()
                    ->color('success')
                    ->sortable(),
                Tables\Columns\TextColumn::make('batch.batch_no')
                    ->label('Batch')
                    ->sortable(),

                Tables\Columns\TextColumn::make('batch.expiry_date')
                    ->label('Expiry')
                    ->date()
                    ->badge()
                    ->color('warning'),
    

                Tables\Columns\TextColumn::make('instructions')
                    ->label('Instructions')
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->instructions),

                Tables\Columns\TextColumn::make('issued_at')
                    ->label('Issued At')
                    ->dateTime('g:i A')
                    ->sortable(),

                Tables\Columns\TextColumn::make('issuer.name')
                    ->label('Issued By')
                    ->toggleable(),
            ])

            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Dispense Medicine')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['issued_by'] = auth()->id();
                        return $data;
                    })
                    ->after(function (Model $record) {

                        $medication = $record->medication;

                        // FEFO: batch nearest expiry
                        $batch = $medication->batches()
                            ->where('qty_on_hand', '>', 0)
                            ->orderBy('expiry_date')
                            ->first();

                        if (! $batch) {
                            throw new \Exception("No stock available for this medicine.");
                        }

                        // Determine quantity to deduct (ensure numeric)
                        $qty = (int) ($record->quantity_issued ?? 0);

                        if ($qty <= 0) {
                            throw new \Exception("Invalid quantity to issue.");
                        }

                        // Prevent negative stock
                        if ($batch->qty_on_hand < $qty) {
                            throw new \Exception("Not enough stock in batch.");
                        }

                        // Deduct stock
                        $batch->decrement('qty_on_hand', $qty);

                        // Save batch used
                        $record->update([
                            'stock_batch_id' => $batch->id,
                        ]);
                    })

            ])

            ->actions([
                Tables\Actions\EditAction::make(),

               Tables\Actions\DeleteAction::make()
                    ->after(function (Model $record) {

                        if ($record->batch) {
                            $record->batch->increment(
                                'qty_on_hand',
                                (int) ($record->quantity_issued ?? 0)
                            );
                        }
                    })

            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])

            ->defaultSort('dispensed_at', 'desc');
    }
}

