<?php

namespace App\Filament\Clinic\Resources;

use App\Filament\Clinic\Resources\PersonResource\Pages;
use App\Models\Person;
use App\Models\School;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PersonResource extends Resource
{
    protected static ?string $model = Person::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationLabel = 'Students & Staff';
    
    protected static ?string $modelLabel = 'Person';
    
    protected static ?string $pluralModelLabel = 'Persons';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\Select::make('school_id')
                            ->label('School')
                            ->relationship('school', 'name')
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                // Apply automatic gender rules only for students
                                if ($get('person_type') !== 'STUDENT' || blank($state)) {
                                    return;
                                }

                                $school = School::find($state);
                                if (! $school) {
                                    return;
                                }

                                $name = $school->name;

                                // Girls-only schools
                                $girlsSchools = [
                                    'Pioneer Girls School',
                                    'Pioneer Girls Junior Academy',
                                ];

                                // Boys-only schools
                                $boysSchools = [
                                    'Pioneer School',
                                    'Pioneer Junior Academy',
                                    'St Paul Thomas Academy',
                                ];

                                if (in_array($name, $girlsSchools, true)) {
                                    $set('gender', 'female');
                                } elseif (in_array($name, $boysSchools, true)) {
                                    $set('gender', 'male');
                                }
                            })
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('person_type')
                            ->options([
                                'STUDENT' => 'Student',
                                'STAFF' => 'Staff',
                                'VISITOR' => 'Visitor',
                            ])
                            ->required()
                            ->reactive(),
                        Forms\Components\TextInput::make('adm_or_staff_no')
                            ->label('Admission/Staff Number')
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('first_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('last_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('other_names')
                            ->maxLength(255),
                        Forms\Components\Select::make('gender')
                            ->options([
                                'male' => 'Male',
                                'female' => 'Female',
                                'other' => 'Other',
                            ]),
                        Forms\Components\DatePicker::make('dob')
                            ->label('Date of Birth'),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\Select::make('status')
                            ->options([
                                'ACTIVE' => 'Active',
                                'INACTIVE' => 'Inactive',
                            ])
                            ->default('ACTIVE')
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('school.name')
                    ->label('School')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('person_type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'STUDENT' => 'success',
                        'STAFF' => 'info',
                        'VISITOR' => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('adm_or_staff_no')
                    ->label('ID Number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('first_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gender')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'ACTIVE' => 'success',
                        'INACTIVE' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('school_id')
                    ->label('School')
                    ->relationship('school', 'name')
                    ->multiple(),
                Tables\Filters\SelectFilter::make('person_type')
                    ->options([
                        'STUDENT' => 'Student',
                        'STAFF' => 'Staff',
                        'VISITOR' => 'Visitor',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'ACTIVE' => 'Active',
                        'INACTIVE' => 'Inactive',
                    ]),
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
            'index' => Pages\ListPeople::route('/'),
            'create' => Pages\CreatePerson::route('/create'),
            'view' => Pages\ViewPerson::route('/{record}'),
            'edit' => Pages\EditPerson::route('/{record}/edit'),
        ];
    }

    /**
     * Enable global search by admission/staff number as well as name.
     */
    public static function getGloballySearchableAttributes(): array
    {
        return [
            'adm_or_staff_no',
            'first_name',
            'last_name',
            'email',
        ];
    }
}
