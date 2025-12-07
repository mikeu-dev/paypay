<?php

namespace App\Filament\Resources\Deductions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DeductionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Select::make('type')
                    ->options(['fixed' => 'Fixed', 'percentage' => 'Percentage'])
                    ->default('fixed')
                    ->required(),
                TextInput::make('value')
                    ->required()
                    ->numeric(),
            ]);
    }
}
