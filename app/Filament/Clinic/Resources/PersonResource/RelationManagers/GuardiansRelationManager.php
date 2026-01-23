<?php

namespace App\Filament\Clinic\Resources\PersonResource\RelationManagers;

use App\Models\Guardian;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class GuardiansRelationManager extends RelationManager
{
    protected static string $relationship = 'guardianLinks';

    protected static ?string $title = 'Guardian Contacts';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('guardian_id')
                    ->label('Guardian')
                    ->relationship('guardian', 'full_name')
                    ->getOptionLabelFromRecordUsing(fn (Guardian $record): string => "{$record->full_name} ({$record->phone})")
                    ->searchable(['full_name', 'phone', 'email'])
                    ->required()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('full_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\Select::make('relationship')
                            ->options([
                                'PARENT' => 'Parent',
                                'GUARDIAN' => 'Guardian',
                                'OTHER' => 'Other',
                            ])
                            ->default('PARENT')
                            ->required(),
                    ]),
                Forms\Components\Toggle::make('is_primary')
                    ->label('Primary Guardian')
                    ->default(false),
                Forms\Components\Textarea::make('notes')
                    ->label('Notes')
                    ->rows(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('guardian.full_name')
            ->columns([
                Tables\Columns\TextColumn::make('guardian.full_name')
                    ->label('Guardian Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('guardian.phone')
                    ->label('Phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('guardian.email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('guardian.relationship')
                    ->label('Relationship')
                    ->badge(),
                Tables\Columns\IconColumn::make('is_primary')
                    ->label('Primary')
                    ->boolean(),
                Tables\Columns\TextColumn::make('notes')
                    ->label('Notes')
                    ->limit(30)
                    ->toggleable(),
            ])
            ->filters([
                //
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
            ]);
    }
}
