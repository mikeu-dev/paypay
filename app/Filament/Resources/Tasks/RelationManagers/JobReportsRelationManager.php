<?php

namespace App\Filament\Resources\Tasks\RelationManagers;

use App\Models\JobReport;
use App\Models\Task;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class JobReportsRelationManager extends RelationManager
{
    protected static string $relationship = 'jobReports';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description'),
                Forms\Components\Select::make('employee_id')
                    ->relationship('employee', 'name')
                    ->label('Employee')
                    ->searchable(),
                Forms\Components\Select::make('status')
                    ->options([
                        JobReport::STATUS_WAITING => 'Waiting',
                        JobReport::STATUS_TODO => 'To Do',
                        JobReport::STATUS_ON_PROGRESS => 'On Progress',
                        JobReport::STATUS_COMPLETE => 'Complete',
                    ])
                    ->default(JobReport::STATUS_WAITING)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        JobReport::STATUS_WAITING => 'gray',
                        JobReport::STATUS_TODO => 'warning',
                        JobReport::STATUS_ON_PROGRESS => 'info',
                        JobReport::STATUS_COMPLETE => 'success',
                    }),
                Tables\Columns\TextColumn::make('employee.name')
                    ->label('Assigned To'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
