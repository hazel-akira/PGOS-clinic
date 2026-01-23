<?php

namespace App\Filament\Parent\Resources;

use App\Filament\Parent\Resources\VisitResource\Pages;
use App\Models\Visit;
use App\Models\Person;
use App\Models\Patient;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class VisitResource extends Resource
{
    protected static ?string $model = Visit::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Medical Visits';
    protected static ?string $navigationGroup = 'Medical Reports';
    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();
        
        // Get children linked to this parent
        $children = Patient::where('type', 'student')
            ->where('guardian_email', $user->email)
            ->where('is_active', true)
            ->get();
            
        // Get student IDs
        $studentIds = $children->pluck('student_id')->filter();
        
        // Find corresponding person IDs
        $personIds = Person::whereIn('adm_or_staff_no', $studentIds)
            ->pluck('id');
        
        // Return visits for these persons
        return parent::getEloquentQuery()
            ->whereIn('person_id', $personIds);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Read-only form - parents can only view, not edit
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('person.full_name')
                    ->label('Child Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                    
                Tables\Columns\TextColumn::make('arrival_at')
                    ->label('Arrival Time')
                    ->dateTime('M d, Y h:i A')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('departure_at')
                    ->label('Departure Time')
                    ->dateTime('M d, Y h:i A')
                    ->placeholder('Still at clinic')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('visit_type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'ILLNESS' => 'danger',
                        'INJURY' => 'warning',
                        'SCREENING' => 'info',
                        'FOLLOW_UP' => 'success',
                        default => 'gray',
                    }),
                    
                Tables\Columns\TextColumn::make('triage_level')
                    ->label('Priority')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'EMERGENCY' => 'danger',
                        'HIGH' => 'warning',
                        'MEDIUM' => 'primary',
                        'LOW' => 'success',
                        default => 'gray',
                    }),
                    
                Tables\Columns\TextColumn::make('disposition')
                    ->label('Outcome')
                    ->badge()
                    ->color('success')
                    ->formatStateUsing(fn (?string $state): string => $state ? str_replace('_', ' ', $state) : 'In Progress'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('person_id')
                    ->label('Child')
                    ->options(function () {
                        $user = Auth::user();
                        $children = Patient::where('type', 'student')
                            ->where('guardian_email', $user->email)
                            ->where('is_active', true)
                            ->get();
                        $studentIds = $children->pluck('student_id', 'student_id')->filter();
                        $persons = Person::whereIn('adm_or_staff_no', $studentIds)->get();
                        return $persons->pluck('full_name', 'id');
                    }),
                    
                Tables\Filters\SelectFilter::make('visit_type')
                    ->options([
                        'ILLNESS' => 'Illness',
                        'INJURY' => 'Injury',
                        'SCREENING' => 'Screening',
                        'FOLLOW_UP' => 'Follow Up',
                        'OTHER' => 'Other',
                    ]),
                    
                Tables\Filters\Filter::make('active_visits')
                    ->label('Active Visits Only')
                    ->query(fn (Builder $query): Builder => $query->whereNull('departure_at')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->defaultSort('arrival_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVisits::route('/'),
            'view' => Pages\ViewVisit::route('/{record}'),
        ];
    }
    
    public static function canCreate(): bool
    {
        return false; // Parents cannot create visits
    }
    
    public static function canEdit($record): bool
    {
        return false; // Parents cannot edit visits
    }
    
    public static function canDelete($record): bool
    {
        return false; // Parents cannot delete visits
    }
}
