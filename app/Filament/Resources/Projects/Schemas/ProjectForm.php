<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('client_id')
                    ->relationship('client', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\DatePicker::make('start_date')
                    ->required(),
                Forms\Components\DatePicker::make('deadline'),
                Forms\Components\Select::make('status')
                    ->options([
                        'planned' => 'Planned',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                        'on_hold' => 'On Hold',
                    ])
                    ->required()
                    ->default('planned'),
                Forms\Components\Select::make('type')
                    ->options([
                        'fixed_price' => 'Fixed Price',
                        'hourly' => 'Hourly',
                        'retainer' => 'Retainer',
                    ])
                    ->required()
                    ->default('fixed_price'),
                Forms\Components\TextInput::make('budget')
                    ->numeric()
                    ->prefix('$'),
            ]);
    }
}
