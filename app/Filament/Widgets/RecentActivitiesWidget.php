<?php

namespace App\Filament\Widgets;

use Filament\Actions\BulkActionGroup;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class RecentActivitiesWidget extends TableWidget
{
    protected static ?int $sort = 3;
    
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => \Spatie\Activitylog\Models\Activity::query()->latest()->limit(10))
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('causer.name')
                    ->label('User')
                    ->icon('heroicon-m-user')
                    ->placeholder('System'),
                \Filament\Tables\Columns\TextColumn::make('description')
                    ->label('Action')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'warning',
                        'deleted' => 'danger',
                        default => 'secondary',
                    }),
                \Filament\Tables\Columns\TextColumn::make('subject_type')
                    ->label('Subject')
                    ->formatStateUsing(fn ($state) => class_basename($state)),
                \Filament\Tables\Columns\TextColumn::make('created_at')
                    ->label('Time')
                    ->dateTime()
                    ->sortable(),
            ]);
    }
}
