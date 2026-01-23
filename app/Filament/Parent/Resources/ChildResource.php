<?php

namespace App\Filament\Parent\Resources;

use App\Filament\Parent\Resources\ChildResource\Pages;
use App\Models\Patient;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ChildResource extends Resource
{
    protected static ?string $model = Patient::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'My Children';
    protected static ?string $navigationGroup = 'My Children';
    protected static ?int $navigationSort = 1;
    protected static ?string $modelLabel = 'Child';
    protected static ?string $pluralModelLabel = 'Children';

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();
        
        // Get children linked to this parent
        return parent::getEloquentQuery()
            ->where('type', 'student')
            ->where('guardian_email', $user->email)
            ->where('is_active', true);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Read-only - parents cannot edit child information
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Name')
                    ->searchable(['first_name', 'last_name'])
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-m-user'),
                    
                Tables\Columns\TextColumn::make('student_id')
                    ->label('Student ID')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-identification'),
                    
                Tables\Columns\TextColumn::make('class')
                    ->label('Class')
                    ->badge()
                    ->color('primary'),
                    
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->label('Age')
                    ->formatStateUsing(fn ($state) => $state ? $state->age . ' years' : 'N/A')
                    ->icon('heroicon-m-cake'),
                    
                Tables\Columns\TextColumn::make('gender')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'male' => 'info',
                        'female' => 'success',
                        default => 'gray',
                    }),
                    
                Tables\Columns\IconColumn::make('consent_emergency_care')
                    ->label('Emergency Consent')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->emptyStateHeading('No Children Found')
            ->emptyStateDescription('Please contact the school office to link your children to your account.')
            ->emptyStateIcon('heroicon-o-user-group');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChildren::route('/'),
            'view' => Pages\ViewChild::route('/{record}'),
        ];
    }
    
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
}
