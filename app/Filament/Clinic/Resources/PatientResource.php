<?php

namespace App\Filament\Clinic\Resources;

use App\Filament\Clinic\Resources\PatientResource\Pages;
use App\Models\Patient;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;

class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Patients';
    protected static ?string $navigationGroup = 'Patient Management';

    protected static ?string $recordTitleAttribute = 'full_name';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Section::make('Patient Type')
                ->schema([
                    Forms\Components\Select::make('patient_type')
                        ->options([
                            'STUDENT' => 'Student',
                            'STAFF' => 'Staff',
                            'VISITOR' => 'Visitor',
                        ])
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(fn (callable $set) => $set('external_id', null)),
                ]),

            Forms\Components\Section::make('Student Lookup')
                ->schema([
                    Forms\Components\TextInput::make('external_id')
                        ->label('Student Admission Number')
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            $set('first_name', 'John');
                            $set('last_name', 'Doe');
                            $set('is_synced', true);
                        }),
                ])
                ->visible(fn ($get) => $get('patient_type') === 'STUDENT'),

            Forms\Components\Section::make('Staff Lookup')
                ->schema([
                    Forms\Components\TextInput::make('external_id')
                        ->label('Staff Number')
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            $set('first_name', 'Jane');
                            $set('last_name', 'Smith');
                            $set('is_synced', true);
                        }),
                ])
                ->visible(fn ($get) => $get('patient_type') === 'STAFF'),

            Forms\Components\Section::make('Visitor Details')
                ->schema([
                    Forms\Components\TextInput::make('first_name')
                        ->required()
                        ->disabled(fn ($record) => $record?->is_synced),

                    Forms\Components\TextInput::make('last_name')
                        ->required()
                        ->disabled(fn ($record) => $record?->is_synced),
                ])
                ->columns(2)
                ->visible(fn ($get) => $get('patient_type') === 'VISITOR'),

            Forms\Components\Section::make('Contact Information')
                ->schema([
                    Forms\Components\TextInput::make('phone')->tel(),
                    Forms\Components\TextInput::make('email')->email(),
                ])
                ->columns(2),

            Forms\Components\Hidden::make('is_synced'),

            Forms\Components\Section::make('Status')
                ->schema([
                    Forms\Components\Select::make('status')
                        ->options([
                            'ACTIVE' => 'Active',
                            'INACTIVE' => 'Inactive',
                        ])
                        ->required(),
                ]),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Patient')
                    ->searchable(),

                Tables\Columns\TextColumn::make('patient_type')
                    ->badge()
                    ->colors([
                        'success' => 'STUDENT',
                        'info' => 'STAFF',
                        'warning' => 'VISITOR',
                    ]),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => 'ACTIVE',
                        'gray' => 'INACTIVE',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Create Visitor')
                    ->icon('heroicon-o-user-plus')
                    ->mutateFormDataUsing(fn (array $data) => [
                        ...$data,
                        'patient_type' => 'VISITOR',
                        'is_synced' => false,
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPatients::route('/'),
            'view' => Pages\ViewPatient::route('/{record}'),
            'edit' => Pages\EditPatient::route('/{record}/edit'),
        ];
    }
}
