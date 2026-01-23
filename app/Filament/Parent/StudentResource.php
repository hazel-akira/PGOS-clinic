<?php

namespace App\Filament\Parent;

use App\Filament\Parent\StudentResource\Pages;
use App\Filament\Parent\StudentResource\RelationManagers;
use App\Models\Person;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class StudentResource extends Resource
{
    protected static ?string $model = Person::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    
    protected static ?string $navigationLabel = 'My Children';
    
    protected static ?string $modelLabel = 'Child';
    
    protected static ?string $pluralModelLabel = 'Children';
    
    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();
        $query = parent::getEloquentQuery();
        
        if ($user->guardian) {
            return $query->whereHas('guardianLinks', function ($q) use ($user) {
                $q->where('guardian_id', $user->guardian->id);
            })->where('person_type', 'STUDENT');
        }
        
        return $query->whereRaw('1 = 0'); // Return empty if no guardian
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Parents have read-only access - cannot create or edit students
                Forms\Components\Placeholder::make('info')
                    ->label('')
                    ->content('Parents have read-only access to their children\'s information. Please contact the school administration for any updates.')
                    ->columnSpanFull(),
            ])
            ->disabled();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->label('First Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->label('Last Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('school.name')
                    ->label('School')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('enrolment.stream')
                    ->label('Class')
                    ->searchable(),
                Tables\Columns\TextColumn::make('age')
                    ->label('Age')
                    ->getStateUsing(fn (Person $record): ?string => $record->age ? "{$record->age} years" : null)
                    ->sortable(query: fn ($query, string $direction) => $query->orderBy('dob', $direction)),
                Tables\Columns\TextColumn::make('visits_count')
                    ->label('Total Visits')
                    ->counts('visits')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                //
            ])
            ->defaultSort('first_name');
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
            'index' => Pages\ListStudents::route('/'),
            'view' => Pages\ViewStudent::route('/{record}'),
        ];
    }
}
