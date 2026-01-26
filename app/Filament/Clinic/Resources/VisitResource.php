<?php

namespace App\Filament\Clinic\Resources;

use App\Filament\Clinic\Resources\VisitResource\Pages;
use App\Models\Person;
use App\Models\School;
use App\Models\Visit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class VisitResource extends Resource
{
    protected static ?string $model = Visit::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationLabel = 'Clinic Visits';

    protected static ?string $modelLabel = 'Visit';

    protected static ?string $pluralModelLabel = 'Visits';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Visit Information')
                    ->schema([
                        Forms\Components\Select::make('school_id')
                            ->label('School')
                            ->options(School::all()->pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('person_id')
                            ->label('Patient')
                            ->relationship('person', 'first_name', fn (Builder $query) => $query->where('status', 'ACTIVE'))
                            ->getOptionLabelFromRecordUsing(fn (Person $record): string => "{$record->first_name} {$record->last_name} ({$record->adm_or_staff_no})")
                            ->required()
                            ->searchable(['first_name', 'last_name', 'adm_or_staff_no'])
                            ->preload(),
                        Forms\Components\Select::make('visit_type')
                            ->options([
                                'ILLNESS' => 'Illness',
                                'INJURY' => 'Injury',
                                'FOLLOW_UP' => 'Follow-up',
                                'SCREENING' => 'Screening',
                                'OTHER' => 'Other',
                            ])
                            ->required()
                            ->default('ILLNESS'),
                        Forms\Components\DateTimePicker::make('arrival_at')
                            ->label('Arrival Time')
                            ->required()
                            ->default(now()),
                        Forms\Components\DateTimePicker::make('departure_at')
                            ->label('Departure Time'),
                        Forms\Components\Select::make('triage_level')
                            ->options([
                                'LOW' => 'Low',
                                'MEDIUM' => 'Medium',
                                'HIGH' => 'High',
                                'EMERGENCY' => 'Emergency',
                            ])
                            ->required()
                            ->default('LOW')
                            ->reactive(),
                        Forms\Components\Textarea::make('chief_complaint')
                            ->label('Chief Complaint')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('history_notes')
                            ->label('History Notes')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('assessment_notes')
                            ->label('Assessment Notes')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('disposition')
                            ->options([
                                'TREATED_AND_RETURNED' => 'Treated and Returned',
                                'RESTED_IN_CLINIC' => 'Rested in Clinic',
                                'SENT_HOME' => 'Sent Home',
                                'REFERRED' => 'Referred',
                                'AMBULANCE' => 'Ambulance',
                                'ADMITTED_OBS' => 'Admitted for Observation',
                            ]),
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
                Tables\Columns\TextColumn::make('person.first_name')
                    ->label('Patient')
                    ->formatStateUsing(fn (Visit $record): string => "{$record->person->first_name} {$record->person->last_name} ({$record->person->adm_or_staff_no})")
                    ->searchable(['person.first_name', 'person.last_name', 'person.adm_or_staff_no'])
                    ->sortable(),
                Tables\Columns\TextColumn::make('visit_type')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('arrival_at')
                    ->label('Arrival')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('triage_level')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'LOW' => 'success',
                        'MEDIUM' => 'warning',
                        'HIGH' => 'danger',
                        'EMERGENCY' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('chief_complaint')
                    ->limit(30)
                    ->tooltip(fn (Visit $record): string => $record->chief_complaint),
                Tables\Columns\TextColumn::make('disposition')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('departure_at')
                    ->label('Departure')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('school_id')
                    ->label('School')
                    ->relationship('school', 'name')
                    ->multiple(),
                Tables\Filters\SelectFilter::make('triage_level')
                    ->options([
                        'LOW' => 'Low',
                        'MEDIUM' => 'Medium',
                        'HIGH' => 'High',
                        'EMERGENCY' => 'Emergency',
                    ]),
                Tables\Filters\SelectFilter::make('visit_type')
                    ->options([
                        'ILLNESS' => 'Illness',
                        'INJURY' => 'Injury',
                        'FOLLOW_UP' => 'Follow-up',
                        'SCREENING' => 'Screening',
                        'OTHER' => 'Other',
                    ]),
                Tables\Filters\Filter::make('active_visits')
                    ->label('Active Visits Only')
                    ->query(fn ($query) => $query->whereNull('departure_at')),
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
            ->defaultSort('arrival_at', 'desc');
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
            'index' => Pages\ListVisits::route('/'),
            'create' => Pages\CreateVisit::route('/create'),
            'view' => Pages\ViewVisit::route('/{record}'),
            'edit' => Pages\EditVisit::route('/{record}/edit'),
        ];
    }

    /**
     * Enable global search by patient admission/staff number as well as name.
     */
    public static function getGloballySearchableAttributes(): array
    {
        return [
            'person.adm_or_staff_no',
            'person.first_name',
            'person.last_name',
        ];
    }
}
