<?php

namespace App\Filament\Parent\Widgets;

use App\Models\Person;
use App\Models\Patient;
use App\Models\Visit;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class RecentVisits extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Recent Medical Visits')
            ->query(
                $this->getVisitsQuery()
            )
            ->columns([
                Tables\Columns\TextColumn::make('person.full_name')
                    ->label('Child Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-m-user'),
                    
                Tables\Columns\TextColumn::make('arrival_at')
                    ->label('Visit Date')
                    ->dateTime('M d, Y h:i A')
                    ->sortable()
                    ->icon('heroicon-m-calendar'),
                    
                Tables\Columns\TextColumn::make('visit_type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'ILLNESS' => 'danger',
                        'INJURY' => 'warning',
                        'SCREENING' => 'info',
                        'FOLLOW_UP' => 'success',
                        default => 'gray',
                    }),
                    
                Tables\Columns\TextColumn::make('chief_complaint')
                    ->label('Complaint')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
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
                    
                Tables\Columns\IconColumn::make('departure_at')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-clock')
                    ->trueColor('success')
                    ->falseColor('warning')
                    ->tooltip(fn ($record): string => $record->departure_at ? 'Completed' : 'In Progress'),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('View Details')
                    ->icon('heroicon-m-eye')
                    ->url(fn (Visit $record): string => route('filament.parent.resources.visits.view', ['record' => $record]))
                    ->openUrlInNewTab(false),
            ])
            ->defaultSort('arrival_at', 'desc')
            ->paginated([10, 25, 50])
            ->poll('30s')
            ->emptyStateHeading('No Medical Visits')
            ->emptyStateDescription('Your children have not visited the clinic yet.')
            ->emptyStateIcon('heroicon-o-heart');
    }

    protected function getVisitsQuery(): Builder
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
        return Visit::query()
            ->whereIn('person_id', $personIds)
            ->where('arrival_at', '>=', now()->subDays(90))
            ->with(['person', 'vitals', 'diagnoses', 'treatments', 'prescriptions']);
    }
}
