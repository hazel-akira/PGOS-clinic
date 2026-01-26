<?php

namespace App\Filament\Clinic\Resources;

use App\Filament\Clinic\Resources\IncidentResource\Pages;
use App\Models\Incident;
use App\Models\Person;
use App\Models\School;
use App\Models\Visit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class IncidentResource extends Resource
{
    protected static ?string $model = Incident::class;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';

    protected static ?string $navigationLabel = 'Incidents & Cases';

    protected static ?string $modelLabel = 'Incident';

    protected static ?string $pluralModelLabel = 'Incidents';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Incident Information')
                    ->schema([
                        Forms\Components\Select::make('school_id')
                            ->label('School')
                            ->options(School::all()->pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('person_id')
                            ->label('Person Involved')
                            ->relationship('person', 'first_name', fn (Builder $query) => $query->where('status', 'ACTIVE'))
                            ->getOptionLabelFromRecordUsing(fn (Person $record): string => "{$record->first_name} {$record->last_name} ({$record->adm_or_staff_no})")
                            ->required()
                            ->searchable(['first_name', 'last_name', 'adm_or_staff_no'])
                            ->preload(),
                        Forms\Components\Select::make('incident_type')
                            ->label('Incident Type')
                            ->options([
                                'INJURY' => 'Injury',
                                'ACCIDENT' => 'Accident',
                                'VIOLENCE' => 'Violence',
                                'ALLERGIC_REACTION' => 'Allergic Reaction',
                                'OTHER' => 'Other',
                            ])
                            ->required()
                            ->native(false),
                        Forms\Components\DateTimePicker::make('occurred_at')
                            ->label('Occurred At')
                            ->required()
                            ->default(now())
                            ->native(false),
                        Forms\Components\TextInput::make('location')
                            ->label('Location')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Playground, Dorm, Lab'),
                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('severity')
                            ->label('Severity')
                            ->options([
                                'LOW' => 'Low',
                                'MEDIUM' => 'Medium',
                                'HIGH' => 'High',
                                'CRITICAL' => 'Critical',
                            ])
                            ->required()
                            ->default('MEDIUM')
                            ->native(false)
                            ->reactive(),
                        Forms\Components\Toggle::make('is_emergency')
                            ->label('Is Emergency?')
                            ->helperText('Mark this case as requiring immediate attention')
                            ->inline(false)
                            ->default(false)
                            ->reactive(),
                        Forms\Components\Select::make('linked_visit_id')
                            ->label('Linked Visit (Optional)')
                            ->relationship('linkedVisit', 'id', fn (Builder $query) => $query->orderBy('arrival_at', 'desc'))
                            ->getOptionLabelFromRecordUsing(fn (Visit $record): string => "Visit #{$record->id} - {$record->person->first_name} {$record->person->last_name} ({$record->arrival_at->format('M d, Y H:i')})")
                            ->searchable()
                            ->preload(),
                        Forms\Components\Hidden::make('reported_by_user_id')
                            ->default(Auth::id()),
                    ])->columns(2),

                Forms\Components\Section::make('Actions & Follow-up')
                    ->schema([
                        Forms\Components\Textarea::make('actions_taken')
                            ->label('Actions Taken')
                            ->rows(4)
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('parents_notified')
                            ->label('Parents Notified?')
                            ->inline(false)
                            ->default(false)
                            ->reactive(),
                        Forms\Components\DateTimePicker::make('parents_notified_at')
                            ->label('Parents Notified At')
                            ->visible(fn (Forms\Get $get) => $get('parents_notified'))
                            ->native(false),
                    ])->columns(2),
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
                Tables\Columns\TextColumn::make('person.full_name')
                    ->label('Person')
                    ->formatStateUsing(fn (Incident $record): string => "{$record->person->first_name} {$record->person->last_name}")
                    ->description(fn (Incident $record): string => $record->person->adm_or_staff_no ?? '')
                    ->searchable(['person.first_name', 'person.last_name'])
                    ->sortable(),
                Tables\Columns\TextColumn::make('incident_type')
                    ->label('Type')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('occurred_at')
                    ->label('Occurred At')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('location')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('severity')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'LOW' => 'success',
                        'MEDIUM' => 'info',
                        'HIGH' => 'warning',
                        'CRITICAL' => 'danger',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_emergency')
                    ->label('Emergency')
                    ->boolean()
                    ->trueIcon('heroicon-o-exclamation-triangle')
                    ->trueColor('danger')
                    ->sortable(),
                Tables\Columns\IconColumn::make('parents_notified')
                    ->label('Parents Notified')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reportedBy.name')
                    ->label('Reported By')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('school_id')
                    ->label('School')
                    ->relationship('school', 'name')
                    ->native(false)
                    ->preload(),
                Tables\Filters\SelectFilter::make('incident_type')
                    ->options([
                        'INJURY' => 'Injury',
                        'ACCIDENT' => 'Accident',
                        'VIOLENCE' => 'Violence',
                        'ALLERGIC_REACTION' => 'Allergic Reaction',
                        'OTHER' => 'Other',
                    ])
                    ->native(false)
                    ->preload(),
                Tables\Filters\SelectFilter::make('severity')
                    ->options([
                        'LOW' => 'Low',
                        'MEDIUM' => 'Medium',
                        'HIGH' => 'High',
                        'CRITICAL' => 'Critical',
                    ])
                    ->native(false)
                    ->preload(),
                Tables\Filters\TernaryFilter::make('is_emergency')
                    ->label('Emergency Cases')
                    ->nullable()
                    ->trueLabel('Only Emergencies')
                    ->falseLabel('Non-Emergencies')
                    ->indicator('Emergency'),
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
            ])
            ->defaultSort('occurred_at', 'desc');
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
            'index' => Pages\ListIncidents::route('/'),
            'create' => Pages\CreateIncident::route('/create'),
            'view' => Pages\ViewIncident::route('/{record}'),
            'edit' => Pages\EditIncident::route('/{record}/edit'),
        ];
    }
}
